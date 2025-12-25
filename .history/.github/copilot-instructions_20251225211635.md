# GitHub Copilot Instructions for OnlineBook (Laravel)

## Project Overview
This is a Laravel 12 application for an online book reading platform. It uses PHP 8.2+, Blade templates, Tailwind CSS 4, and Vite.

## Architecture & Patterns

### Backend (Laravel)
- **MVC Structure**: Standard Laravel directory structure.
- **Authentication**: 
  - Uses standard Laravel `Auth` facade.
  - `User` model has a `role` column. Admins have `role === 'admin'`.
  - `App\Http\Middleware\AdminMiddleware` protects `/admin` routes.
  - Social Login (Facebook, Instagram, Twitter) via `SocialController` and Laravel Socialite.
- **Database**:
  - Migrations are in `database/migrations`.
  - Key Models: `User`, `Book`, `Author`, `ReadingProgress`, `Review`, `Subscription`.
  - **Book Authors**: `Book` model supports both a simple string `author` column and a relational `author_id` (linking to `Author` model). Use `getAuthorDisplayAttribute` (accessed as `$book->author_display`) to display the author name reliably.

### Frontend
- **Stack**: Blade Templates + Tailwind CSS 4.
- **Build Tool**: Vite (`vite.config.js`).
- **Assets**: Located in `resources/css` and `resources/js`.
- **Layouts**:
  - Public pages often include `resources/views/include/header.blade.php`.
  - Admin pages extend `layouts.sidebar` (`resources/views/layouts/sidebar.blade.php`).
- **Book Reader**: The reading view (`books.read`) chunks long text descriptions into slides/pages for better readability.

## Critical Workflows

### Development
- **Server**: Run `php artisan serve` for the backend.
- **Assets**: Run `npm run dev` for Vite hot reloading.
- **Database**: Run `php artisan migrate` to update schema.

### Admin Panel
- Routes are prefixed with `/admin` and named `admin.*`.
- Controllers for admin are standard resource controllers (e.g., `UserController`, `BookController`).
- Admin dashboard is at `admin.dashboard`.

## Coding Conventions

- **Routes**: Define web routes in `routes/web.php`. API routes in `routes/api.php` are currently minimal/unused.
- **Controllers**: Keep logic in controllers or move complex logic to Services if needed (though currently mostly in Controllers).
- **Views**: Use Blade components or `@include` for reusable parts (like headers).
- **Styling**: Use Tailwind utility classes. Avoid custom CSS unless necessary (e.g., `book-reader.css`).

## Specific Implementation Details

- **Reading Progress**: Handled by `BookController::saveProgress` via AJAX. Stores `current_page` and `percentage` in `reading_progress` table.
- **Book Display**: When displaying books, ensure you handle both legacy string authors and relational authors.
- **Subscriptions**: `SubscriptionController` handles payments and status.

## Common Commands
- `php artisan make:model ModelName -m` (Create model + migration)
- `php artisan make:controller Admin/ControllerName --resource` (Create admin resource controller)
- `npm run build` (Build assets for production)
