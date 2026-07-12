# Ecommerce Ordering & Payment System

A production-oriented Laravel 13 REST API for an Ecommerce Ordering & Payment System developed as a backend assessment.

---

## Features

- User Authentication (Laravel Sanctum)
- Role-Based Access Control (Admin / Customer)
- Category Management
- Product Management
- Order Management
- Stripe Payment Gateway
- Redis Caching
- Docker Support
- Swagger (OpenAPI 3.0) Documentation
- Category Tree Traversal (DFS)
- Strategy Pattern for Payment Providers
- RESTful API Architecture

---

# Technology Stack

| Technology | Version         |
| ---------- | --------------- |
| PHP        | 8.3             |
| Laravel    | 13              |
| MySQL      | 8               |
| Redis      | 7               |
| Docker     | Latest          |
| Nginx      | Latest          |
| Stripe     | Latest API      |
| Sanctum    | Laravel Sanctum |
| Swagger    | L5 Swagger      |

---

# Architecture

```
Client
      │
      ▼
 Laravel REST API
      │
 ┌───────────────┐
 │ Controllers   │
 └───────────────┘
      │
      ▼
 Service Layer
      │
      ▼
 Models
      │
      ▼
 MySQL Database

Redis
Caching Layer

Stripe
Payment Gateway
```

---

# Project Structure

```
app/
 ├── Http/
 │    ├── Controllers
 │    ├── Requests
 │    ├── Resources
 │
 ├── Services
 │
 ├── Models
 │
 ├── Traits
 │
 └── Enums
```

---

# Installation

Clone the repository

```bash
git clone <repository-url>
```

```bash
cd ecommerce-ordering-payment-system
```

Install dependencies

```bash
composer install
```

Copy environment file

```bash
cp .env.example .env
```

Generate application key

```bash
php artisan key:generate
```

Run migrations and seeders

```bash
php artisan migrate:fresh --seed
```

Start Laravel

```bash
php artisan serve
```

---

# Docker

Build containers

```bash
docker compose build
```

Run containers

```bash
docker compose up -d
```

Run migrations

```bash
docker compose exec app php artisan migrate --seed
```

---

# Swagger Documentation

Generate documentation

```bash
php artisan l5-swagger:generate
```

Swagger UI

```
http://localhost:8000/api/documentation
```

---

# Default Accounts

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

# API Modules

Authentication

- Register
- Login
- Current User
- Logout

Categories

- List Categories
- Category Tree
- DFS Traversal
- Create Category
- Update Category
- Delete Category

Products

- List Products
- Product Details
- Create Product
- Update Product
- Delete Product

Orders

- Create Order

Payments

- Stripe Payment

Webhooks

- Stripe Webhook

---

# Authentication

All protected routes require a Bearer Token.

```
Authorization: Bearer {token}
```

---

# Payment Providers

Implemented

- Stripe

Planned

- bKash Sandbox Integration

> **Note:** bKash integration is designed using the Strategy Pattern. Sandbox credentials were unavailable during the assessment period, so live implementation could not be completed.

---

# Redis

Redis is used for:

- Cache
- Queue (configurable)

---

# Design Patterns

- Service Layer Pattern
- Strategy Pattern (Payment Gateway)

---

# Algorithms

Category Tree Traversal implemented using

- Depth First Search (DFS)

---

# Seeders

Included

- AdminUserSeeder
- CustomerUserSeeder
- CategorySeeder
- ProductSeeder

---

# Security

- Laravel Sanctum Authentication
- Password Hashing
- Request Validation
- Role-Based Authorization
- Mass Assignment Protection

---

# Future Improvements

- bKash Payment Integration
- Email Notifications
- PHPUnit Feature Tests
- GitHub Actions CI/CD
- Frontend (Next.js)

---

# Developed By

**Samiul Islam**

Backend Developer Assessment

Laravel 13 • Docker • Redis • Stripe • Swagger
