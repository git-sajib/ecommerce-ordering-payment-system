<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Ecommerce Ordering Payment API',
    description: 'REST API Documentation for the Ecommerce Ordering Payment System.'
)]

#[OA\Server(
    url: 'http://127.0.0.1:8000',
    description: 'Local Development Server'
)]

#[OA\SecurityScheme(
    securityScheme: 'sanctum',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'JWT',
    description: 'Enter your Sanctum Bearer Token'
)]

#[OA\PathItem(path: '/')]
class OpenApi {}
