<?php

namespace App\Dtos\Auth;

use App\Models\User;
use Laravel\Sanctum\NewAccessToken;

final readonly class LoggedInDto
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public User $user,
        public NewAccessToken $accessToken
    )
    {
        //
    }
}
