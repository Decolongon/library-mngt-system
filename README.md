# Library Management System

A library management app built on the **Laravel Livewire Starter Kit**, extended with **Filament 5** for staff administration and **spatie/laravel-permission** for role-based access control.

Members browse and borrow books through a Livewire + Flux UI frontend. Librarians and admins manage the catalogue through a role-gated Filament panel at `/admin`.

## Tech stack

Laravel 13 · PHP 8.3 · Livewire 4 · Flux UI · Filament 5 (+ Shield) · Fortify (2FA, passkeys) · spatie/laravel-permission · Tailwind 4 / Vite · Pest · PHPStan (larastan) · Pint

Database: MySQL by default (`db_book_mngt`); `.env.example` ships SQLite and `config/database.php` falls back to it.

## Installation

```bash
git clone <repository-url>
cd library-mngt-system
composer run setup     # install, .env, key, migrate, npm build
php artisan db:seed    # required for accounts — setup does not seed
composer run dev       # serve + Vite with HMR
```

App runs at `http://localhost:8000`, admin panel at `/admin`.

## Seeded accounts

All use password `12345678` — change before deploying anywhere public.

| Role | Email | Lands on |
| --- | --- | --- |
| `librarian` | `librarian@gmail.com` | `/admin` |
| `super_admin` | `superadmin@gmail.com` | `/admin` |
| `book_borrower` | `book_borrower@gmail.com` | `/dashboard` |

The seeder creates no books or categories — add them through the admin panel.

## Roles and access control

| Role | Access |
| --- | --- |
| `book_borrower` | Catalogue, borrow, return. 403 on `/admin`. |
| `librarian` | Full Filament panel. |
| `super_admin` | Full panel including Shield role management. |

- Panel gate: `app/Http/Middleware/FilamentPanelMiddleware.php` returns 403 unless `librarian` or `super_admin`.
- Member routes use `['auth', 'verified', 'role:book_borrower']`; `role` is aliased to Spatie's `RoleMiddleware` in `bootstrap/app.php`.
- Fine-grained permissions come from Shield policies in `app/Policies/` (`Book`, `BookBorrower`, `Category`, `Role`), named e.g. `Create:BookBorrower`.

Regenerate with `php artisan shield:generate`; grant super admin with `php artisan shield:super-admin`.



## License

MIT.
