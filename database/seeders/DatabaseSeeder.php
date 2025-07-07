<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\SeckillActivitySeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CategorySeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(SeckillActivitySeeder::class);
    }
}
