<?php

namespace Database\Factories;

use App\Models\Anuncio;
use App\Models\Institucion;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnuncioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'institucion_id' => Institucion::factory(),
            'user_id' => User::factory(),
            'titulo' => fake()->sentence(),
            'contenido' => fake()->paragraph(),
            'publicado' => true,
        ];
    }

    /**
     * Anuncio no publicado
     */
    public function noPublicado(): static
    {
        return $this->state(fn (array $attributes) => [
            'publicado' => false,
        ]);
    }
}
