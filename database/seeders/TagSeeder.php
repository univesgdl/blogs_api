<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tag::create(['slug' => 'inteligencia-artificial', 'name' => 'Inteligencia Artificial']);
        Tag::create(['slug' => 'educacion', 'name' => 'Educación']);
        Tag::create(['slug' => 'marketing', 'name' => 'Marketing']);
        Tag::create(['slug' => 'pedagogia', 'name' => 'Pedagogía']);
        Tag::create(['slug' => 'finanzas', 'name' => 'Finanzas']);
        Tag::create(['slug' => 'software', 'name' => 'Software']);
    }
}
