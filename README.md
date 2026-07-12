# Ecommerce Ordering & Payment System

A production-ready Ecommerce Ordering & Payment System built with **Laravel 13**. This project provides secure authentication, category and product management, order processing, Stripe payment integration, Redis caching, and interactive API documentation using Swagger (OpenAPI 3.0).

---

# Features

- User Authentication (Laravel Sanctum)
- Role-Based Access Control (Admin & Customer)
- Category Management (CRUD)
- Product Management (CRUD)
- Order Management
- Stripe Payment Integration
- Stripe Webhook Handling
- Redis Cache
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
├── Http/
│   ├── Controllers/
│   ├── Requests/
│   └── Resources/
├── Models/
├── Services/
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
├── System-Architecture.png
└── Payment-Flow.png
```

---

# Installation

Clone the repository

```bash
git clone <repository-url>
```

Go to the project

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

Run migrations and seeders

```bash
php artisan migrate:fresh --seed
```

Generate Swagger documentation

```bash
php artisan l5-swagger:generate
```

Run the application

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

Open Swagger UI

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

# API Modules

## Authentication

- Register
- Login
- Current User
- Logout

## Categories

- List Categories
- Category Tree
- DFS Traversal
- Create Category
- Update Category
- Delete Category

## Products

- List Products
- Product Details
- Create Product
- Update Product
- Delete Product

## Orders

- Create Order

## Payments

- Stripe Payment

## Webhooks

- Stripe Webhook

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

> The payment module is implemented using the **Strategy Pattern**, allowing additional payment providers (such as bKash) to be integrated with minimal changes.

> **Note:** bKash sandbox credentials were unavailable during the assessment period, so the integration could not be completed.

---

# Redis

Redis is used for

- API Cache
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
- Secure Payment Webhook Verification

---

# Database Seeders

Included

- AdminUserSeeder
- CustomerUserSeeder
- CategorySeeder
- ProductSeeder

---

# API Testing

The REST APIs can be tested using

- Swagger UI
- Postman

---

# Documentation

The following documentation is included inside the **docs/** directory.

- ER Diagram
- System Architecture Diagram
- Payment Flow Diagram

---

# Future Improvements

- bKash Payment Integration
- Payment Refunds
- Email Notifications
- Queue Workers
- Automated Feature Tests
- CI/CD Pipeline (GitHub Actions)

---

# License

This project was developed as part of a Backend Developer Technical Assessment.

---

# Developed By

**Samiul Islam**

Backend Developer Assessment

Laravel 13 • Docker • Redis • Stripe • Swagger • Sanctum
