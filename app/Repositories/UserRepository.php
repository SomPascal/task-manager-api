<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function findByEmail(string $email, array $with = []): ?User
    {
        return User::with($with)
        ->where('email', $email)
        ->first();
    }
}
