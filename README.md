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

## Routes

| Path | Who |
| --- | --- |
| `GET /` | public landing page |
| `GET /dashboard`, `/books`, `/my-borrowed-book` | `book_borrower` |
| `/admin/books`, `/admin/categories`, `/admin/book-borrowers`, `/admin/shield/roles` | staff |

## Data model

```
Category 1 ──── * Book 1 ──── * BookBorrower * ──── 1 User
```

- `Category` — `name`, `slug`
- `Book` — `category_id`, `title`, `author`, `isbn` (unique), `total_copies`, `available_copies`
- `BookBorrower` — `book_id`, `borrower_id`, `borrow_at`, `return_at`, unique(`borrower_id`, `book_id`)

Borrowing decrements `available_copies`; returning increments it and stamps `return_at`. Double-borrowing is blocked both in the component and by the unique index.

## Project layout

```
app/Filament/Resources/   Books, BookBorrowers, Categories (Filament CRUD)
app/Policies/             Shield-generated policies
resources/views/pages/
  book/                   ⚡ catalogue + borrowed-book Livewire components
  settings/               ⚡ profile, security, appearance
  auth/                   Fortify auth screens
```

`⚡` marks Livewire 4 single-file components; the filename maps to the component name (`pages/book/⚡book.blade.php` → `pages::book.book`). Borrow/return logic lives inside these components, not in service classes.

## Quality tooling

```bash
composer run lint          # pint
composer run lint:check    # pint --test
composer run types:check   # phpstan level 7
composer run test          # config:clear + lint:check + types:check + pest
composer run ci:check      # full suite
```

## Known gaps

1. **Registration creates a role-less user.** `app/Actions/Fortify/CreateNewUser.php` assigns no role, so self-registered users cannot reach `/books`. Assign `book_borrower` there or manually.
2. **No domain factories or seed data** — no `BookFactory`, `CategoryFactory`, or `BookBorrowerFactory` exists.
3. **No tests for the domain.** The 12 Pest files cover only the inherited auth surface; `Book`, `BookBorrower`, `Category`, Filament resources, and borrow/return flows are untested.
4. **Book view page renders empty** — `Books/Schemas/BookInfolist.php` has an empty `components()` array.
5. **Categories are only creatable from the Book form** — standalone create/edit page registrations are commented out in `CategoryResource.php`.
6. **`MustVerifyEmail` is commented out** on `User` even though the `verified` middleware is applied.
7. **Branding is unconfigured** — `APP_NAME` is still `Laravel` and `AdminPanelProvider` sets no `brandName()`.
8. **`composer.json`** is still named `laravel/livewire-starter-kit`.

## License

MIT.
