# ATCLSACCOS – Laravel + Jetstream + Livewire + Tailwind

Setup is **complete**. This project has:

- **Laravel 12**
- **Livewire** (v3)
- **Jetstream** (Livewire stack: auth, profile, 2FA, API tokens)
- **Tailwind CSS** (with Vite)

## Run the app

```bash
php artisan serve
```

Then open http://localhost:8000 (register/login, dashboard at `/dashboard`).

## Development

- Frontend: `npm run dev` (with `php artisan serve` in another terminal)
- Database: SQLite at `database/database.sqlite` (`.env` already set)
- Migrations: already run; re-run with `php artisan migrate` if you add new ones
