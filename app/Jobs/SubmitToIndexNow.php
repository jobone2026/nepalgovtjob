<?php

namespace App\Jobs;

use App\Services\IndexNowService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SubmitToIndexNow implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [60, 300, 900]; // 1 min, 5 min, 15 min

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string|array $urls
    ) {}

    /**
     * Execute the job.
     */
    public function handle(IndexNowService $indexNowService): void
    {
        if (is_array($this->urls)) {
            $indexNowService->submitUrls($this->urls);
        } else {
            $indexNowService->submitUrl($this->urls);
        }
    }
}
