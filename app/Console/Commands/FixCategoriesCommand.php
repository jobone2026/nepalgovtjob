<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Support\Str;

class FixCategoriesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seo:fix-categories {--apply : Apply the suggested changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates missing categories and reclassifies posts from State Govt to correct categories based on keywords.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $missingCategories = [
            'Central PSU',
            'Central Government',
            'Central University',
            'Armed Forces',
            'Paramilitary',
            'Apprentice/Trainee',
        ];

        $this->info("Ensuring missing categories exist...");
        $categoryMap = [];
        foreach ($missingCategories as $catName) {
            $slug = Str::slug($catName);
            $category = Category::firstOrCreate(
                ['name' => $catName],
                [
                    'slug' => $slug,
                    'icon' => 'fas fa-building',
                    'color' => '#3b82f6', // blue
                ]
            );
            $categoryMap[$catName] = $category->id;
            $this->line(" - $catName (ID: {$category->id})");
        }

        // We also need the ID for 'State Govt' to find posts that need reclassifying.
        $stateGovtCategory = Category::where('slug', 'state-govt')->orWhere('name', 'like', '%State Govt%')->first();
        
        if (!$stateGovtCategory) {
            $this->error("Could not find 'State Govt' category. Aborting reclassification.");
            return;
        }

        $this->info("Scanning posts currently in 'State Govt' (ID: {$stateGovtCategory->id})...");

        $posts = Post::where('category_id', $stateGovtCategory->id)->get();
        $this->info("Found {$posts->count()} posts to scan.");

        $updates = [];
        $counts = array_fill_keys($missingCategories, 0);

        foreach ($posts as $post) {
            $searchString = strtolower($post->title . ' ' . $post->organization . ' ' . $post->organisation_full);
            
            $suggestedCategory = null;

            if (Str::contains($searchString, ['apprentice', 'trainee', 'apprenticeship'])) {
                $suggestedCategory = 'Apprentice/Trainee';
            } elseif (Str::contains($searchString, ['university', 'iit', 'nit', 'iim', 'jnu', 'ignou', 'du ', 'delhi university'])) {
                // If it's central university vs state university, we approximate. The prompt asks for Central University.
                $suggestedCategory = 'Central University';
            } elseif (Str::contains($searchString, ['army', 'navy', 'air force', 'indian army', 'indian navy', 'iaf', 'cds', 'nda'])) {
                $suggestedCategory = 'Armed Forces';
            } elseif (Str::contains($searchString, ['crpf', 'bsf', 'cisf', 'itbp', 'ssb', 'assam rifles', 'paramilitary', 'ssc gd'])) {
                $suggestedCategory = 'Paramilitary';
            } elseif (Str::contains($searchString, ['bhel', 'ntpc', 'ongc', 'sail', 'gail', 'hpcl', 'iocl', 'bpcl', 'psu', 'cil', 'coal india', 'npcil', 'iffco', 'alimco', 'nhpc', 'bel ', 'hal ', 'oil india', 'pfc', 'rec ', 'powergrid'])) {
                $suggestedCategory = 'Central PSU';
            } elseif (Str::contains($searchString, ['upsc', 'ssc', 'rrb', 'railway', 'ministry', 'department of', 'isro', 'drdo'])) {
                $suggestedCategory = 'Central Government';
            }

            if ($suggestedCategory) {
                $updates[] = [
                    'post_id' => $post->id,
                    'title' => $post->title,
                    'old_cat' => $stateGovtCategory->name,
                    'new_cat' => $suggestedCategory,
                    'new_cat_id' => $categoryMap[$suggestedCategory]
                ];
                $counts[$suggestedCategory]++;
            }
        }

        $this->info("Reclassification suggestions:");
        foreach ($counts as $cat => $count) {
            $this->line(" - $cat: $count posts");
        }

        if ($this->option('apply')) {
            $this->info("Applying changes...");
            $bar = $this->output->createProgressBar(count($updates));
            $bar->start();

            foreach ($updates as $update) {
                Post::where('id', $update['post_id'])->update(['category_id' => $update['new_cat_id']]);
                $bar->advance();
            }

            $bar->finish();
            $this->newLine();
            $this->info("Successfully updated " . count($updates) . " posts.");
        } else {
            $this->info("Run with --apply to execute these changes.");
        }
    }
}
