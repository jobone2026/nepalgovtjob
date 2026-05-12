<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;

class RestorePostContent extends Command
{
    protected $signature = 'restore:post-content {slug : Post slug to restore}';
    protected $description = 'Restore post content from HTML file';

    public function handle()
    {
        $slug = $this->argument('slug');
        $post = Post::where('slug', $slug)->first();

        if (!$post) {
            $this->error("Post with slug '{$slug}' not found");
            return 1;
        }

        // Map slugs to content files
        $contentFiles = [
            'punjab-anganwadi-recruitment-2026-apply-94-posts' => 'punjab-anganwadi-content.html',
        ];

        if (!isset($contentFiles[$slug])) {
            $this->error("No restore content defined for slug: {$slug}");
            return 1;
        }

        $filePath = storage_path('app/' . $contentFiles[$slug]);
        
        if (!file_exists($filePath)) {
            $this->error("Content file not found: {$filePath}");
            return 1;
        }

        $content = file_get_contents($filePath);
        
        // Extract body content
        if (preg_match('/<body[^>]*>(.*)<\/body>/is', $content, $matches)) {
            $content = $matches[1];
        }

        // Remove DOCTYPE and html/head tags
        $content = preg_replace('/<\?xml[^>]*\?>/i', '', $content);
        $content = preg_replace('/<!DOCTYPE[^>]*>/i', '', $content);
        $content = preg_replace('/<html[^>]*>/i', '', $content);
        $content = preg_replace('/<\/html>/i', '', $content);
        $content = preg_replace('/<head[^>]*>.*?<\/head>/is', '', $content);

        $content = trim($content);

        $post->update(['content' => $content]);
        $this->info("✅ Restored: {$post->title}");
        $this->info("Content length: " . strlen($content) . " bytes");
        
        return 0;
    }
}

