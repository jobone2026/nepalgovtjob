<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SeoUpdateCommand extends Command
{
    protected $signature = 'seo:update-metadata {--apply : Apply changes to database}';
    protected $description = 'Retroactively update Meta Titles and Descriptions to the new high-intent formula';

    public function handle()
    {
        $posts = Post::all();
        $this->info("Scanning " . $posts->count() . " posts...");

        foreach ($posts as $post) {
            $orgAbbr = $post->organization ? explode(' ', $post->organization)[0] : 'Govt';
            $vCount = (int)$post->total_posts;
            $vStr = $vCount > 0 ? " – {$vCount} Posts" : "";
            $yearStr = $post->recruitment_year ?: date('Y');
            
            $lastDateHint = $post->last_date ? $post->last_date->format('d M') : 'Apply Soon';
            
            // Clean post title for meta (remove generic recruitment word if redundant)
            $cleanTitle = str_replace(['Recruitment', 'Vacancy'], '', $post->title);
            $cleanTitle = trim(preg_replace('/\s+/', ' ', $cleanTitle));

            $newTitle = "{$orgAbbr} {$cleanTitle} Recruitment {$yearStr}{$vStr} | {$lastDateHint} | JobOne.in";
            if (strlen($newTitle) > 75) $newTitle = Str::limit($newTitle, 72);

            $eduList = is_array($post->education) ? implode(', ', array_slice($post->education, 0, 2)) : 'Graduate';
            $lastDateFull = $post->last_date ? $post->last_date->format('d-m-Y') : 'soon';
            
            $orgFull = $post->organization ?: 'Recruitment authority';
            $newDesc = "{$orgFull} has released " . ($vCount > 0 ? $vCount : 'various') . " {$post->title} vacancies for {$yearStr}. Eligibility: {$eduList}. Last date to apply: {$lastDateFull}. Check qualification, salary, selection process and direct apply link here.";
            if (strlen($newDesc) > 165) $newDesc = Str::limit($newDesc, 162);

            if ($this->option('apply')) {
                $post->update([
                    'meta_title' => $newTitle,
                    'meta_description' => $newDesc
                ]);
            }
        }

        if (!$this->option('apply')) {
            $this->warn("Dry run complete. Use --apply to save changes.");
        } else {
            $this->info("Successfully updated " . $posts->count() . " posts.");
        }
    }
}
