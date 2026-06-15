Laravel + Vue Job Board — Auth & Roles scaffold

This workspace contains scaffolding for the Auth & Roles feature (Laravel backend + Vue 3 frontend using Vite).

Backend: uses Laravel Sanctum for API tokens (personal access tokens). Frontend: Vue 3 + Vite + Axios.

Quick start (after installing PHP, Composer, Node):

Install backend deps and run migrations:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed --class=RoleSeeder
```

Install frontend deps and run dev server:

```bash
cd frontend
npm install
npm run dev
```

Notes:
- This is a scaffold for the Auth & Roles tasks assigned to Xero.
- Review `routes/api.php`, `app/Http/Controllers/AuthController.php`, and `app/Http/Middleware/CheckRole.php`.
