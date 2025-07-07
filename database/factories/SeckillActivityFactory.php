<?php

namespace Database\Factories;

use App\Models\SeckillActivity;
use Illuminate\Database\Eloquent\Factories\Factory;

class SeckillActivityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SeckillActivity::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence,
            'user_ids' => '',
            'product_id' => 0,
            'product_quantity' => 0,
            'start_time' => $this->faker->dateTimeBetween('now', '+1 week'),
            'end_time' => $this->faker->dateTimeBetween('+1 week', '+2 weeks'),
            'status' => $this->faker->randomElement([0, 1]),
        ];
    }
}