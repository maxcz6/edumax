<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\LogoutRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Realizar login
     * 
     * POST /api/auth/login
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login(
            $request->validated()['email'],
            $request->validated()['password']
        );

        return response()->json($result, $result['success'] ? 200 : 401);
    }

    /**
     * Registrar nuevo usuario
     * 
     * POST /api/auth/register
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->authService->register($request->validated());

        return response()->json($result, $result['success'] ? 201 : 400);
    }

    /**
     * Realizar logout
     * 
     * POST /api/auth/logout
     */
    public function logout(LogoutRequest $request): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado.',
            ], 401);
        }

        $result = $this->authService->logout($user);

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    /**
     * Obtener usuario autenticado
     * 
     * GET /api/auth/me
     */
    public function obtenerMe(): JsonResponse
    {
        $result = $this->authService->obtenerUsuarioAutenticado();

        return response()->json($result, $result['success'] ? 200 : 401);
    }

    /**
     * Renovar token
     * 
     * POST /api/auth/refresh
     */
    public function refresh(): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado.',
            ], 401);
        }

        $result = $this->authService->renovarToken($user);

        return response()->json($result, $result['success'] ? 200 : 400);
    }
}
