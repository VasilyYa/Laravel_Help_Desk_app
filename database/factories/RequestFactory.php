<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'description' => $this->faker->sentence(),
            'status_id' => 1,
            'client_id' => User::all()->where('role_id', 1)->random()->id,
            'manager_id' => $this->faker->randomElement([
                User::all()->where('role_id', 2)->random()->id,
                null //null if manager is not attached yet
            ]),
        ];
    }
}
