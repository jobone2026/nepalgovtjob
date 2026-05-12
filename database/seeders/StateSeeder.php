<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Nepal provinces as per the new federal structure.
     */
    public function run(): void
    {
        $provinces = [
            ['name' => 'Koshi Province', 'slug' => 'koshi-province'],
            ['name' => 'Madhesh Province', 'slug' => 'madhesh-province'],
            ['name' => 'Bagmati Province', 'slug' => 'bagmati-province'],
            ['name' => 'Gandaki Province', 'slug' => 'gandaki-province'],
            ['name' => 'Lumbini Province', 'slug' => 'lumbini-province'],
            ['name' => 'Karnali Province', 'slug' => 'karnali-province'],
            ['name' => 'Sudurpashchim Province', 'slug' => 'sudurpashchim-province'],
            ['name' => 'All Nepal', 'slug' => 'all-nepal'],
        ];

        foreach ($provinces as $province) {
            \App\Models\State::create($province);
        }
    }
}