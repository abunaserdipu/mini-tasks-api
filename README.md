# Mini Task Management API

## Requirements

-   PHP 8.2
-   Laravel 12
-   Composer
-   MySQL

## Installation

1. Clone repo
2. cp .env.example .env
3. Set DB credentials in .env
4. composer install
5. php artisan key:generate
6. php artisan migrate --seed
7. php artisan serve

## Authentication

-   Register: POST /api/register
-   Login: POST /api/login
-   Use Authorization: Bearer {token} header for protected routes.

## Endpoints

-   /api/projects (CRUD)
-   /api/projects/{projectId}/tasks (CRUD + filtering: status, due_date_from, due_date_to)

## Notes

-   Uses Laravel Sanctum (personal access tokens).
-   Pagination supported via `per_page` query param.
