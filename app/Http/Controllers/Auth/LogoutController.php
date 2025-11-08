<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {
        $this->channel = 'auth';
    }

    public function logout(): JsonResponse
    {
        $user = auth('sanctum')->user();

        try {
            $this->authService->logout($user);

            return $this->success(
                message: 'You were successfuly logged out'
            );
        } catch (\Throwable $th) {
            $this->logException(
                'Could not logout a user',
                $th
            );

            return $this->failServerError();
        }
    }
}
