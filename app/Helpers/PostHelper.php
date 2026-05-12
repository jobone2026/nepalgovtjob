<?php

namespace App\Helpers;

use App\Models\Post;
use Carbon\Carbon;

class PostHelper
{
    /**
     * Strip leading emoji / symbol prefixes that scrapers inject into titles.
     * e.g. "🔥 SSC CGL Result 2025 ➡️" → "SSC CGL Result 2025"
     */
    public static function cleanTitle(string $title): string
    {
        // Remove leading emoji, symbols, punctuation and whitespace
        $cleaned = preg_replace(
            '/^[\s\p{So}\p{Sm}\p{Po}\p{Pd}\p{Ps}\p{Pe}\p{Pi}\p{Pf}\x{2000}-\x{206F}\x{2600}-\x{27BF}\x{1F000}-\x{1FFFF}\x{FE00}-\x{FEFF}]+/u',
            '',
            $title
        );
        // Also strip trailing arrows / symbols like ➡️ 👇
        $cleaned = preg_replace(
            '/[\s\p{So}\p{Sm}\x{2190}-\x{21FF}\x{2600}-\x{27BF}\x{1F000}-\x{1FFFF}]+$/u',
            '',
            $cleaned
        );
        return trim($cleaned) ?: $title; // fallback to original if empty
    }

    /**
     * Generate attention-grabbing badge array for a post — like sarkariresult.com
     * Returns array of ['label'=>'...', 'icon'=>'...', 'bg'=>'...', 'color'=>'...', 'border'=>'...']
     */
    public static function getAttentionBadges(Post $post): array
    {
        $badges = [];
        $now = Carbon::now();

        // 1. TODAY deadline — highest urgency
        if ($post->last_date) {
            $daysLeft = $now->startOfDay()->diffInDays($post->last_date->startOfDay(), false);
            if ($daysLeft === 0) {
                $badges[] = ['label' => '🔥🔥 Last Date Today', 'icon' => '', 'bg' => '#fee2e2', 'color' => '#b91c1c', 'border' => '#fca5a5'];
            } elseif ($daysLeft > 0 && $daysLeft <= 3) {
                $badges[] = ['label' => "🔥 Last {$daysLeft} Days", 'icon' => '', 'bg' => '#fff7ed', 'color' => '#c2410c', 'border' => '#fdba74'];
            }
        }

        // 2. Date Extended
        if ($post->is_date_extended) {
            $badges[] = ['label' => '🔥 Date Extended', 'icon' => '', 'bg' => '#fff1f2', 'color' => '#be123c', 'border' => '#fecdd3'];
        }

        // 3. Result Out
        if ($post->type === 'result') {
            if ($post->final_result || ($post->tags && in_array('final_result', $post->tags))) {
                $badges[] = ['label' => '🎉 Result Out', 'icon' => '', 'bg' => '#fef9c3', 'color' => '#854d0e', 'border' => '#fde047'];
            } else {
                $badges[] = ['label' => '🏆 Result', 'icon' => '', 'bg' => '#fff7ed', 'color' => '#c2410c', 'border' => '#fdba74'];
            }
        }

        // 4. New post (within 3 days of creation)
        if ($post->created_at && $post->created_at->diffInDays($now) <= 3) {
            $badges[] = ['label' => '✅ New', 'icon' => '', 'bg' => '#dcfce7', 'color' => '#15803d', 'border' => '#86efac'];
        }

        // 5. New Update (updated recently but not brand new)
        if ($post->updated_at && $post->updated_at->diffInDays($now) <= 2
            && $post->created_at && $post->created_at->diffInDays($now) > 3) {
            $badges[] = ['label' => '✅ New Update', 'icon' => '', 'bg' => '#e0f2fe', 'color' => '#0369a1', 'border' => '#bae6fd'];
        }

        // 6. Upcoming
        if ($post->is_upcoming) {
            $badges[] = ['label' => '⏳ Upcoming', 'icon' => '', 'bg' => '#fff7ed', 'color' => '#c2410c', 'border' => '#fed7aa'];
        }

        // 7. Admit Card Available
        if ($post->type === 'admit_card') {
            $badges[] = ['label' => '🎟️ Admit Card', 'icon' => '', 'bg' => '#faf5ff', 'color' => '#7e22ce', 'border' => '#e9d5ff'];
        }

        // 8. Answer Key Available
        if ($post->type === 'answer_key') {
            $badges[] = ['label' => '🔑 Answer Key', 'icon' => '', 'bg' => '#fefce8', 'color' => '#854d0e', 'border' => '#fde68a'];
        }

        return $badges;
    }
}
