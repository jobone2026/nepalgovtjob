<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;

class FixBlogContent extends Command
{
    protected $signature = 'fix:blog-content {--type=all : Post type to fix (all, blog, job, etc.)}';
    protected $description = 'Fix posts with full HTML document structure';

    public function handle()
    {
        $type = $this->option('type');
        $this->info('🔧 Fixing post content...');

        $query = Post::query();

        if ($type !== 'all') {
            $query->where('type', $type);
        }

        $posts = $query->where(function ($q) {
            $q->where('content', 'like', '<!DOCTYPE%')
              ->orWhere('content', 'like', '<html%')
              ->orWhere('content', 'like', '%<style%')
              ->orWhere('content', 'like', '%<script%');
        })->get();

        if ($posts->isEmpty()) {
            $this->info('✅ No posts need fixing');
            return 0;
        }

        $this->warn("Found {$posts->count()} posts to fix");

        foreach ($posts as $post) {
            $content = $post->content;

            // Extract content from body tag
            if (preg_match('/<body[^>]*>(.*)<\/body>/is', $content, $matches)) {
                $content = $matches[1];
                $this->line("  ✓ Extracted body content from: {$post->title}");
            }

            // Remove DOCTYPE and html/head tags ONLY
            $content = preg_replace('/<\?xml[^>]*\?>/i', '', $content);
            $content = preg_replace('/<!DOCTYPE[^>]*>/i', '', $content);
            $content = preg_replace('/<html[^>]*>/i', '', $content);
            $content = preg_replace('/<\/html>/i', '', $content);
            $content = preg_replace('/<head[^>]*>.*?<\/head>/is', '', $content);
            
            // DO NOT remove style or script tags - they contain the styling for the content!

            // Clean up extra whitespace
            $content = trim($content);

            // Update the post
            $post->update(['content' => $content]);
            $this->line("  ✅ Fixed: {$post->title} (Type: {$post->type})");
        }

        $this->info('✅ All posts fixed successfully!');
        return 0;
    }
}
