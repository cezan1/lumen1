<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = \Faker\Factory::create('zh_CN');
        $parentCategories = Category::where('parent_id', 0)->pluck('id')->toArray();
        
        // 80% 概率为一级分类，20% 概率为二三级分类
        if (count($parentCategories) > 0 && $faker->boolean(20)) {
            return [
                'name' => $faker->word,
                'description' => $faker->sentence,
                'parent_id' => $faker->randomElement($parentCategories)
            ];
        }
        
        return [
            'name' => $faker->word,
            'description' => $faker->sentence,
            'parent_id' => 0
        ];
    }
}