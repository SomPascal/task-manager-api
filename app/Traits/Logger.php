<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;

trait Logger
{
    protected string $channel = 'daily';

    protected function logger(): LoggerInterface
    {
        return Log::channel($this->channel);
    }

    protected function logException(string $message, \Throwable $th, array $context = [])
    {
        $this->logger()->error(
            message: $message,
            context: [
                ...$context,
                [
                    'message' => $th->getMessage(),
                    'trace' => $th->getTraceAsString(),
                    'class' => $th::class,
                    'user_email' => auth('sanctum')->user()?->email
                ]
            ]
        );
    }
}
