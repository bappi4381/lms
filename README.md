# LMS — Installation Guide

A Laravel-based Learning Management System built with Laravel 13, Filament 5, and Livewire.

## Requirements

- PHP >= 8.2
- Composer
- Node.js & npm
- MySQL (or another supported database)
- [Laragon](https://laragon.org/) (or any local dev environment — XAMPP, Herd, Valet, etc.)

## Installation Steps

### 1. Clone the repository

```bash
git clone https://github.com/bappi4381/lms.git
cd lms
```

### 2. Install PHP dependencies

```bash
composer install
```

This will install all Laravel, Filament, Livewire, and related packages, and automatically run:
- `artisan package:discover`
- `artisan filament:upgrade`

### 3. Set up environment file

```bash
cp .env.example .env
php artisan key:generate
```

Then open `.env` and configure your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_lms
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Run database migrations

```bash
php artisan migrate
```

If the database doesn't exist yet, Laravel will prompt to create it:

```
The database 'laravel_lms' does not exist on the 'mysql' connection.
Would you like to create it? (yes/no) [yes]
```

Type `yes` to continue. This creates all required tables (users, courses, modules, lessons, enrollments, orders, quizzes, assignments, certificates, subscriptions, and more).

### 5. Create the storage symlink

```bash
php artisan storage:link
```

This links `public/storage` to `storage/app/public` so uploaded files (course thumbnails, avatars, etc.) are accessible publicly.

### 6. Install frontend dependencies

```bash
npm install
```

### 7. Build frontend assets

```bash
npm run build
```

### 8. Start the development server

```bash
php artisan serve
```

Your application will now be running at:

```
http://127.0.0.1:8000
```

## Quick Reference (All Commands)

```bash
git clone https://github.com/bappi4381/lms.git
cd lms
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
npm install
npm run build
php artisan serve
```

## Notes

- Make sure MySQL is running before executing `php artisan migrate`.
- Run `php artisan storage:link` from **inside the `lms` project folder**, not the parent directory — running it outside the project will fail with `Could not open input file: artisan`.
- For local development, you can use `npm run dev` instead of `npm run build` to enable hot-reloading via Vite.
