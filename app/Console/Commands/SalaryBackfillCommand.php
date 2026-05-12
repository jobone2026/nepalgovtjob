<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;

class SalaryBackfillCommand extends Command
{
    protected $signature = 'seo:backfill-salary {--apply : Apply changes}';
    protected $description = 'Extract salary_min and salary_max from existing salary strings';

    public function handle()
    {
        $posts = Post::whereNull('salary_min')->orWhere('salary_min', 0)->get();
        $this->info("Scanning " . $posts->count() . " posts...");

        foreach ($posts as $post) {
            if (empty($post->salary)) continue;

            $s = str_replace(',', '', $post->salary);
            preg_match_all('/\b\d{4,}\b/', $s, $matches); // Look for numbers > 1000
            
            if (!empty($matches[0])) {
                $nums = array_map('intval', $matches[0]);
                sort($nums);
                $min = $nums[0];
                $max = $nums[count($nums) - 1];

                $this->line("Post #{$post->id}: '{$post->salary}' -> Min: $min, Max: $max");

                if ($this->option('apply')) {
                    $post->update([
                        'salary_min' => $min,
                        'salary_max' => $max,
                        'salary_type' => str_contains(strtolower($post->salary), 'stipend') ? 'stipend' : 'salary'
                    ]);
                }
            }
        }

        $this->info("Done.");
    }
}
