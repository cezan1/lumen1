<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 先创建 5 个一级分类
        $rootCategories = Category::factory()->count(5)->create();
        
        // 为每个一级分类创建 2 个二级分类
        $rootCategories->each(function ($category) {
            Category::factory()->count(2)->create(['parent_id' => $category->id]);
        });
        
        // 为每个二级分类创建 2 个三级分类
        $secondLevelCategories = Category::whereIn('parent_id', $rootCategories->pluck('id'))->get();
        $secondLevelCategories->each(function ($category) {
            Category::factory()->count(2)->create(['parent_id' => $category->id]);
        });
    }
}