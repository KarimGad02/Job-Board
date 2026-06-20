# Laravel + Vue Job Board

This repository contains the source code for a Job Board platform built with a Laravel (Sanctum) backend and a Vue 3 (Vite + Axios) frontend.

## 🚀 Quick Setup

We have included an automated setup script to easily initialize the project on Windows machines.

1. Clone the repository.
2. Double-click the `setup.bat` file in the root directory (or run it via terminal).
3. The script will install all dependencies for both backend and frontend, create your `.env` files, and run the database migrations and seeders.
4. *Important: Ensure your local database matches the credentials generated in `backend/.env` before the migrations step.*

**To run the application manually:**
Open two separate terminals:
- **Terminal 1 (Backend):** `cd backend && php artisan serve` (Runs on `http://127.0.0.1:8000`)
- **Terminal 2 (Frontend):** `cd frontend && npm run dev`

---

## 👥 Team Task Distribution & Future Work

Below is the planned distribution of tasks and the current status of features to be implemented across the team:

### 1. Auth & Roles — @Xero (Scaffolded)
**Backend:**
- [x] Laravel authentication setup (Sanctum)
- [x] Registration & login APIs
- [x] Role system (Admin / Employer / Candidate)
- [x] Authorization middleware (`role` middleware)
- [x] Profile management APIs
- [x] Route protection based on roles

**Frontend:**
- [x] Login page
- [x] Registration page
- [x] Profile page
- [x] Edit profile page

### 2. Jobs & Taxonomy — @khaled_waleed_ali (Future Work)
**Backend:**
- [x] Categories table and APIs
- [x] Technologies table and APIs
- [x] Jobs table and model
- [x] Job CRUD APIs
- [x] Employer dashboard APIs
- [x] Job details endpoint

**Frontend:**
- [x] Create Job page
- [x] Edit Job page
- [x] Employer Jobs Management page
- [x] Employer Dashboard
- [x] Job Details page (Employer view)

### 3. Admin, Payments & Platform Ops — @Shaker (Completed)
**Backend:**
- [x] Admin dashboard APIs
- [x] Job approval/rejection workflow
- [x] Job status management
- [x] Payment integration (Stripe/PayPal)
- [x] Analytics endpoints
- [x] Comment moderation APIs

**Frontend:**
- [x] Admin Dashboard
- [x] Job Approval Queue
- [x] Admin Job Management pages
- [x] Payment pages
- [x] Analytics views

### 4. Applications & Resumes — @ziad (Completed)
**Backend:**
- [x] Application APIs
- [x] Apply to Job endpoint
- [x] Cancel Application endpoint
- [x] Resume upload handling
- [x] Application status management
- [x] Accept/Reject candidate endpoints

**Frontend:**
- [x] Candidate Dashboard
- [x] Applications page
- [x] Resume upload UI
- [x] Apply button and application forms
- [x] Application status tracking pages

### 5. Public Job Board & Search — @MahmoudAli (Completed)
**Backend:**
- [x] Search APIs
- [x] Filtering & Pagination logic
- [x] Sorting logic
- [x] Comments APIs

**Frontend:**
- [x] Public Home Page
- [x] Job Listing page
- [x] Public Job Details page
- [x] Search, Filters, Pagination, and Sorting UI
- [x] Comments section
- [x] Responsive/mobile design & Candidate-facing UX improvements
