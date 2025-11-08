<?php

namespace App\Factories\Auth;

use App\Dtos\Auth\RegisterDto;
use App\Http\Requests\Auth\RegisterRequest;

class RegisterDtoFactory
{
    public static function fromRequest(RegisterRequest $request): RegisterDto
    {
        return new RegisterDto(
            email: $request->validated('email'),
            name: $request->validated('name'),
            password: $request->validated('password')
        );
    }
}
