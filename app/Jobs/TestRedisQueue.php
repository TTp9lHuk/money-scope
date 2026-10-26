<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class TestRedisQueue implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $message = 'Привет из Redis Queue!',
    ) {}

    public function handle(): void
    {
        $result = [
            'message' => $this->message,
            'executed_at' => now()->toDateTimeString(),
            'status' => 'success',
        ];

        Log::info('TestRedisQueue result', $result);
    }
}
