# Ecommerce Ordering & Payment System

A production-ready **Ecommerce Ordering & Payment System** built with **Laravel 13**. The project provides secure authentication, role-based authorization, category and product management, order processing, Stripe payment integration, Redis caching, and interactive API documentation using **Swagger (OpenAPI 3.0)**.

---

# Features

- User Authentication (Laravel Sanctum)
- Role-Based Access Control (Admin & Customer)
- Category Management (CRUD)
- Product Management (CRUD)
- Order Management
- Order History
- Payment Management
- Payment History
- Stripe Payment Integration
- Stripe Webhook Handling
- Redis Caching
- Docker Support
- Swagger (OpenAPI 3.0)
- Category Tree Traversal (DFS)
- Service Layer Architecture
- Strategy Pattern for Payment Providers
- RESTful API Design

---

# Technology Stack

| Technology | Version                  |
| ---------- | ------------------------ |
| PHP        | 8.3                      |
| Laravel    | 13                       |
| MySQL      | 8                        |
| Redis      | 7                        |
| Docker     | Latest                   |
| Nginx      | Latest                   |
| Stripe     | Latest API               |
| Sanctum    | Laravel Sanctum          |
| Swagger    | L5 Swagger (OpenAPI 3.0) |

---

# System Architecture

See:

```
docs/System-Architecture.png
```

---

# Database Design (ER Diagram)

See:

```
docs/ER-Diagram.png
```

---

# Payment Flow

See:

```
docs/Payment-Flow.png
```

---

# Project Structure

```
app/
├── Enums/
├── Factories/
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

Clone the repository

```bash
git clone <repository-url>
```

Navigate to the project

```bash
cd ecommerce-ordering-payment-system
```

Install dependencies

```bash
composer install
```

Copy the environment file

```bash
cp .env.example .env
```

Generate application key

```bash
php artisan key:generate
```

Run database migrations and seeders

```bash
php artisan migrate:fresh --seed
```

Generate Swagger documentation

```bash
php artisan l5-swagger:generate
```

Start the application

```bash
php artisan serve
```

---

# Docker

Build containers

```bash
docker compose build
```

Start containers

```bash
docker compose up -d
```

Run migrations

```bash
docker compose exec app php artisan migrate --seed
```

Generate Swagger

```bash
docker compose exec app php artisan l5-swagger:generate
```

---

# API Base URL

```
http://127.0.0.1:8000/api/v1
```

---

# Swagger Documentation

Generate documentation

```bash
php artisan l5-swagger:generate
```

Swagger UI

```
http://127.0.0.1:8000/api/documentation
```

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
- Access administrative APIs

## Customer

- Register & Login
- Browse Categories
- Browse Products
- Create Orders
- View Own Orders
- Create Payments
- View Own Payments

---

# API Modules

## Authentication

| Method | Endpoint                | Description         |
| ------ | ----------------------- | ------------------- |
| POST   | `/api/v1/auth/register` | Register a new user |
| POST   | `/api/v1/auth/login`    | Login               |
| GET    | `/api/v1/auth/me`       | Authenticated user  |
| POST   | `/api/v1/auth/logout`   | Logout              |

---

## Categories

| Method | Endpoint                  | Description             |
| ------ | ------------------------- | ----------------------- |
| GET    | `/api/v1/categories`      | List Categories         |
| GET    | `/api/v1/categories/tree` | Category Tree           |
| GET    | `/api/v1/categories/dfs`  | DFS Traversal           |
| GET    | `/api/v1/categories/{id}` | Category Details        |
| POST   | `/api/v1/categories`      | Create Category (Admin) |
| PUT    | `/api/v1/categories/{id}` | Update Category (Admin) |
| DELETE | `/api/v1/categories/{id}` | Delete Category (Admin) |

---

## Products

| Method | Endpoint                | Description            |
| ------ | ----------------------- | ---------------------- |
| GET    | `/api/v1/products`      | List Products          |
| GET    | `/api/v1/products/{id}` | Product Details        |
| POST   | `/api/v1/products`      | Create Product (Admin) |
| PUT    | `/api/v1/products/{id}` | Update Product (Admin) |
| DELETE | `/api/v1/products/{id}` | Delete Product (Admin) |

---

## Orders

| Method | Endpoint              | Description        |
| ------ | --------------------- | ------------------ |
| GET    | `/api/v1/orders`      | View Order History |
| GET    | `/api/v1/orders/{id}` | View Order Details |
| POST   | `/api/v1/orders`      | Create Order       |

---

## Payments

| Method | Endpoint                | Description          |
| ------ | ----------------------- | -------------------- |
| GET    | `/api/v1/payments`      | View Payment History |
| GET    | `/api/v1/payments/{id}` | View Payment Details |
| POST   | `/api/v1/payments`      | Create Payment       |

---

## Webhooks

| Method | Endpoint                  | Description    |
| ------ | ------------------------- | -------------- |
| POST   | `/api/v1/webhooks/stripe` | Stripe Webhook |

---

# Authentication

Protected endpoints require a Bearer Token.

```
Authorization: Bearer {token}
```

---

# Payment Providers

## Implemented

- Stripe

## Planned

- bKash

The payment module is implemented using the **Strategy Pattern**, making it easy to integrate additional payment gateways.

> **Note:** bKash sandbox credentials were unavailable during the assessment period, so the integration could not be completed. The application architecture is ready for adding bKash support.

---

# Redis

Redis is used for

- API Caching
- Session Storage
- Queue Support (Configurable)

---

# Design Patterns

- Service Layer Pattern
- Strategy Pattern (Payment Gateway)

---

# Algorithms

Category hierarchy traversal is implemented using

- Depth First Search (DFS)

---

# Security

- Laravel Sanctum Authentication
- Password Hashing
- Request Validation
- Role-Based Authorization
- Mass Assignment Protection
- API Resources
- Stripe Webhook Signature Verification
- User Ownership Validation for Orders & Payments

---

# Database Seeders

Included seeders

- AdminUserSeeder
- CustomerUserSeeder
- CategorySeeder
- ProductSeeder

Populate the database using

```bash
php artisan migrate:fresh --seed
```

---

# API Testing

The REST APIs can be tested using

- Swagger UI
- Postman

---

# Documentation

The project includes

- ER Diagram
- System Architecture Diagram
- Payment Flow Diagram
- Swagger (OpenAPI 3.0)

All documentation files are located inside the **docs/** directory.

---

# Future Improvements

- bKash Payment Integration
- Payment Refund Support
- Email Notifications
- Background Queue Workers
- Automated Feature Tests
- GitHub Actions CI/CD Pipeline

---

# License

This project was developed as part of a **Backend Developer Technical Assessment**.

---

# Developed By

**Samiul Islam**

Backend Developer Assessment

**Laravel 13 • Docker • Redis • Stripe • Swagger • Sanctum**
