<?php

namespace Database\Seeders;

use App\Models\Domain;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DomainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Domain::create(['name' => 'blog.unives.mx']);
        Domain::create(['name' => 'unives.mx-noticias']);
        Domain::create(['name' => 'blog.univesmarket.com']);
        Domain::create(['name' => 'ihsmex.com-noticias']);
        Domain::create(['name' => 'ihsmex.com-blog']);
    }
}
