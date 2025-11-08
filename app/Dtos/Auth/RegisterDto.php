<?php

namespace App\Dtos\Auth;

final readonly class RegisterDto
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public string $email,
        public string $name,
        public string $password
    )
    {
        //
    }
}
