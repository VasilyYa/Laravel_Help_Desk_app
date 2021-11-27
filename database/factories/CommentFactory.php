<?php

namespace Database\Factories;

use App\Models\Request;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $randomTime = $this->faker->dateTimeBetween('-2 days');
        $request = Request::query()->inRandomOrder()->first();
        return [
            'text' => $this->faker->sentence(4), // +/- 40% from 4 words
            'author_id' => $this->faker->randomElement([
                $request->client_id,
                $request->manager_id ?? $request->client_id
            ]),
            'request_id' => $request->id,
            'created_at' => $randomTime,
            'updated_at' => $randomTime,
        ];
    }
}
