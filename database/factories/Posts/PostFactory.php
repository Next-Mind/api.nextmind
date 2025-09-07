<?php

namespace Database\Factories\Posts;

use App\Models\Posts\PostCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Posts\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "title"=> $this->faker->sentence(7),
            "subtitle" => $this->faker->sentence(3),
            "body" => $this->faker->paragraph(5),
            "language" => $this->faker->randomElement(['pt-BR','en-US','es-419']),
            "like_count" => $this->faker->numberBetween(0,2500),
            'image_url' => "https://picsum.photos/seed/{$this->faker->word()}/200",
            "reading_time" => $this->faker->numberBetween(2,10),
            "visibility" => $this->faker->randomElement(['public','private']),
            "post_category_id" => PostCategory::all()->random()->id
        ];
    }
}
