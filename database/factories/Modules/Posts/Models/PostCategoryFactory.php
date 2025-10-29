<?php

namespace Database\Factories\Modules\Posts\Models;

use Illuminate\Support\Str;
use App\Modules\Posts\Models\PostCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\Posts\Models\PostCategory>
 */
class PostCategoryFactory extends Factory
{

    protected $model = PostCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->words(rand(1, 3), true);
        return [
            'name' => $name,
            'slug' => Str::slug($name),
        ];
    }
}
