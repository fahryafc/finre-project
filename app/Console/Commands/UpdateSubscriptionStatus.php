<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Invites;
use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\ExpiringSubscriptionMail;

class UpdateSubscriptionStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update subscription status based on expiry date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Subscription::where('ends_at', '<', Carbon::now())
        //     ->where('status', 'active')
        //     ->where('nama_paket', '!=', 'Free Trial')
        //     ->update(['status' => 'expired']);

        $users = User::with('subscription')->get();

        foreach ($users as $user) {
            Log::info('Checking subscription for user: ' . $user->id);

            if ($user->subscription && $user->subscription->ends_at && Carbon::now()->gt($user->subscription->ends_at)) {
                Log::info('User ' . $user->id . ' subscription expired, removing role inviter');

                if ($user->hasRole('inviter')) {
                    $user->removeRole('inviter');
                }

                Mail::to($user->email)->send(new ExpiringSubscriptionMail($user));

                Invites::where('subscription_id', $user->subscription->id)
                    ->where('status', 'accepted')
                    ->update(['status' => 'inactive']);

                Subscription::where('user_id', $user->id)
                    ->where('nama_paket', 'Free Trial')
                    ->where('status', 'active')
                    ->update(['status' => 'expired']);

                $user_permissions = User::find($user->id);
                $permissions = $user_permissions->getPermissionNames();

                if ($permissions->isNotEmpty()) {
                    foreach ($permissions as $permission) {
                        $user_permissions->revokePermissionTo($permission);
                    }
                }

                $this->info("Email dikirim ke {$user->email} dan status free trial diubah menjadi expired.");
            }
        }

        $this->info('Subscription statuses updated successfully.');
    }
}
