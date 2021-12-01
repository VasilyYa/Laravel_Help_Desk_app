<?php

namespace Database\Factories;

use App\Models\Issue;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class IssueFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Issue::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $randomTime = $this->faker->dateTimeInInterval('-10 days', '+7 days');

        return [
            'subject' => $this->faker->sentence(8), // +/- 40% from words number
            'detail' => $this->faker->text(500),
            'status_id' => 1,
            'client_id' => User::all()->where('role_id', 1)->random()->id,
            'manager_id' => $this->faker->randomElement([
                User::all()->where('role_id', 2)->random()->id,
                null //when manager is not attached yet
            ]),
            'created_at' => $randomTime,
            'updated_at' => $randomTime,
        ];
    }
}
