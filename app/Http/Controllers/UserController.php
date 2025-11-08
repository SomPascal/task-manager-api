<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function me(): JsonResponse
    {
        $user = auth('sanctum')->user();

        return $this->success(
            message: 'User successfuly retrieved',
            data: new UserResource($user)
        );
    }
}
