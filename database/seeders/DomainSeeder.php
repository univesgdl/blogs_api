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
        Domain::create(['name' => 'unives-blog']);
        Domain::create(['name' => 'unives-noticias']);
        Domain::create(['name' => 'univesmarket-blog']);
        Domain::create(['name' => 'ihsmex-convenios']);
        Domain::create(['name' => 'ihsmex-blog']);
    }
}
