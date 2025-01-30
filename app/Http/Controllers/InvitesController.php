<?php

namespace App\Http\Controllers;

use App\Mail\InviteMail;
use App\Models\User;
use App\Models\Invites;
use Illuminate\Support\Str;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Permission;

class InvitesController extends Controller
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
        // Cek subscription user
        $subsription = Subscription::where('user_id', Auth::id())->where('status', 'active')->first();

        // Cek user yang diinvite
        $checkUser = User::where('email', $request->email)->first();

        // Jika user tidak ada, maka tidak bisa menginvite
        if (!$checkUser) {
            alert()->warning('Failed', 'User not found');

            return redirect()->back();
        }

        // Jika user yang diinvite sudah memiliki role inviter, sudah meng acc permintaan sebelumnya atau memiliki permission, maka tidak bisa diinvite
        if ($checkUser->hasRole('inviter') || count($checkUser->permissions) > 0 || Invites::where('email', $request->email)->where('status', 'accepted')->exists()) {
            alert()->warning('Failed', 'User already has a role as inviter, has permissions or has accepted other invitation');

            return redirect()->back();
        }

        // Jika jumlah invite yang sudah dikirim lebih dari atau sama dengan maksimal invite yang diperbolehkan, maka tidak bisa menginvite
        if (Subscription::findOrFail($subsription->id)->invites()->count() >= $subsription->max_invite) {
            alert()->warning('Failed', 'You have reached the maximum number of invitations');

            return redirect()->back();
        }

        // Jika email yang diinvite sudah pernah diinvite sebelumnya dan status nya masih pending, maka tidak bisa menginvite
        if (Invites::where('subscription_id', $subsription->id)->where('email', $request->email)->where('status', 'pending')->exists()) {
            alert()->warning('Failed', 'Invitation already sent');

            return redirect()->back();
        }

        // Jika email yang diinvite sudah pernah diinvite sebelumnya dan status nya rejected, namun subsription penginvite masih aktif
        $check = Invites::where('subscription_id', $subsription->id)
            ->where('email', $request->email)
            ->where('status', 'rejected')
            ->first();

        // Jika email yang diinvite sudah pernah diinvite sebelumnya dan status nya rejected, namun subsription penginvite masih aktif
        if ($check) {
            $check->update([
                'token' => Str::random(32),
                'expires_at' => now()->addDays(7),
                'status' => 'pending',
            ]);

            $invite = $check;
        } else {
            $invite = Invites::create([
                'subscription_id' => $subsription->id,
                'email' => $request->email,
                'token' => Str::random(32),
                'expires_at' => now()->addDays(7), // Berlaku sampai 7 hari kedepan
            ]);
        }

        // Kirim email
        Mail::to($request->email)->send(new InviteMail($invite));

        alert()->success('Success', 'Invitation sent successfully');

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Invites $invites, $email)
    {
        $user = User::where('email', $email)->first(); // Get user
        $permissions = Permission::all(); // Get all permission

        return view('pages.role-managament.index', compact('permissions', 'user'));
    }

    public function change_permission(Request $request, $id)
    {
        $user = User::findOrFail($id); // Get user

        $user->syncPermissions($request->permissions); // Update permission

        alert()->success('Success', 'Permission updated successfully');

        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invites $invites)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invites $invites, $invitation_id)
    {
        $find = $invites->find($invitation_id); // Get invitation

        $find->update([
            'status' => $request->status,
        ]);

        alert()->success('Success', 'Invitation status updated successfully');

        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invites $invites, $id)
    {
        $find = $invites->find($id);

        $user = User::where('email', $find->email)->first(); // Get user

        // Revoke permission
        foreach ($user->getPermissionNames() as $permission) {
            $user->revokePermissionTo($permission);
        }

        $find->delete();

        alert()->success('Success', 'Invitation deleted successfully');

        return redirect()->back();
    }
}
