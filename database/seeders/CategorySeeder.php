<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create(['slug' => 'uncategorized', 'name' => 'Uncategorized']);
        Category::create(['slug' => 'technology', 'name' => 'Technology']);
        Category::create(['slug' => 'programming', 'name' => 'Programming']);
        Category::create(['slug' => 'lifestyle', 'name' => 'Lifestyle']);
        Category::create(['slug' => 'travel', 'name' => 'Travel']);
        Category::create(['slug' => 'food', 'name' => 'Food']);
    }
}
