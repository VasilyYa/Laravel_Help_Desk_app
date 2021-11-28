<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Issue;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $randomTime = $this->faker->dateTimeBetween('-2 days');
        $randomIssue = Issue::query()->inRandomOrder()->first();

        return [
            'text' => $this->faker->sentence(4), // +/- 40% from words number
            'author_id' => $this->faker->randomElement([
                $randomIssue->client_id,
                $randomIssue->manager_id ?? $randomIssue->client_id
            ]),
            'issue_id' => $randomIssue->id,
            'created_at' => $randomTime,
            'updated_at' => $randomTime,
        ];
    }
}
