<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Factories\SeckillActivityFactory;

class SeckillActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SeckillActivityFactory::new()->count(10)->create();
    }
}