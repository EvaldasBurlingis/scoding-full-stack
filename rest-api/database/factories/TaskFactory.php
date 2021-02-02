<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->numberBetween(1, 6),
            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(array('to_do', 'in_progress', 'completed')),
        ];
    }
}