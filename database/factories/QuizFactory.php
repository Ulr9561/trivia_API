<?php

namespace Database\Factories;

use App\Http\Enums\CategoryEnum;
use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class QuizFactory extends Factory
{
    protected $model = Quiz::class;

    public function definition()
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'category' => $this->faker->randomElement([
                CategoryEnum::INFORMATIQUE,
                CategoryEnum::ANIME,
                CategoryEnum::SPORTS,
                CategoryEnum::SCIENCE,
                CategoryEnum::ARTS,
                CategoryEnum::GAMING,
                CategoryEnum::GENERAL,
                CategoryEnum::DEVINETTES
            ]),
            'level' => $this->faker->randomElement([])
        ];
    }
}
