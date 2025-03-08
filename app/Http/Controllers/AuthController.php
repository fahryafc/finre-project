<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Invites;
use App\Models\PhoneNumber;
use App\Models\ReferalUser;
use App\Models\UserProfile;
use Illuminate\Support\Str;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Jobs\EmailVerification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Test
        $message = [
            'required' => 'The :attribute field is required.',
            'email' => 'The :attribute must be a valid email address.',
            'unique' => 'The :attribute has already been taken.',
            'min' => 'The :attribute must be at least :min characters.',
            'numeric' => 'The :attribute must be numeric.',
        ];

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|numeric|min:12|unique:user_profiles,nomor_hp',
            'password' => 'required|min:6'
        ], $message);

        // Create user
        $user = User::create([
            'name' => Str::title($request->name),
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        // Create phone
        $user->user_profile()->create([
            'nomor_hp' => $request->phone
        ]);

        Subscription::create([
            'user_id' => $user->id,
            'nama_paket' => 'Free Trial',
            'max_invite' => 0,
            'starts_at' => now(),
            'ends_at' => now()->addHours(24),
            'status' => 'active'
        ]);

        $permission = [
            'dashboard',
            'penjualan',
            'pengeluaran',
            'hutang-piutang',
            'kas-bank',
            'pajak',
            'produk-inventori',
            'aset',
            'laporan',
            'kontak',
            'akun',
            'modal'
        ];

        $user->givePermissionTo($permission);

        // Jika ada referal code dalam session, maka kirim request ke API Referal
        if (session()->has('referal_code')) {
            $res = Http::post(env('APP_REFERAL_URL') . '/api/add-registered-link', [
                'referal_code' => session('referal_code')
            ]);

            // Jika statusnya 200, maka simpan referal code ke database
            if ($res->status() == 200) {
                ReferalUser::create([
                    'user_id' => $user->id,
                    'referal' => session('referal_code'),
                ]);

                session()->forget('referal_code'); // Hapus referal code dari session
            } else {

                session()->forget('referal_code'); // Hapus referal code dari session
                return abort(404);
            }
        }

        return redirect('/login');
    }

    public function email_verify()
    {
        $user = Auth::user(); // Get data user login

        // Kirim email verifikasi melalui job queue
        // Run in terminal: php artisan queue:work
        EmailVerification::dispatch($user);

        return redirect()->back()->with('success', 'Email verification sent!');
    }

    public function update_account(Request $request)
    {
        $user = User::find(Auth::user()->id); // Get data user login

        if (!auth()->user()->hasRole('owner')) {
            $user_profile = UserProfile::where('user_id', Auth::user()->id)->first(); // Get data phone
        }

        // $request->delete_image merupakan request dari javascript melalui fetch, yg berfungsi apabila user ingin menghapus gambarnya saja
        if ($request->delete_image) {
            $path = ('public/user_images/' . $user_profile->gambar);

            Storage::delete($path);

            $user_profile->update([
                'gambar' => NULL,
            ]);

            return response()->json(['message' => 'Image deleted successfully.'], 200);
        }

        $message = [
            'required' => 'The :attribute field is required.',
            'email' => 'The :attribute must be a valid email address.',
            'unique' => 'The :attribute has already been taken.',
            'min' => 'The :attribute must be at least :min characters.',
            'numeric' => 'The :attribute must be numeric.',
            'mimes' => 'The :attribute must be a file of type: jpg, jpeg, png.',
            'max' => 'The :attribute may not be greater than :max kilobytes.',
        ];

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . Auth::user()->id,
            'password' => 'nullable|min:6',
            'image' => 'nullable|max:2048|mimes:jpg,jpeg,png',
        ], $message);

        if ($request->image) {
            $path = ('public/user_images/' . $user_profile->gambar);

            Storage::delete($path);

            $request->image->storeAs('public/user_images', $request->image->hashName());

            $user_profile->update([
                'gambar' => $request->image->hashName(),
            ]);
        }

        if ($request->bidang) {
            $user_profile->update([
                'bidang' => Str::title($request->bidang),
            ]);
        }

        if ($request->company_name) {
            $user_profile->update([
                'nama_perusahaan' => Str::title($request->company_name),
            ]);
        }

        if ($request->jumlah_karyawan) {
            $user_profile->update([
                'jumlah_karyawan' => $request->jumlah_karyawan,
            ]);
        }

        if ($request->address) {
            $user_profile->update([
                'alamat' => $request->address,
            ]);
        }

        if ($request->phone) {
            $request->validate([
                'phone' => 'nullable|numeric|min:12|unique:user_profiles,nomor_hp,' . $user_profile->id,
            ]);

            // Update phone
            $user_profile->update([
                'nomor_hp' => $request->phone,
            ]);
        }

        // Jika password tidak kosong
        if ($request->password) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        // Update user
        $user->update([
            'name' => Str::title($request->name),
            'email' => $request->email,
        ]);

        alert()->success('Success', 'Profil berhasil diubah');

        return redirect()->back();
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:5'
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember_me)) {
            $request->session()->regenerate();

            $user = Auth::user(); // Simpan dalam variabel agar tidak memanggil berkali-kali
            $redirectTo = '/daftar-paket'; // Default redirect jika tidak ada kondisi yang terpenuhi

            if ($user->hasRole('inviter')) {
                $redirectTo = '/dashboard';
            } elseif ($user->hasRole('owner')) {
                $redirectTo = '/dashboard-owner';
            } else {
                // Cek apakah user diundang
                $invite_status = Invites::where('email', $user->email)->where('status', 'accepted')->exists();

                if ($invite_status) {
                    // Cek apakah user memiliki permission
                    $permission = $user->permissions->first(); // Lazy loading
                    $redirectTo = $permission ? '/' . $permission->name : '/waiting-permission';
                }
            }

            // Cek apakah ada referal code dalam session
            if (session()->has('referal_code') && !ReferalUser::where('user_id', $user->id)->exists()) {
                ReferalUser::create([
                    'user_id' => $user->id,
                    'referal' => session('referal_code'),
                ]);

                session()->forget('referal_code'); // Hapus session setelah digunakan
            }

            return redirect()->intended($redirectTo);
        }

        return back()->withErrors([
            'email' => 'Email or password is wrong',
        ])->onlyInput('email');
    }

    public function forget_password(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Cek user
        $check_user = User::where('email', $request->email)->exists();

        // Jika user ditemukan
        if ($check_user) {
            session([
                'email' => $request->email,
                'rand_slug' => Str::random(50)
            ]);

            return redirect('/reset-password/' . session('rand_slug'));
        }

        return back()->withErrors([
            'email' => 'Email not found',
        ]);
    }

    public function reset_password(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6',
        ]);

        // Update password
        User::where('email', session('email'))->update([
            'password' => Hash::make($request->password)
        ]);

        // Hapus email dari session
        session()->forget('email');

        return redirect('/login');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
