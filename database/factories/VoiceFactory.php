<?php

namespace Database\Factories;

use App\Models\Voice;
use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class VoiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Voice::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'value' => $this->faker->boolean(),
            'user_id' => User::inRandomOrder()->first(),
            'question_id' => Question::inRandomOrder()->first(),
        ];
    }
}
