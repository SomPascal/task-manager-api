<?php

namespace App\Http\Controllers\Auth;

use App\Factories\Auth\RegisterDtoFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {
        $this->channel = 'auth';
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $registerDto = RegisterDtoFactory::fromRequest($request);
            $loggedInDto = $this->authService->register($registerDto);
    
            return $this->success(
                message: 'Successfuly registered',
                data: [
                    'token' => $loggedInDto->accessToken->plainTextToken,
                    'user' => new UserResource($loggedInDto->user)
                ]
            );
        } catch (\Throwable $th) {
            $this->logException(
                message: 'Could not login a user',
                th: $th
            );

            return $this->failServerError(
                message: 'Could not register the user'
            );
        }
    }
}
