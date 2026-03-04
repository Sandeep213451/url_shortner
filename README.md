# URL Shortener — Laravel Application

A role-based URL shortener service built with PHP Laravel 12 and MySQL.

## Roles

| Role       | Can Create URLs | Can View URLs                        | Can Invite         |
|------------|-----------------|--------------------------------------|--------------------|-
| SuperAdmin | ❌              | ✅ (All Companies)                   | ✅ (Admin only)    |
| Admin      | ✅              | ✅ (Their Company)                   | ✅ (Admin, Member) |
| Member     | ✅              | ✅ (Own URLs Only)                   | ❌                  |

## Local Setup

### Prerequisites

- PHP 8.2+
- Composer
- MySQL 5.7+ or MariaDB

### Steps

```bash
# 1. Clone the repository
git clone <your-repo-url>
cd url_shortner

# 2. Install PHP dependencies
composer install

# 3. Copy environment file
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Configure database connection in .env
# Set DB_DATABASE, DB_USERNAME, DB_PASSWORD to match your MySQL setup

# 6. Run database migrations
php artisan migrate

# 7. Seed the SuperAdmin account
php artisan db:seed
```

### SuperAdmin Login Credentials

| Field    | Value                     |
|----------|---------------------------|
| Email    | superadmin@gmail.com      |
| Password | password                  |

### Run the Development Server

```bash
php artisan serve
```

Then open [http://localhost:8000](http://localhost:8000) in your browser.

### Run Tests

```bash
php artisan test
```

Expected output: **11 tests, 24 assertions — all passing**.

> Note: Tests use an in-memory SQLite database (configured in `phpunit.xml`) so no MySQL setup is required to run tests.

## Project Structure

```
app/
  Http/Controllers/
    Auth/LoginController.php        # Login / Logout
    InvitationController.php        # Invite new team members
    ShortUrlController.php          # Create & list short URLs
    RedirectController.php          # Resolve short URL → redirect
  Models/
    User.php                        # Roles, constants, relationships
    Company.php                     # Company model
    ShortUrl.php                    # Short URL model
database/
  migrations/                       # Schema definitions
  seeders/DatabaseSeeder.php        # Creates SuperAdmin via raw SQL
resources/views/
  layouts/app.blade.php             # Main layout with topbar
  auth/login.blade.php              # Login form
  short_urls/index.blade.php        # URL list (role-filtered)
  short_urls/create.blade.php       # Create form (Admin/Member only)
  invite.blade.php                  # Invite form
tests/Feature/
  UrlRestrictionTest.php            # Role restriction + public access tests
  InvitationRestrictionTest.php     # Invitation restriction tests
```

## AI Usage

Used **Antigravity (Google DeepMind)** for:
- Laravel validation syntax for URL and invitation form requests
- Understanding and writing Eloquent `hasMany` / `belongsTo` relationships between `Company`, `User`, and `ShortUrl` models
- Writing PHPUnit feature test structure using `actingAs()`, `assertDatabaseHas()`, and `RefreshDatabase`
- Debugging missing controller and view files during development
- Generating the `DatabaseSeeder` raw SQL insert for the SuperAdmin account
