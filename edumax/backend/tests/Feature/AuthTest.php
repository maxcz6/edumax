<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Institucion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_correct_credentials(): void
    {
        $institucion = Institucion::factory()->create();
        $user = User::factory()->create([
            'email' => 'test@edumax.com',
            'password' => bcrypt('password'),
            'institucion_id' => $institucion->id,
            'estado' => 'activo',
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@edumax.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => ['token', 'user']
                 ]);
    }

    public function test_user_cannot_login_with_incorrect_password(): void
    {
        $user = User::factory()->create([
            'email' => 'test@edumax.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@edumax.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401);
    }
}
