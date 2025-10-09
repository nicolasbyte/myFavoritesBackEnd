<?php

namespace App\Listeners;

use Illuminate\Support\Facades\DB;
use Laravel\Passport\Events\AccessTokenCreated;

class RevokeOldTokens
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(AccessTokenCreated $event): void
    {
        // Find the IDs of all previous, unrevoked access tokens for this user
        $oldTokenIds = DB::table('oauth_access_tokens')
            ->where('user_id', $event->userId)
            ->where('id', '!=', $event->tokenId)
            ->where('revoked', false)
            ->pluck('id');

        // If there are no old tokens, there's nothing to do
        if ($oldTokenIds->isEmpty()) {
            return;
        }

        // Revoke all the old access tokens
        DB::table('oauth_access_tokens')
            ->whereIn('id', $oldTokenIds)
            ->update(['revoked' => true]);

        // Revoke all refresh tokens associated with those old access tokens
        DB::table('oauth_refresh_tokens')
            ->whereIn('access_token_id', $oldTokenIds)
            ->update(['revoked' => true]);
    }
}
