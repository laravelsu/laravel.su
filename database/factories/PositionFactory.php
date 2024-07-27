<?php

namespace Database\Factories;

use App\Models\Enums\ScheduleEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class PositionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'        => 'Laravel разработчик',
            'description'  => 'Требуется Laravel разработчик ❤️',
            'user_id'      => random_int(1, 10),
            'organization' => fake()->randomElement(['assisted-mindfulness.com', 'Пятёрочка']),
            'salary_min'   => fake()->numberBetween(100, 100000),
            'location'     => fake()->randomElement(['г. Липецк', 'м. Красногвардейская']),
            'schedule'     => fake()->randomElement(ScheduleEnum::cases()),
            'approved'     => random_int(0, 1),
            'contact'      => '@laravel',
        ];
    }
}
