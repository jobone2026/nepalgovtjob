<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Facades\Cache;

class CacheInvalidationService
{
    public function invalidatePost(Post $post): void
    {
        // Clear post detail page
        $this->clearUrl("/{$post->type}/{$post->slug}");

        // Clear homepage
        $this->clearUrl('/');

        // Clear post type listing
        $typeRoute = $this->getTypeRoute($post->type);
        $this->clearUrl($typeRoute);

        // Clear category page
        if ($post->category) {
            $this->clearUrl("/category/{$post->category->slug}");
        }

        // Clear state page
        if ($post->state) {
            $this->clearUrl("/state/{$post->state->slug}");
        }

        // Clear paginated pages (first 5 pages)
        for ($i = 1; $i <= 5; $i++) {
            $this->clearUrl("{$typeRoute}?page={$i}");
        }
    }

    // Alias method for backward compatibility
    public function invalidatePostCache(Post $post): void
    {
        $this->invalidatePost($post);
    }

    private function clearUrl(string $path): void
    {
        $fullUrl = url($path);
        $cacheKey = 'page_cache:' . md5($fullUrl);
        Cache::forget($cacheKey);
    }

    private function getTypeRoute(string $type): string
    {
        return match($type) {
            'job' => '/jobs',
            'admit_card' => '/admit-cards',
            'result' => '/results',
            'answer_key' => '/answer-keys',
            'syllabus' => '/syllabus',
            'blog' => '/blogs',
            default => '/'
        };
    }

    public function clearAll(): void
    {
        Cache::flush();
    }
}
