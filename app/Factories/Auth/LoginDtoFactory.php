<?php

namespace App\Factories\Auth;

use App\Dtos\Auth\LoginDto;
use App\Http\Requests\Auth\LoginRequest;

class LoginDtoFactory
{
    public static function fromRequest(LoginRequest $request): LoginDto
    {
        return new LoginDto(
            email: $request->validated('email'),
            password: $request->validated('password')
        );
    }
}
