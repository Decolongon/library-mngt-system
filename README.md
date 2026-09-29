# Library Management System

A web-based library management application built on the official **Laravel Livewire Starter Kit**, extended with **Filament 5** for staff administration and **spatie/laravel-permission** for role-based access control.

Members browse the catalogue and borrow/return books through a Livewire + Flux UI frontend. Librarians and admins manage books, categories, and borrow records through a role-gated Filament panel.

## Table of contents

- [Features](#features)
- [Tech stack](#tech-stack)
- [Installation](#installation)
- [Seeded accounts](#seeded-accounts)
- [Roles and permissions](#roles-and-permissions)
- [Routes](#routes)
- [Data model](#data-model)
- [Project structure](#project-structure)
- [Quality tooling](#quality-tooling)
- [Notes and caveats](#notes-and-caveats)
- [License](#license)

## Features

### Member area (Livewire + Flux)

- **Catalogue** (`/books`) — live search across title, author, and ISBN with `wire:model.live.debounce.300ms`.
- **Borrow** — one-click borrow, authorized through `BookPolicy`, with protection against double-borrowing (application-level guard plus a `unique(['borrower_id','book_id'])` unique index) and against borrowing unavailable copies. Decrements `available_copies`.
- **My Borrowed Books** (`/my-borrowed-book`) — lists the member's active loans, updates live via a `book-borrowed` event, and supports returns. Returns set `return_at` to today and increment `available_copies`. Authorization confirms the record belongs to the authenticated user (`abort_if(..., 403)`).

### Admin panel (Filament 5, `/admin`)

| Resource | Path | Capability |
| --- | --- | --- |
| Books | `/admin/books` | Full CRUD, view, bulk delete |
| Categories | `/admin/categories` | List only (categories are created inline from the Book form) |
| Book Borrowers | `/admin/book-borrowers` | Full CRUD and view |
| Roles | `/admin/shield/roles` | Filament Shield role/permission management |

- Panel is served in SPA mode at `/admin`.
- Guarded by a custom middleware that returns `403` unless the user holds `librarian` or `super_admin` (`app/Http/Middleware/FilamentPanelMiddleware.php`).
- Colours: primary green, warning orange, success emerald, danger rose, info blue.
- Book form includes an inline "create category" option that auto-generates a slug.
- `available_copies` is validated as `<= total_copies` and both must be `>= 1`.
- Categories list is navigable, but the `CreateCategory` / `EditCategory` page registrations are commented out in `CategoryResource.php`.

### Authentication (Fortify)

Enabled features: registration, password reset, email verification, two-factor authentication (with password + code confirmation), and passkeys.

Post-login routing is role-aware (`app/Providers/FortifyServiceProvider.php`):

- `librarian` or `super_admin` → `/admin`
- `book_borrower` → `/dashboard`
- any other user → back to the login screen

Rate limiting is configured: login 5/min, two-factor 5/min per session, passkeys 10/min.

## Tech stack

| Layer | Package | Version |
| --- | --- | --- |
| Language | PHP | `^8.3` (developed on 8.5, CI on 8.4) |
| Framework | Laravel | `^13.17` |
| Auth | laravel/fortify | `^1.37` |
| Passkeys | laravel/passkeys | `^0.2` |
| RBAC | spatie/laravel-permission | `^8.3` |
| Admin panel | filament/filament | `~5.0` |
| Filament RBAC plugin | bezhansalleh/filament-shield | `^4.3` |
| Reactive UI | livewire/livewire | `^4.1` |
| Blade single-file components | livewire/blaze | `^1.0` |
| UI component library | livewire/flux | `^2.13` |
| CSS | tailwindcss | `^4.0` (via `@tailwindcss/vite`) |
| Build tool | laravel-vite-plugin + vite-plus | `^3.1` / `0.3.0` |
| Testing | pestphp/pest | `^5.2` |
| Static analysis | larastan/larastan | `^3.9` (PHPStan level 7) |
| Formatting | laravel/pint | `^1.27` |
| Blade formatting | prettier + prettier-plugin-blade | `^3.9` |
| Local dev processes | laravel/pao | `^1.0` |
| AI tooling | laravel/boost (+ MCP) | `^2.2` |

Database: **MySQL** by default in the local `.env` (`db_book_mngt`). `.env.example` ships with SQLite, and `config/database.php` falls back to `DB_CONNECTION=sqlite`, so switching back needs no config edits.

## Installation

```bash
git clone <repository-url>
cd library-mngt-system

composer run setup
```

`composer run setup` performs: `composer install`, copies `.env.example` to `.env` if absent, `artisan key:generate`, `artisan migrate --force`, `npm install`, `npm run build`.

### Using MySQL

The seeder and migrations expect a database. To use MySQL instead of the SQLite default:

```bash
# .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_book_mngt
DB_USERNAME=root
DB_PASSWORD=
```

Create the database first, then run the migrations.

### Seeding

```bash
php artisan db:seed
```

This is **required** to get usable accounts — `composer setup` does not seed. There is no seed data for books, categories, or borrow records; create those through the admin panel after signing in.

### Running locally

```bash
composer run dev
```

Runs the registered dev processes together. Individual processes:

```bash
php artisan serve        # application on http://localhost:8000
php artisan dev:list     # list registered dev processes
npm run dev              # Vite asset server with HMR
npm run build            # production asset build
```

`php artisan dev` is provided by `laravel/pao`; inspect `dev:list` to confirm which processes it launches.

### Optional: Laravel Boost / MCP

The repository includes `boost.json` and an MCP server registration for `laravel-boost`, which exposes schema, logs, error, and documentation tools to AI agents.

## Seeded accounts

All seeded accounts use the password `12345678`. **Change these before deploying anywhere public.**

| Role | Email | Password | Lands on |
| --- | --- | --- | --- |
| `librarian` | `librarian@gmail.com` | `12345678` | `/admin` |
| `book_borrower` | `book_borrower@gmail.com` | `12345678` | `/dashboard` |
| `super_admin` | `superadmin@gmail.com` | `12345678` | `/admin` |

## Roles and permissions

Three roles are seeded:

| Role | Area | Can do |
| --- | --- | --- |
| `book_borrower` | Member | Browse catalogue, borrow, return. Blocked from `/admin` (403). |
| `librarian` | Staff | Full Filament panel access. |
| `super_admin` | Staff | Full Filament panel access, including Shield role management. |

Additional roles exist in configuration but are not seeded: `panel_user` (created automatically by Filament Shield).

### Access control implementation

- **Panel gate** — `app/Http/Middleware/FilamentPanelMiddleware.php` aborts with 403 unless the user has `librarian` or `super_admin`. Registered in `AdminPanelProvider::authMiddleware()`, replacing Filament's default `Authenticate`.
- **Member gate** — `routes/web.php` wraps `/dashboard`, `/books`, and `/my-borrowed-book` in `['auth', 'verified', 'role:book_borrower']`, where `role` is aliased to `Spatie\Permission\Middleware\RoleMiddleware` in `bootstrap/app.php`.
- **Granular permissions** — Shield generates four policies in `app/Policies/` (`BookPolicy`, `BookBorrowerPolicy`, `CategoryPolicy`, `RolePolicy`), each covering `viewAny`, `view`, `create`, `update`, `delete`, `deleteAny`, `restore`, `forceDelete`, `forceDeleteAny`, `restoreAny`, `replicate`, and `reorder`. Permission naming uses a `:` separator in Pascal case, e.g. `Create:BookBorrower`.
- **Filament policies** — `BookBorrower` declares `#[UsePolicy(BookBorrowerPolicy::class)]`; `Book` and `Category` rely on Laravel 13 automatic policy discovery.

### Changing permissions

```bash
php artisan shield:generate    # regenerate permissions and policies
php artisan shield:super-admin # assign super_admin to a user
```

Config lives in `config/filament-shield.php` (`policies.generate`, `policies.merge`, `super_admin.enabled`, `register_role_policy`).

## Routes

### Public

| Path | Name | Description |
| --- | --- | --- |
| `GET /` | `home` | Marketing landing page |
| `GET /up` | — | Health check |

### Member — `auth` + `verified` + `role:book_borrower`

| Path | Name | Description |
| --- | --- | --- |
| `GET /dashboard` | `dashboard` | Member dashboard |
| `GET /books` | `books` | Catalogue with live search |
| `GET /my-borrowed-book` | `my-borrowed-book` | Active loans and returns |

### Settings

| Path | Name | Middleware |
| --- | --- | --- |
| `GET /settings` | — | `auth` (redirects to profile) |
| `GET /settings/profile` | `profile.edit` | `auth` |
| `GET /settings/appearance` | `appearance.edit` | `auth`, `verified` |
| `GET /settings/security` | `security.edit` | `auth`, `verified`, `password.confirm` |
| `GET /.well-known/passkey-endpoints` | `well-known.passkeys` | public |

### Filament panel — `/admin`

`/admin`, `/admin/books`, `/admin/categories`, `/admin/book-borrowers`, `/admin/shield/roles`.

## Data model

```
Category 1 ──── * Book 1 ──── * BookBorrower * ──── 1 User
                                          (borrower_id)
```

| Model | Table | Fields | Relationships |
| --- | --- | --- | --- |
| `App\Models\Category` | `categories` | `name` (unique), `slug` (unique) | `books()` HasMany |
| `App\Models\Book` | `books` | `category_id` FK cascade, `title`, `author`, `isbn` (unique), `total_copies` (default 1), `available_copies` (default 1) | `category()` BelongsTo, `borrowers()` HasMany |
| `App\Models\BookBorrower` | `book_borrowers` | `book_id` FK cascade, `borrower_id` FK cascade, `borrow_at` (date), `return_at` (date), unique(`borrower_id`,`book_id`) | `book()` BelongsTo, `borrower()` BelongsTo `User` |
| `App\Models\User` | `users` | `name`, `email`, `password`, plus auth columns | `bookBorrows()` HasMany `BookBorrower` |

Notable details:

- `categories` is created inside the migration file named `create_books_table.php`.
- `BookBorrower` casts `borrow_at` and `return_at` to `date`.
- `User` implements `Laravel\Fortify\Contracts\PasskeyUser` and uses `HasRoles`, `PasskeyAuthenticatable`, `TwoFactorAuthenticatable`, and `Notifiable`. The `#[Hidden]` attribute hides `password`, `two_factor_secret`, `two_factor_recovery_codes`, and `remember_token` from serialization.
- `User::initials()` provides avatar initials for the UI.
- `MustVerifyEmail` is **not** implemented on `User`, even though the `verified` middleware is applied to member and settings routes. Email verification screens therefore render but verification is not enforced at the model level.

## Project structure

```
app/
  Actions/Fortify/        CreateNewUser, ResetUserPassword
  Concerns/               PasswordValidationRules, ProfileValidationRules
  Filament/
    Resources/
      Books/              BookResource + List/Create/View/Edit pages, Schemas, Tables
      BookBorrowers/      BookBorrowerResource + pages, Schemas, Tables
      Categories/         CategoryResource (index only) + Schemas, Tables
    Pages/Dashboard.php
  Http/
    Middleware/           FilamentPanelMiddleware
    Responses/            LogoutResponse
  Livewire/Actions/       Logout
  Models/                 Book, BookBorrower, Category, User
  Policies/               BookPolicy, BookBorrowerPolicy, CategoryPolicy, RolePolicy
  Providers/
    AdminPanelProvider.php   Filament panel: id/path admin, SPA, colours, Shield plugin
    FortifyServiceProvider.php Role-aware login/logout redirects, views, rate limiters
    AppServiceProvider.php   Filament logout binding, production password rules
resources/views/pages/
  auth/                   Login, register, forgot/reset password, verify email, 2FA challenge
  settings/               Profile, security, appearance (⚡ single-file Livewire components)
  book/                   ⚡book.blade.php (catalogue), ⚡borrowed-book.blade.php (loans)
  welcome.blade.php       Landing page
database/migrations/      8 migrations (users/auth, cache, jobs, passkeys, 2FA, permissions, books, book_borrowers)
database/seeders/         DatabaseSeeder — 3 roles and 3 users
tests/                    Unit + Feature suites
```

Files prefixed with `⚡` are Livewire 4 single-file components. The filename maps directly to the component name: `pages/book/⚡book.blade.php` → `pages::book.book`. Preserve the `⚡` character when renaming.

All business logic for borrowing and returning lives inside these Blade single-file components rather than in dedicated service classes.

## Quality tooling

```bash
composer run lint          # pint --parallel (fix)
composer run lint:check    # pint --parallel --test
composer run types:check   # phpstan analyse (level 7)
composer run test          # config:clear + lint:check + types:check + artisan test
composer run ci:check      # full test suite
```

PHPStan analyses `app/`, `bootstrap/app.php`, `config/`, `database/`, and `routes/` at level 7.

```bash
npx prettier --write "resources/views/**/*.blade.php"   # format Blade
```

CI runs on GitHub Actions (`.github/workflows/tests.yml`) for pushes to `main` and all pull requests, using PHP 8.4 and Node 22, running `composer setup` then `composer ci:check`. Dependabot is configured.

### Test coverage

12 Pest test files cover the inherited auth surface: home page, dashboard access, authentication, registration, email verification, password reset, password confirmation, 2FA challenge, profile updates, and the security settings page.

**There are no tests for `Book`, `BookBorrower`, `Category`, any Filament resource, or the Livewire borrow/return flows.** All test infrastructure is inherited from the starter kit.

## Notes and caveats

Things to be aware of before building further:

1. **Self-registration produces a role-less user.** `CreateNewUser` does not assign a role, so users who register through `/register` cannot access `/books` (blocked by `role:book_borrower`) and are redirected to `login` after registering. Assign the `book_borrower` role manually, or add role assignment to `app/Actions/Fortify/CreateNewUser.php`.

2. **Categories can only be created from the Book form.** The `CategoryResource` page registrations for create/edit are commented out (`app/Filament/Resources/Categories/CategoryResource.php`). The book form provides an inline create-category option with automatic slug generation. Uncomment those registrations to expose standalone category CRUD.

3. **No factories or seed data for the domain.** Only `UserFactory` exists. There are no `BookFactory`, `CategoryFactory`, or `BookBorrowerFactory`, and the seeder creates no catalogue data.

4. **`BookInfolist` is empty.** `app/Filament/Resources/Books/Schemas/BookInfolist.php` has an empty `components([...])` array, so the Filament book *view* page renders no content.

5. **`MustVerifyEmail` is commented out** in `app/Models/User.php` while the `verified` middleware remains in use on routes.

6. **`APP_NAME` is still `Laravel`.** The Filament panel sets no `brandName()` or `brandLogo()`, so it falls back to `APP_NAME`. The landing page is branded separately ("Library System" / "Your library, simplified."). Set `APP_NAME` and add branding in `AdminPanelProvider` for a consistent look.

7. **`composer.json` is still named `laravel/livewire-starter-kit`.** Consider updating the `name` and `description` fields to reflect the actual application.

8. **`Book` copy counts are not enforced at the model level.** `available_copies` and `total_copies` constraints exist only as Filament form validation; there is no observer or invariant preventing `available_copies > total_copies` outside the admin UI.

9. **The root `.env` uses MySQL while `.env.example` ships SQLite.** Adjust `.env.example` if you want the two to match.

## License

MIT.
