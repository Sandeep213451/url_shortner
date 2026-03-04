URL Shortner — Laravel Application

A multi-company, role-based URL shortener service built with PHP Laravel 12 and MySQL.

------------------------------------------------------------

SUMMARY

This application allows users to generate publicly accessible short URLs.

The system supports:
- Multiple Companies
- Multiple Users per Company
- Roles:
  • SuperAdmin
  • Admin
  • Member

------------------------------------------------------------

TECH STACK

- PHP Laravel 12
- MySQL
- Blade (Simple HTML)
- Laravel session-based authentication

------------------------------------------------------------

ROLES & PERMISSIONS

SuperAdmin
- Cannot create short URLs
- Can view all URLs (all companies)
- Can invite Admin in a new company

Admin
- Can create short URLs
- Can view URLs in their own company only
- Can invite Admin or Member in their own company

Member
- Can create short URLs
- Can view only their own created URLs
- Cannot invite users

------------------------------------------------------------

AUTHENTICATION & AUTHORIZATION

- Roles: SuperAdmin, Admin, Member
- SuperAdmin created using Database Seeder
- Users can log in and log out
- Middleware and role checks used for authorization

------------------------------------------------------------

INVITATION SYSTEM

SuperAdmin:
- Invites Admin
- Creates new Company during invitation

Admin:
- Invites Admin or Member
- Restricted to own company

Member:
- No invitation permissions

------------------------------------------------------------

URL SHORTNER FEATURES

URL Creation:
- Admin → Allowed
- Member → Allowed
- SuperAdmin → Not Allowed

URL Visibility:
- SuperAdmin → All companies
- Admin → Own company only
- Member → Own URLs only

Public Redirection:
- All short URLs are publicly accessible
- Automatically redirect to original URL

------------------------------------------------------------

LOCAL SETUP

Prerequisites:
- PHP 8.2+
- Composer
- MySQL 5.7+ or MariaDB

Installation Steps:

1. git clone <your-github-repo-url>
2. cd url_shortner
3. composer install
4. cp .env.example .env
5. php artisan key:generate
6. Configure DB_DATABASE, DB_USERNAME, DB_PASSWORD
7. php artisan migrate
8. php artisan db:seed

SuperAdmin Credentials:
Email: superadmin@gmail.com
Password: password

Run Server:
php artisan serve

Open:
http://localhost:8000

------------------------------------------------------------

RUN TESTS

php artisan test

Tests Cover:
- Admin and Member can create short URLs
- SuperAdmin cannot create short URLs
- Admin sees only company URLs
- Member sees only own URLs
- Public redirection works

Tests use in-memory SQLite (phpunit.xml).

------------------------------------------------------------

AI USAGE DISCLOSURE

Used ChatGPT for:
- Laravel role-based authorization clarification
- Eloquent relationship understanding
- Test structuring guidance

Used only for syntax lookup and debugging.
Core logic and implementation done independently.

