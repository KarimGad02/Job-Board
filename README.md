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

### 3. Admin, Payments & Platform Ops — @Shaker (Future Work)
**Backend:**
- [ ] Admin dashboard APIs
- [ ] Job approval/rejection workflow
- [ ] Job status management
- [ ] Payment integration (Stripe/PayPal)
- [ ] Analytics endpoints
- [ ] Comment moderation APIs

**Frontend:**
- [ ] Admin Dashboard
- [ ] Job Approval Queue
- [ ] Admin Job Management pages
- [ ] Payment pages
- [ ] Analytics views

### 4. Applications & Resumes — @ziad (Future Work)
**Backend:**
- [ ] Application APIs
- [ ] Apply to Job endpoint
- [ ] Cancel Application endpoint
- [ ] Resume upload handling
- [ ] Application status management
- [ ] Accept/Reject candidate endpoints

**Frontend:**
- [ ] Candidate Dashboard
- [ ] Applications page
- [ ] Resume upload UI
- [ ] Apply button and application forms
- [ ] Application status tracking pages

### 5. Public Job Board & Search — @MahmoudAli (Future Work)
**Backend:**
- [ ] Search APIs
- [ ] Filtering & Pagination logic
- [ ] Sorting logic
- [ ] Comments APIs

**Frontend:**
- [ ] Public Home Page
- [ ] Job Listing page
- [ ] Public Job Details page
- [ ] Search, Filters, Pagination, and Sorting UI
- [ ] Comments section
- [ ] Responsive/mobile design & Candidate-facing UX improvements
