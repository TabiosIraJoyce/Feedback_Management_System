# Installation Guide

## Prerequisites

- PHP 8.2 or later
- Composer
- Node.js and npm
- SQLite, MySQL, or MariaDB
- Git

## Local Setup

1. Clone repository:
   ```bash
   git clone <repo> .
   ```
2. Install PHP dependencies:
   ```bash
   composer install
   ```
3. Install frontend dependencies:
   ```bash
   npm install
   ```
4. Copy environment file:
   ```bash
   cp .env.example .env
   ```
5. Generate application key:
   ```bash
   php artisan key:generate
   ```
6. Configure `.env` values for database, mail, and cache.
7. Run migrations:
   ```bash
   php artisan migrate
   ```
8. Create a public storage link for attachments:
   ```bash
   php artisan storage:link
   ```
9. Build assets:
   ```bash
   npm run build
   ```
10. Start local development server:
   ```bash
   php artisan serve
   ```

## Laravel Breeze

This application uses Laravel Breeze with Blade, Tailwind CSS, and Alpine.js. Run `npm run dev` for hot-reload development.

## Environment Notes

- Ensure `APP_DEBUG=false` in production.
- Use `SESSION_SECURE_COOKIE=true` and `SESSION_HTTP_ONLY=true` for production sessions.
- Configure `FILESYSTEM_DISK`, `QUEUE_CONNECTION`, and `CACHE_STORE` for your production host.
