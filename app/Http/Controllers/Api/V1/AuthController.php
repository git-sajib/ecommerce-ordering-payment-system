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
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Authentication',
    description: 'Authentication APIs'
)]
class AuthController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected AuthService $authService
    ) {}

    /**
     * --------------------------------------------------------------------------
     * Register
     * --------------------------------------------------------------------------
     * Register a new user and return an access token.
     */
    #[OA\Post(
        path: '/api/v1/auth/register',
        summary: 'Register a new user',
        tags: ['Authentication']
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['name', 'email', 'password', 'password_confirmation'],
            properties: [
                new OA\Property(
                    property: 'name',
                    type: 'string',
                    example: 'Assessment User'
                ),
                new OA\Property(
                    property: 'email',
                    type: 'string',
                    format: 'email',
                    example: 'assessment@example.com'
                ),
                new OA\Property(
                    property: 'password',
                    type: 'string',
                    format: 'password',
                    example: 'password123'
                ),
                new OA\Property(
                    property: 'password_confirmation',
                    type: 'string',
                    format: 'password',
                    example: 'password123'
                ),
            ]
        )
    )]
    #[OA\Response(
        response: 201,
        description: 'User registered successfully'
    )]
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
     * --------------------------------------------------------------------------
     * Login
     * --------------------------------------------------------------------------
     * Authenticate a user and return an access token.
     */
    #[OA\Post(
        path: '/api/v1/auth/login',
        summary: 'User Login',
        tags: ['Authentication']
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['email', 'password'],
            properties: [
                new OA\Property(
                    property: 'email',
                    type: 'string',
                    format: 'email',
                    example: 'assessment@example.com'
                ),
                new OA\Property(
                    property: 'password',
                    type: 'string',
                    format: 'password',
                    example: 'password123'
                ),
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Login successful'
    )]
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
     * --------------------------------------------------------------------------
     * Current User
     * --------------------------------------------------------------------------
     * Get the authenticated user's profile.
     */
    #[OA\Get(
        path: '/api/v1/auth/me',
        summary: 'Gettting authenticated user',
        tags: ['Authentication'],
        security: [['sanctum' => []]]
    )]
    #[OA\Response(
        response: 200,
        description: 'Authenticated user'
    )]
    public function me(Request $request): JsonResponse
    {
        return $this->success(
            new UserResource(
                $this->authService->me($request->user())
            )
        );
    }

    /**
     * --------------------------------------------------------------------------
     * Logout
     * --------------------------------------------------------------------------
     * Revoke the authenticated user's access token.
     */
    #[OA\Post(
        path: '/api/v1/auth/logout',
        summary: 'Logout user',
        tags: ['Authentication'],
        security: [['sanctum' => []]]
    )]
    #[OA\Response(
        response: 200,
        description: 'Logout successful'
    )]
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
