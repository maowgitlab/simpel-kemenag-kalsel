<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class MediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = "Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quos labore quam porro -" . fake()->unique()->numberBetween(1, 10000);
        return [
            'user_id' => \App\Models\User::inRandomOrder()->first(), 
            'category_id' => \App\Models\Category::inRandomOrder()->first(), 
            'judul' => $title,
            'slug' => str()->slug($title),
            'konten' => fake()->paragraph(100),
            'pilihan' => fake()->numberBetween(0, 1),
            'jumlah_dibaca' => fake()->numberBetween(0, 1000),
            'created_at' => fake()->unique()->dateTimeBetween('-7 days', 'now'),
        ];
    }
}
