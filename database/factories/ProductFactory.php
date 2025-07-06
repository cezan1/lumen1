<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = \Faker\Factory::create('zh_CN');
        $categories = Category::pluck('id')->toArray();
        return [
            'name' => $faker->words(3, true),
            'description' => $faker->paragraph,
            'price' => $faker->randomFloat(2, 10, 1000),
            'stock' => $faker->numberBetween(10, 1000),
            'image' => $faker->imageUrl(),
            'status' => $faker->randomElement([1, 2]),
            'category_id' => $faker->randomElement($categories)
        ];
    }
}