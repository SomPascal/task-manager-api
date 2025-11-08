<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

trait ApiResponse
{
    public function success(string $message, array | JsonResource $data = [], int $status = 200, array $headers = []): JsonResponse
    {
        return response()->json(data: [
            'success' => true,
            'data' => $data,
            'status' => $status,
            'message' => $message
        ], status: $status, headers: $headers);
    }

    public function fail(string $message, int $status, ?string $code = null, array $errors = [], array $headers = []): JsonResponse
    {
        return response()->json(data: [
            'success' => false,
            'status' => $status,
            'code' => $code,
            'errors' => $errors,
            'message' => $message
        ], status: $status, headers: $headers);
    }

    public function failServerError(?string $message = null, array $headers = []): JsonResponse
    {
        return response()->json(data: [
            'success' => false,
            'status' => 500,
            'message' => $message ?? 'Server Error'
        ], status: 500, headers: $headers);
    }
}
