<?php

namespace App\Http\Controllers;

use App\Models\Invites;
use App\Models\PaymentHistory;
use App\Models\ReferalUser;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $nama = $request->input('name');
        $harga = str_replace(".", "", $request->input('price'));
        $jml_user = $request->input('users');
        $periode = $request->input('periode');

        // Encode midtrans server key
        $encode = base64_encode(config('services.midtrans.server_key') . ':');

        // Get request ke API Midtrans
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . $encode,
        ])->get('https://api.sandbox.midtrans.com/v2/' . session('order_id') . '/status');

        // Jika status transaksi berhasil, maka simpan orderan ke table order
        if ($response['transaction_status'] == 'capture') {
            $checkReferal = ReferalUser::where('user_id', auth()->user()->id)->first(); // Cek apakah user memiliki referal

            // Jika user memiliki referal, maka tambahkan comission
            if ($checkReferal) {
                $res = Http::post(env('APP_REFERAL_URL') . '/api/add-comissions', [
                    'referal_code' => $checkReferal->referal,
                    'comission' => 175000
                ]);

                if ($res->status() != 200) {
                    return abort(404);
                }

                // Hapus referal user setelah mendapatkan comission
                $checkReferal->delete();
            }

            // Hitung tanggal akhir subscription
            switch ($periode) {
                case '6 bulan':
                    $end_at = now()->addMonths(6);
                    break;
                case 'tahunan':
                    $end_at = now()->addYears(1);
                    break;
                default:
                    $end_at = now()->addMonths();
                    break;
            }

            // Cek apakah user sudah memiliki subscription
            $checkSubscription = Subscription::where('user_id', auth()->user()->id)->first();

            // Jika user sudah memiliki subscription, maka update subscription
            if ($checkSubscription) {
                $checkSubscription->update([
                    'nama_paket' => $nama,
                    'max_invite' => $jml_user,
                    'starts_at' => now(),
                    'ends_at' => $end_at,
                    'status' => 'active'
                ]);
            } else {
                // Jika user belum memiliki subscription, maka buat subscription
                $checkSubscription = Subscription::create([
                    'user_id' => auth()->user()->id,
                    'nama_paket' => $nama,
                    'max_invite' => $jml_user,
                    'starts_at' => now(),
                    'ends_at' => $end_at,
                    'status' => 'active'
                ]);
            }

            // Jika user pernah atau ada menjadi member
            if (Invites::where('email', Auth::user()->email)->exists()) {
                $user = User::where('email', Auth::user()->email)->first(); // Get user

                // Revoke permission
                foreach ($user->getPermissionNames() as $permission) {
                    $user->revokePermissionTo($permission);
                }

                Invites::where('email', Auth::user()->email)->delete();
            }

            // Jika user pernah atau ada menjadi member
            if (Invites::where('subscription_id', $checkSubscription->id)->exists()) {
                Invites::where('subscription_id', $checkSubscription->id)
                    ->where('status', 'inactive')
                    ->update([
                        'status' => 'accepted'
                    ]);
            }

            $user = Auth::user();

            // Jika user belum memiliki role inviter, maka tambahkan role inviter
            $user->assignRole('inviter');

            // Simpan payment history
            PaymentHistory::create([
                'subscription_id' => $checkSubscription->id,
                'nama_paket' => $nama,
                'periode' => $periode,
                'harga' => $harga . '000'
            ]);

            alert()->success('Berhasil', 'Pembayaran berhasil!');

            // Kembali ke halaman store/toko
            return redirect('/paket-setting')->with('success', 'Pembelian berhasil!');
        } else {
            // Cek apakah user sudah memiliki subscription
            $checkSubscription = Subscription::where('user_id', auth()->user()->id)->first();

            PaymentHistory::create([
                'subscription_id' => $checkSubscription->id,
                'nama_paket' => $nama,
                'periode' => $periode,
                'harga' => $harga . '000',
                'status' => 'failed',
            ]);

            alert()->error('Gagal', 'Pembayaran gagal!');

            // Kembali ke halaman store/toko
            return redirect('/paket-setting')->with('success', 'Pembelian berhasil!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Subscription $subscription)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subscription $subscription)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subscription $subscription)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subscription $subscription)
    {
        //
    }
}
