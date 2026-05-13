<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * Realizar login y generar token
     * 
     * @param string $email
     * @param string $password
     * @return array
     */
    public function login(string $email, string $password): array
    {
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return [
                'success' => false,
                'message' => 'Las credenciales proporcionadas son incorrectas.',
            ];
        }

        if ($user->estado !== 'activo') {
            return [
                'success' => false,
                'message' => 'La cuenta está inactiva o suspendida.',
            ];
        }

        // Actualizar último acceso
        $user->update(['ultimo_acceso' => now()]);

        // Generar token Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'success' => true,
            'message' => 'Login exitoso.',
            'data' => [
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'nombres' => $user->nombres,
                    'apellidos' => $user->apellidos,
                    'email' => $user->email,
                    'telefono' => $user->telefono,
                    'foto_perfil' => $user->foto_perfil,
                    'institucion_id' => $user->institucion_id,
                    'roles' => $user->getRoleNames(),
                ],
            ],
        ];
    }

    /**
     * Registrar nuevo usuario
     * 
     * @param array $data
     * @return array
     */
    public function register(array $data): array
    {
        try {
            $user = User::create([
                'institucion_id' => $data['institucion_id'],
                'nombres' => $data['nombres'],
                'apellidos' => $data['apellidos'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'telefono' => $data['telefono'] ?? null,
                'estado' => 'activo',
            ]);

            // Generar token Sanctum
            $token = $user->createToken('auth_token')->plainTextToken;

            return [
                'success' => true,
                'message' => 'Usuario registrado exitosamente.',
                'data' => [
                    'token' => $token,
                    'user' => [
                        'id' => $user->id,
                        'nombres' => $user->nombres,
                        'apellidos' => $user->apellidos,
                        'email' => $user->email,
                        'telefono' => $user->telefono,
                        'institucion_id' => $user->institucion_id,
                    ],
                ],
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error al registrar el usuario: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Realizar logout y revocar tokens
     * 
     * @param User $user
     * @return array
     */
    public function logout(User $user): array
    {
        try {
            // Revocar todos los tokens del usuario
            $user->tokens()->delete();

            return [
                'success' => true,
                'message' => 'Logout exitoso.',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error al realizar logout: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Obtener usuario autenticado
     * 
     * @return array
     */
    public function obtenerUsuarioAutenticado(): array
    {
        $user = Auth::user();

        if (!$user) {
            return [
                'success' => false,
                'message' => 'Usuario no autenticado.',
            ];
        }

        return [
            'success' => true,
            'data' => [
                'id' => $user->id,
                'nombres' => $user->nombres,
                'apellidos' => $user->apellidos,
                'email' => $user->email,
                'telefono' => $user->telefono,
                'foto_perfil' => $user->foto_perfil,
                'institucion_id' => $user->institucion_id,
                'estado' => $user->estado,
                'roles' => $user->getRoleNames(),
            ],
        ];
    }

    /**
     * Renovar token
     * 
     * @param User $user
     * @return array
     */
    public function renovarToken(User $user): array
    {
        try {
            // Crear nuevo token
            $token = $user->createToken('auth_token')->plainTextToken;

            return [
                'success' => true,
                'message' => 'Token renovado exitosamente.',
                'data' => [
                    'token' => $token,
                ],
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error al renovar el token: ' . $e->getMessage(),
            ];
        }
    }
}
