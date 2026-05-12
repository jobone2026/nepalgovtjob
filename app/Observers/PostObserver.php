<?php

namespace App\Observers;

use App\Models\Post;
use Illuminate\Support\Facades\Cache;

class PostObserver
{
    /**
     * Handle the Post "created" event.
     */
    public function created(Post $post): void
    {
        if ($post->is_published) {
            $this->clearHomeCache($post);
        }
    }

    /**
     * Handle the Post "updated" event.
     */
    public function updated(Post $post): void
    {
        // Clear cache if published status changed or if it's published
        if ($post->is_published || $post->wasChanged('is_published')) {
            $this->clearHomeCache($post);
        }
    }

    /**
     * Handle the Post "deleted" event.
     */
    public function deleted(Post $post): void
    {
        $this->clearHomeCache($post);
    }

    /**
     * Clear home page cache for the post's state
     */
    private function clearHomeCache(Post $post): void
    {
        // Clear main home cache
        Cache::forget('home_sections');

        // Clear state-specific home cache if post has a state
        if ($post->state_id) {
            Cache::forget("home_sections_state_{$post->state_id}");
        }

        // Also clear the posts list cache for this type
        Cache::forget("posts_{$post->type}");
        Cache::forget("posts_{$post->type}_state_{$post->state_id}");

        \Log::info("Cache cleared for post: {$post->title} (Type: {$post->type})");
    }
}
