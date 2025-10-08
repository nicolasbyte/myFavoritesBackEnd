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
        DB::table('oauth_access_tokens')
            ->where('id', '!=', $event->tokenId)
            ->where('user_id', $event->userId)
            ->where('revoked', false)
            ->update(['revoked' => true]);
    }
}
