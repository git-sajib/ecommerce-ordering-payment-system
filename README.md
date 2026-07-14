# Ecommerce Ordering & Payment System

A production-ready **Ecommerce Ordering & Payment System** built with **Laravel 13**. The project provides secure authentication, role-based authorization (RBAC), category and product management, order processing, Stripe payment integration, category hierarchy traversal using DFS, caching, and interactive API documentation with **Swagger (OpenAPI 3.0)**.

---

# Features

- User Authentication (Laravel Sanctum)
- Role-Based Access Control (RBAC)
- Category Management (CRUD)
- Product Management (CRUD)
- Order Management
- Order History
- Payment Management
- Payment History
- Stripe Payment Integration
- Stripe Webhook Handling
- Category Hierarchy (Tree)
- Depth First Search (DFS) Traversal
- Category Caching
- Service Layer Architecture
- Strategy Pattern (Payment Providers)
- RESTful API Design
- Swagger (OpenAPI 3.0)

---

# Technology Stack

| Technology    | Version                  |
| ------------- | ------------------------ |
| PHP           | 8.3                      |
| Laravel       | 12                       |
| MySQL         | 8                        |
| Laravel Cache | File Cache (Redis Ready) |
| Stripe        | Latest API               |
| Sanctum       | Laravel Sanctum          |
| Swagger       | L5 Swagger (OpenAPI 3.0) |

---

# System Architecture

```
docs/System-Architecture.png
```

---

# Database Design (ER Diagram)

```
docs/ER-Diagram.png
```

---

# Payment Flow

```
docs/Payment-Flow.png
```

---

# Project Structure

```
app/
├── Enums/
├── Http/
│   ├── Controllers/
│   ├── Middleware/
│   ├── Requests/
│   └── Resources/
├── Models/
├── Services/
├── Strategies/
├── Traits/
└── Providers/

database/
├── migrations/
├── factories/
└── seeders/

routes/
├── api.php
└── web.php

docs/
├── ER-Diagram.png
├── Payment-Flow.png
└── System-Architecture.png
```

---

# Installation

Clone repository

```bash
git clone <repository-url>
```

Navigate

```bash
cd ecommerce-ordering-payment-system
```

Install dependencies

```bash
composer install
```

Copy environment

```bash
cp .env.example .env
```

Generate application key

```bash
php artisan key:generate
```

Configure database inside `.env`

Run migrations & seeders

```bash
php artisan migrate:fresh --seed
```

Generate Swagger

```bash
php artisan l5-swagger:generate
```

Run application

```bash
php artisan serve
```

---

# Environment Variables

Example configuration

```env
APP_NAME=Ecommerce
APP_ENV=local
APP_DEBUG=true

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecommerce_backend
DB_USERNAME=root
DB_PASSWORD=

CACHE_STORE=file

STRIPE_KEY=pk_test_xxxxxxxxx
STRIPE_SECRET=sk_test_xxxxxxxxx
STRIPE_WEBHOOK_SECRET=whsec_xxxxxxxxx
```

---

## API Documentation

Swagger UI

http://127.0.0.1:8000/api/documentation

OpenAPI JSON

http://127.0.0.1:8000/docs

---

# Demo Accounts

## Administrator

Email

```
admin@example.com
```

Password

```
password123
```

---

## Customer

Email

```
assessment@example.com
```

Password

```
password123
```

---

# User Roles

## Administrator

- Manage Categories
- Manage Products
- Access Administrative APIs

## Customer

- Register
- Login
- Browse Categories
- Browse Products
- Create Orders
- View Own Orders
- Create Payments
- View Own Payments

---

# API Modules

## Authentication

| Method | Endpoint              |
| ------ | --------------------- |
| POST   | /api/v1/auth/register |
| POST   | /api/v1/auth/login    |
| GET    | /api/v1/auth/me       |
| POST   | /api/v1/auth/logout   |

---

## Categories

| Method | Endpoint                |
| ------ | ----------------------- |
| GET    | /api/v1/categories      |
| GET    | /api/v1/categories/tree |
| GET    | /api/v1/categories/dfs  |
| GET    | /api/v1/categories/{id} |
| POST   | /api/v1/categories      |
| PUT    | /api/v1/categories/{id} |
| DELETE | /api/v1/categories/{id} |

---

## Products

| Method | Endpoint              |
| ------ | --------------------- |
| GET    | /api/v1/products      |
| GET    | /api/v1/products/{id} |
| POST   | /api/v1/products      |
| PUT    | /api/v1/products/{id} |
| DELETE | /api/v1/products/{id} |

---

## Orders

| Method | Endpoint            |
| ------ | ------------------- |
| GET    | /api/v1/orders      |
| GET    | /api/v1/orders/{id} |
| POST   | /api/v1/orders      |

---

## Payments

| Method | Endpoint              |
| ------ | --------------------- |
| GET    | /api/v1/payments      |
| GET    | /api/v1/payments/{id} |
| POST   | /api/v1/payments      |

---

## Webhooks

| Method | Endpoint                |
| ------ | ----------------------- |
| POST   | /api/v1/webhooks/stripe |

---

# Authentication

Protected endpoints require

```
Authorization: Bearer {token}
```

---

# Payment Providers

## Implemented

- Stripe

## Planned

- bKash

The payment module follows the **Strategy Pattern**, allowing additional payment gateways to be added without modifying the order processing logic.

> **Note:** bKash sandbox/live integration is prepared architecturally. Stripe integration is fully implemented for the assessment.

---

# Caching

Category hierarchy uses **Laravel Cache** to minimize repeated database queries during DFS traversal. The implementation is compatible with both File Cache and Redis drivers.

---

# Design Patterns

- Service Layer Pattern
- Strategy Pattern (Payment Providers)

---

# Algorithms

- Deterministic Order Total Calculation
- Safe Stock Reduction Algorithm
- Depth First Search (DFS) for Category Hierarchy

---

# Security

- Laravel Sanctum Authentication
- Password Hashing
- Request Validation
- Role-Based Authorization (RBAC)
- Mass Assignment Protection
- API Resources
- Stripe Webhook Signature Verification
- User Ownership Validation

---

# Database Seeders

Included seeders

- AdminUserSeeder
- CustomerUserSeeder
- CategorySeeder
- ProductSeeder

Run

```bash
php artisan migrate:fresh --seed
```

---

# API Testing

- Swagger UI
- Postman

---

# Deployment

## Backend

```bash
php artisan serve
```

Expose the backend

```bash
ngrok http 8000
```

Example

```
https://xxxxxxxx.ngrok-free.app
```

---

# Documentation

Included

- System Architecture Diagram
- ER Diagram
- Payment Flow Diagram
- Swagger (OpenAPI 3.0)

---

# Future Improvements

- Complete bKash Integration
- Refund API
- Queue Workers
- Email Notifications
- Feature Tests
- CI/CD Pipeline

---

# License

Developed as part of a **Backend Engineer Technical Assessment**.

---

# Developed By

**Samiul Islam**

Backend Engineer Technical Assessment

Laravel • Stripe • Sanctum • Swagger • MySQL
