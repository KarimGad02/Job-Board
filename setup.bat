@echo off
echo ===================================================
echo Setting up Laravel + Vue Job Board Project
echo ===================================================

echo.
echo [1/4] Setting up the Backend (Laravel)...
cd backend
call composer install
if not exist .env (
    copy .env.example .env
    echo .env file created from .env.example
)
call php artisan key:generate

echo.
echo [2/4] Running Database Migrations ^& Seeding...
echo ---------------------------------------------------
echo IMPORTANT: Make sure your database connection details
echo are properly configured in backend/.env before continuing!
echo ---------------------------------------------------
pause
call php artisan migrate --seed

echo.
echo [3/4] Setting up the Frontend (Vue)...
cd ../frontend
call npm install

echo.
echo [4/4] Setup Complete!
echo ===================================================
echo To start the project locally, open two terminals:
echo.
echo Terminal 1 (Backend):
echo   cd backend
echo   php artisan serve
echo.
echo Terminal 2 (Frontend):
echo   cd frontend
echo   npm run dev
echo ===================================================
pause
