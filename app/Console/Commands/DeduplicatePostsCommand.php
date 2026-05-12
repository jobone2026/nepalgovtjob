<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeduplicatePostsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seo:deduplicate {--apply : Apply the changes and merge}';
    protected $description = 'Find and merge duplicate job posts based on organization, last date, and vacancy count.';

    public function handle()
    {
        $this->info('Scanning for duplicates...');

        // First, ensure all posts have an organization_slug
        $unslugged = \App\Models\Post::whereNotNull('organization')
            ->whereNull('organization_slug')
            ->get();
        
        if ($unslugged->count() > 0) {
            $this->info("Populating organization_slug for {$unslugged->count()} posts...");
            foreach ($unslugged as $p) {
                $p->organization_slug = \Illuminate\Support\Str::slug($p->organization);
                $p->save();
            }
        }
        
        $allPosts = \App\Models\Post::whereNotNull('organization_slug')
            ->whereNotNull('last_date')
            ->orderBy('last_date', 'desc')
            ->get();

        $groups = [];
        foreach ($allPosts as $post) {
            $key = $post->organization_slug . '_' . $post->total_posts;
            $groups[$key][] = $post;
        }

        $duplicatesFound = 0;

        foreach ($groups as $key => $posts) {
            if (count($posts) < 2) continue;

            // Sort by last_date to check proximity
            usort($posts, fn($a, $b) => $a->last_date <=> $b->last_date);

            for ($i = 0; $i < count($posts) - 1; $i++) {
                if (!$posts[$i]->is_published) continue;

                for ($j = $i + 1; $j < count($posts); $j++) {
                    if (!$posts[$j]->is_published) continue;

                    $diffDays = $posts[$i]->last_date->diffInDays($posts[$j]->last_date);

                    if ($diffDays <= 7) {
                        $this->warn("Duplicate detected: [{$posts[$i]->id}] {$posts[$i]->title} vs [{$posts[$j]->id}] {$posts[$j]->title}");
                        $this->line("  Org: {$posts[$i]->organization} | Date1: {$posts[$i]->last_date->toDateString()} | Date2: {$posts[$j]->last_date->toDateString()} | Vacancy: {$posts[$i]->total_posts}");
                        
                        $duplicatesFound++;

                        if ($this->option('apply')) {
                            $this->merge($posts[$i], $posts[$j]);
                            // If the canonical was $posts[$j], $posts[$i] is unpublished, so we break
                            if (!$posts[$i]->is_published) {
                                break;
                            }
                        }
                    }
                }
            }
        }

        if ($duplicatesFound === 0) {
            $this->info('No duplicates found.');
        } else {
            if (!$this->option('apply')) {
                $this->info("Found {$duplicatesFound} potential duplicate pairs. Run with --apply to merge.");
            } else {
                $this->info("Processed {$duplicatesFound} duplicate pairs.");
            }
        }
    }

    protected function merge($p1, $p2)
    {
        // Canonical is usually the one with more content or more views
        // For this script, we'll pick the one with more views or higher ID as canonical
        $canonical = $p1->view_count >= $p2->view_count ? $p1 : $p2;
        $weaker    = $p1->id === $canonical->id ? $p2 : $p1;

        $this->info("Merging [{$weaker->id}] into canonical [{$canonical->id}]");

        // Merge content if canonical has less content
        if (strlen(strip_tags($canonical->content)) < strlen(strip_tags($weaker->content))) {
            $canonical->content = $weaker->content;
        }

        // Merge other fields if empty
        foreach (['qualifications', 'skills', 'responsibilities', 'meta_title', 'meta_description', 'meta_keywords'] as $field) {
            if (empty($canonical->$field) && !empty($weaker->$field)) {
                $canonical->$field = $weaker->$field;
            }
        }

        $canonical->save();

        // Create redirect
        $oldUrl = "/{$weaker->type}/{$weaker->slug}";
        $newUrl = "/{$canonical->type}/{$canonical->slug}";

        \App\Models\Redirect::updateOrCreate(
            ['old_url' => $oldUrl],
            ['new_url' => $newUrl, 'status_code' => 301]
        );

        // Unpublish weaker post
        $weaker->is_published = false;
        $weaker->save();

        $this->info("  Redirected {$oldUrl} -> {$newUrl}");
    }
}
