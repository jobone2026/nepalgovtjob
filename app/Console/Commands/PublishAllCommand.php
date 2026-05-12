<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;

class PublishAllCommand extends Command
{
    protected $signature = 'seo:publish-all';
    protected $description = 'Set is_published=1 for all posts in the database';

    public function handle()
    {
        $count = Post::where('is_published', 0)->count();
        $this->info("Found $count unpublished posts.");
        
        if ($count > 0) {
            Post::where('is_published', 0)->update(['is_published' => 1]);
            $this->info("All posts are now published.");
        }
    }
}
