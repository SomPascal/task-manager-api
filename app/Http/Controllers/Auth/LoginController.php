<?php

namespace App\Http\Controllers\Auth;

use App\Constants\HttpCode;
use App\Exceptions\Auth\WrongCredentialsException;
use App\Factories\Auth\LoginDtoFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function __construct(
        protected AuthService $authService,
    ) {
        $this->channel = 'auth';
    }

    public function login(LoginRequest $request): JsonResponse
    {
        
        try {
            $loginDto = LoginDtoFactory::fromRequest($request);
            $loggedInDto = $this->authService->login($loginDto);

            return $this->success(
                message: 'User successfuly logged in',
                data: [
                    'token' => $loggedInDto->accessToken->plainTextToken,
                    'user' => new UserResource($loggedInDto->user)
                ]
            );
        } catch (WrongCredentialsException $e) {
            return $this->fail(
                message: 'Wrong credentials',
                code: HttpCode::WRONG_CREDENTIALS,
                status: 403
            );
        }
        catch (\Throwable $th) {
            $this->logException(
                'Could not login a user',
                $th
            );

            return $this->failServerError();
        }
    }
}
