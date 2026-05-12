<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $roles = ['Administrador', 'Director', 'Docente', 'Estudiante', 'Padre'];
        
        return [
            'nombre' => fake()->unique()->randomElement($roles),
            'descripcion' => fake()->sentence(),
        ];
    }
}
