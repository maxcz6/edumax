<?php

namespace Database\Factories;

use App\Models\Notificacion;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificacionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'titulo' => fake()->sentence(),
            'mensaje' => fake()->paragraph(),
            'tipo' => fake()->randomElement(['sistema', 'whatsapp', 'sms', 'correo']),
            'leido' => false,
            'fecha_envio' => fake()->dateTime(),
        ];
    }

    /**
     * Notificación leída
     */
    public function leida(): static
    {
        return $this->state(fn (array $attributes) => [
            'leido' => true,
        ]);
    }
}
