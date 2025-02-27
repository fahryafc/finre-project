<?php

namespace App\Http\Controllers;

use App\Models\Invites;
use App\Models\PaymentHistory;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class PagesController extends Controller
{
    public function index()
    {
        return view('pages.index');
    }

    public function dashboard_owner(Request $request)
    {
        $data['user_active'] = User::with('subscription')
            ->whereHas('subscription', function ($query) {
                $query->where('status', 'active');
            })->count();

        $data['user_inactive'] = User::with('subscription')
            ->whereHas('subscription', function ($query) {
                $query->where('status', 'expired');
            })->count();

        $data['all_user'] = Subscription::with('user', 'payment_history')
            ->whereHas('user', function ($query) use ($request) {
                $query->when($request->keyword, function ($q) use ($request) {
                    $q->where('name', 'LIKE', '%' . $request->keyword . '%');
                });
            })
            ->when($request->from && $request->to, function ($query) use ($request) {
                $query->whereBetween('updated_at', [$request->from, $request->to . ' 23:59:59']);
            })
            ->when($request->from && !$request->to, function ($query) use ($request) {
                $query->where('updated_at', '>=', $request->from);
            })
            ->paginate(10);

        $data['paket_personal'] = Subscription::where('nama_paket', 'Personal')->count();
        $data['paket_business'] = Subscription::where('nama_paket', 'Business')->count();
        $data['paket_enterprise'] = Subscription::where('nama_paket', 'Enterprise')->count();

        return view('pages.owner.index', compact('data'));
    }

    public function pendapatan_owner(Request $request)
    {
        $data['total_pendapatan'] = PaymentHistory::sum('harga');

        $data['all_user'] = DB::table('users')
            ->join('subscriptions', 'users.id', '=', 'subscriptions.user_id')
            ->join('user_profiles', 'users.id', '=', 'user_profiles.user_id')
            ->join('payment_histories', 'subscriptions.id', '=', 'payment_histories.subscription_id')
            ->when($request->keyword, function ($query) use ($request) {
                $query->where('users.name', 'LIKE', '%' . $request->keyword . '%');
            })
            ->when($request->from && $request->to, function ($query) use ($request) {
                $query->whereBetween('subscriptions.updated_at', [$request->from, $request->to . ' 23:59:59']);
            })
            ->when($request->from && !$request->to, function ($query) use ($request) {
                $query->where('subscriptions.updated_at', '>=', $request->from);
            })
            ->select(
                'users.*',
                'subscriptions.*',
                'subscriptions.updated_at as subscription_updated_at',
                'user_profiles.alamat',
                'user_profiles.nomor_hp',
                'payment_histories.periode',
                'payment_histories.harga',
                'payment_histories.status as payment_status',
            )->paginate(10);

        $data['pendapatan_personal'] = PaymentHistory::where('nama_paket', 'personal')->sum('harga');
        $data['pendapatan_business'] = PaymentHistory::where('nama_paket', 'business')->sum('harga');
        $data['pendapatan_enterprise'] = PaymentHistory::where('nama_paket', 'enterprise')->sum('harga');

        return view('pages.owner.pendapatan', compact('data'));
    }

    public function user_list(Request $request)
    {
        $data = User::with('subscription', 'user_profile')
            ->withoutRole('owner')
            ->when($request->keyword, function ($query) use ($request) {
                $query->where('name', 'LIKE', '%' . $request->keyword . '%');
            })
            ->paginate(10);
        // dd($data->toArray());
        return view('pages.owner.user-list', compact('data'));
    }

    public function detail_langganan_user(Request $request, $id)
    {
        $subscription = Subscription::with('user.user_profile')->findOrFail($id);

        $payment_history = $subscription->payment_history()
            ->when($request->from && $request->to, function ($query) use ($request) {
                $query->whereBetween('created_at', [$request->from, $request->to . ' 23:59:59']);
            })
            ->when($request->from && !$request->to, function ($query) use ($request) {
                $query->where('created_at', '>=', $request->from);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // dd($subscription->toArray());

        return view('pages.owner.detail', compact('subscription', 'payment_history'));
    }

    public function paket()
    {
        if (auth()->user()->hasRole('owner')) {
            return redirect('/dashboard-owner');
        }

        // Jika role user inviter atau status member accepted maka mengembalikan ke halaman sebelumnya
        if (Auth::user()->hasRole('inviter') || Invites::where('email', Auth::user()->email)->where('status', 'accepted')->exists()) {
            return redirect('/waiting-permission');
        }

        return view('pages.paket.index');
    }

    public function login()
    {
        return view('pages.auth.index');
    }

    public function register()
    {
        return view('pages.auth.register');
    }

    public function forget_password()
    {
        return view('pages.auth.forget-password');
    }

    public function reset_password($slug)
    {
        // Jika slug tidak sama dengan slug yang ada di session, maka lempar ke halaman abort
        if ($slug != session('rand_slug') && !session()->has('rand_slug')) {
            return abort(404);
        }

        // Hapus slug dari session
        session()->forget('rand_slug');

        return view('pages.auth.reset-password');
    }

    public function join_from_afiliate(Request $request)
    {
        $ref = $request->input('ref');

        // Jika ref ada, maka lempar ke halaman login
        if ($ref) {
            // Kirim request ke API Referal
            $res = Http::post(env('APP_REFERAL_URL') . '/api/referal-track', [
                'referal_code' => $ref
            ]);

            // Jika statusnya 200, maka lempar ke halaman login
            if ($res->status() == 200) {
                // Simpan referal code ke session
                session()->put('referal_code', $ref);

                return redirect('/login');
                // return redirect()->away('https://finre.id/');
            }

            return abort(404);
        }

        return abort(404);
    }

    public function checkout(Request $request)
    {
        // Jika user belum verifikasi email, maka lempar ke halaman sebelumnya
        if (!Auth::user()->email_verified_at || Invites::where('email', Auth::user()->email)->where('status', 'accepted')->exists()) {
            return redirect()->back();
        }

        $nama = $request->input('name');
        $harga = str_replace(".", "", $request->input('price'));
        $jml_user = $request->input('users');
        $periode = $request->input('periode');

        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;

        session()->put('order_id', rand());

        $params = array(
            'transaction_details' => array(
                'order_id' => session('order_id'),
                'gross_amount' => $harga . '000',
            ),
            'customer_details' => array(
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ),
        );

        // Generate snap token
        $snapToken = \Midtrans\Snap::getSnapToken($params);

        $data = [
            'nama' => $nama,
            'harga' => $request->input('price'),
            'jml_user' => $jml_user,
            'periode' => $periode,
        ];

        return view('pages.paket.checkout', compact('data', 'snapToken'));
    }

    public function paket_setting()
    {
        // Get data paket
        $information = Subscription::where('user_id', Auth::user()->id)->first();

        // Get data member
        $members = Invites::where('subscription_id', $information->id)->get();

        return view('pages.paket.setting', compact('information', 'members'));
    }

    public function invitation_page($token)
    {
        // Jika token tidak sesuai dengan token yang ada di database, maka lempar ke halaman 404
        $checkUser = Invites::where('token', $token)
            ->where('email', Auth::user()->email)
            ->where('expires_at', '>', now())
            ->where('status', 'pending')
            ->first();

        // JIka token tidak sesuai
        if (!$checkUser) {
            return abort(404);
        }

        // Jika token sesuai, maka lempar ke halaman invitation
        $detail = DB::table('invites')
            ->join('subscriptions', 'invites.subscription_id', '=', 'subscriptions.id')
            ->join('users', 'subscriptions.user_id', '=', 'users.id')
            ->where('invites.token', $token)
            ->where('invites.status', 'pending')
            ->where('invites.expires_at', '>', now())
            ->select('users.*', 'invites.id as invite_id')
            ->first();

        return view('pages.invitation.index', compact('detail'));
    }

    public function waiting_permission()
    {
        if (Auth::user()->hasRole('owner')) {
            return redirect('/dashboard-owner');
        }

        if (Auth::user()->hasRole('inviter')) {
            return redirect('/dashboard');
        }

        $user = Auth::user(); // Get data user login

        // Jika role user inviter
        $invite_status = Invites::where('email', $user->email)->where('status', 'accepted')->first();

        // Jika user terinvite
        if ($invite_status) {
            // JIka user memiliki permission
            if (count($user->permissions) > 0) {
                return redirect()->intended('/' . $user->permissions->toArray()[0]['name']);
            }
        } else {
            return redirect('/daftar-paket');
        }

        return view('pages.waiting-permission');
    }

    public function settings()
    {
        // Get data user
        $user = User::with('user_profile')->where('id', Auth::user()->id)->first();

        return view('pages.settings', compact('user'));
    }
}
