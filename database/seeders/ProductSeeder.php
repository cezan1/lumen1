<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Generator as Faker;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create('zh_CN');
        // 只获取二三级分类
        $subCategories = Category::where('parent_id', '!=', 0)->pluck('id')->toArray();

        Product::factory()->count(20)->make()->each(function ($product) use ($faker, $subCategories) {
            $product->category_id = $faker->randomElement($subCategories);
            $product->save();
        });
    }
}