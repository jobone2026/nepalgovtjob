<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = \App\Models\Admin::first();
        $categories = \App\Models\Category::all();
        $states = \App\Models\State::all();

        $posts = [];

        // Generate 50 Jobs
        for ($i = 1; $i <= 50; $i++) {
            $posts[] = [
                'type' => 'job',
                'title' => "Job Recruitment $i - Government Position",
                'short_description' => "Government job opening $i with attractive salary and benefits",
                'content' => "This is a government job opportunity $i. Apply now to secure your future.",
                'total_posts' => rand(100, 5000),
                'last_date' => now()->addDays(rand(10, 60))
            ];
        }

        // Generate 50 Admit Cards
        for ($i = 1; $i <= 50; $i++) {
            $posts[] = [
                'type' => 'admit_card',
                'title' => "Admit Card Released $i - Exam Notification",
                'short_description' => "Admit card for exam $i is now available for download",
                'content' => "Download your admit card for exam $i from the official website.",
                'notification_date' => now()->subDays(rand(0, 30))
            ];
        }

        // Generate 50 Results
        for ($i = 1; $i <= 50; $i++) {
            $posts[] = [
                'type' => 'result',
                'title' => "Result Announced $i - Exam Results",
                'short_description' => "Results for exam $i have been declared",
                'content' => "Check your results for exam $i on the official portal.",
                'notification_date' => now()->subDays(rand(0, 30))
            ];
        }

        // Generate 50 Answer Keys
        for ($i = 1; $i <= 50; $i++) {
            $posts[] = [
                'type' => 'answer_key',
                'title' => "Answer Key Released $i - Official Keys",
                'short_description' => "Official answer key for exam $i",
                'content' => "Download the official answer key for exam $i and raise objections if needed.",
                'notification_date' => now()->subDays(rand(0, 30))
            ];
        }

        // Generate 50 Syllabus
        for ($i = 1; $i <= 50; $i++) {
            $posts[] = [
                'type' => 'syllabus',
                'title' => "Syllabus $i - Complete Exam Pattern",
                'short_description' => "Complete syllabus for exam $i",
                'content' => "Detailed syllabus and exam pattern for exam $i.",
                'notification_date' => now()->subDays(rand(0, 30))
            ];
        }

        // Generate 50 Blogs
        for ($i = 1; $i <= 50; $i++) {
            $posts[] = [
                'type' => 'blog',
                'title' => "Blog Post $i - Exam Preparation Tips",
                'short_description' => "Tips and strategies for exam preparation $i",
                'content' => "Comprehensive guide for exam preparation with useful tips and strategies.",
                'notification_date' => now()->subDays(rand(0, 30))
            ];
        }

        foreach ($posts as $postData) {
            $postData['slug'] = \Illuminate\Support\Str::slug($postData['title'] . '-' . uniqid());
            $postData['category_id'] = $categories->random()->id;
            $postData['state_id'] = $states->random()->id;
            $postData['admin_id'] = $admin->id;
            $postData['is_published'] = true;
            $postData['is_featured'] = rand(0, 1);
            $postData['meta_title'] = substr($postData['title'], 0, 60);
            $postData['meta_description'] = substr($postData['short_description'], 0, 160);

            \App\Models\Post::create($postData);
        }
    }
}

