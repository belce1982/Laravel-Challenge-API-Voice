<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Question::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $number_value_words = $this->faker->numberBetween(2, 5);
        return [
            'value' => $this->faker->words($number_value_words, true).'?',
            'user_id' => User::inRandomOrder()->first()->id,
        ];
    }
}
