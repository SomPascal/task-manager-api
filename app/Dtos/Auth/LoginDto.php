<?php

namespace App\Dtos\Auth;

final readonly class LoginDto
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public string $email,
        public string $password
    )
    {
        //
    }
}
