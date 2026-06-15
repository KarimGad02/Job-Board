Backend scaffold notes

- Add `Laravel\Sanctum` to `providers` in `config/app.php` if needed.
- Register `App\Http\Middleware\CheckRole` in `app/Http/Kernel.php` as 'role'.
- Run `php artisan migrate` and `php artisan db:seed --class=RoleSeeder` to create roles.
- The `AuthController` uses personal access tokens; for SPA cookie-based sanctum, replace token flow with session-based login.
