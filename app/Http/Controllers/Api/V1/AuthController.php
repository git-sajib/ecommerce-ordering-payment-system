<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected AuthService $authService
    ) {}

    /**
     * Register a new user.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->authService->register(
            $request->validated()
        );

        return $this->success(
            [
                'user' => new UserResource($result['user']),
                'token' => $result['token'],
            ],
            'User registered successfully.',
            201
        );
    }

    /**
     * Login.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login(
            $request->validated()
        );

        return $this->success(
            [
                'user' => new UserResource($result['user']),
                'token' => $result['token'],
            ],
            'Login successful.'
        );
    }

    /**
     * Current user.
     */
    public function me(Request $request): JsonResponse
    {
        return $this->success(
            new UserResource(
                $this->authService->me($request->user())
            )
        );
    }

    /**
     * Logout.
     */
    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout(
            $request->user()
        );

        return $this->success(
            null,
            'Logout successful.'
        );
    }
}
