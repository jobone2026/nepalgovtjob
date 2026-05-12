<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Nepal-specific categories for Lok Sewa (government jobs).
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Lok Sewa Aayog', 'slug' => 'lok-sewa-aayog', 'icon' => 'briefcase', 'color' => '#dc2626'],
            ['name' => 'Nepal Police', 'slug' => 'nepal-police', 'icon' => 'shield-alt', 'color' => '#1e40af'],
            ['name' => 'Nepal Army', 'slug' => 'nepal-army', 'icon' => 'star', 'color' => '#059669'],
            ['name' => 'Teaching Service', 'slug' => 'teaching-service', 'icon' => 'graduation-cap', 'color' => '#7c3aed'],
            ['name' => 'Health Service', 'slug' => 'health-service', 'icon' => 'heartbeat', 'color' => '#dc2626'],
            ['name' => 'Engineering Service', 'slug' => 'engineering-service', 'icon' => 'cogs', 'color' => '#0891b2'],
            ['name' => 'Banking Jobs', 'slug' => 'banking-jobs', 'icon' => 'university', 'color' => '#4f46e5'],
            ['name' => 'Local Level', 'slug' => 'local-level', 'icon' => 'map-marker-alt', 'color' => '#f59e0b'],
            ['name' => 'Admit Card', 'slug' => 'admit-card', 'icon' => 'id-card-alt', 'color' => '#8b5cf6'],
            ['name' => 'Results', 'slug' => 'results', 'icon' => 'chart-bar', 'color' => '#10b981'],
            ['name' => 'Answer Key', 'slug' => 'answer-key', 'icon' => 'key', 'color' => '#f97316'],
            ['name' => 'Syllabus', 'slug' => 'syllabus', 'icon' => 'book-open', 'color' => '#6366f1'],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::create($category);
        }
    }
}