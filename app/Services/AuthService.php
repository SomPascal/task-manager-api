<?php

namespace App\Services;

use App\Dtos\Auth\LoggedInDto;
use App\Dtos\Auth\LoginDto;
use App\Dtos\Auth\RegisterDto;
use App\Exceptions\Auth\WrongCredentialsException;
use App\Models\User;
use App\Repositories\CategoryRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    protected const TOKEN_NAME = 'Personal Access Token';

    /**
     * Create a new class instance.
     */
    public function __construct(
        protected UserRepository $userRepository,
        protected CategoryRepository $categoryRepository
    )
    {
        //
    }

    public function register(RegisterDto $dto): LoggedInDto
    {
        $user = User::create([
            'email' => $dto->email,
            'name' => $dto->name,
            'password' => Hash::make($dto->password)
        ]);

        $this->categoryRepository->create(
            categoryName: __('text.my_tasks'),
            userId: $user->id
        );

        $accessToken = $user->createToken(self::TOKEN_NAME);

        return new LoggedInDto(
            user: $user,
            accessToken: $accessToken
        );
    }

    /**
     * @param \App\Dtos\Auth\LoginDto $dto
     * @throws WrongCredentialsException
     * @return LoggedInDto
     */
    public function login(LoginDto $dto): LoggedInDto
    {
        $user = $this->userRepository->findByEmail($dto->email);

        throw_if(
            condition: !($user?->password && Hash::check($dto->password, $user->password)),
            exception: WrongCredentialsException::class
        );

        $accessToken = $user->createToken(self::TOKEN_NAME);

        return new LoggedInDto(
            user: $user,
            accessToken: $accessToken
        );
    }

    public function logout(User $user): void
    {
        $user->tokens()->delete();
    }
}
