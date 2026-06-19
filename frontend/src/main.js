import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import App from './App.vue'
import Login from './pages/Login.vue'
import Register from './pages/Register.vue'
import Profile from './pages/Profile.vue'
import EditProfile from './pages/EditProfile.vue'
import Admin from './pages/Admin.vue'

// --- Khaled's Employer Page Imports ---
import Dashboard from './pages/employer/Dashboard.vue'
import JobsManagement from './pages/employer/JobsManagement.vue'
import CreateJob from './pages/employer/CreateJob.vue'
import EditJob from './pages/employer/EditJob.vue'
import JobDetails from './pages/employer/JobDetails.vue'
//-----------------------------------------------------------------
import AdminStats from './pages/adminstats.vue'
import AdminJobs from './pages/adminJobs.vue'
import AdminComment from './pages/adminComment.vue'
import Payment from './pages/payment.vue'

// --- Candidate Pages Imports ---
import CandidateJobs from './pages/candidate/CandidateJobs.vue'
import CandidateJobDetails from './pages/candidate/CandidateJobDetails.vue'
import CandidateApplications from './pages/candidate/Applications.vue'
import JobApplications from './pages/employer/JobApplications.vue'



const routes = [
  // Existing Routes
  { path: '/', component: Login },
  { path: '/login', component: Login },
  { path: '/register', component: Register },
  { path: '/profile', component: Profile },
  { path: '/profile/edit', component: EditProfile },
  { path: '/admin', component: Admin },
  { path: '/payment', component: Payment },


  // --- Khaled's Employer Routes ---
  { path: '/employer/dashboard', component: Dashboard },
  { path: '/employer/jobs', component: JobsManagement },
  { path: '/employer/jobs/create', component: CreateJob },
  { path: '/employer/jobs/:id/edit', component: EditJob },
  { path: '/employer/jobs/:id', component: JobDetails },
//---------------------------------------------------------------
  { path: '/admin/stats', component: AdminStats },
  { path: '/admin/jobs', component: AdminJobs },
  { path: '/admin/comments', component: AdminComment },

  // --- Candidate Application Routes ---
  { path: '/jobs', component: CandidateJobs },
  { path: '/jobs/:id', component: CandidateJobDetails },
  { path: '/candidate/applications', component: CandidateApplications },

  // --- Employer Application Route ---
  { path: '/employer/jobs/:id/applications', component: JobApplications },



]

const router = createRouter({ history: createWebHistory(), routes })
const app = createApp(App)

// auto-set token from localStorage if present
const token = localStorage.getItem('api_token')
if (token) {
  import('./services/api').then(({ setAuthToken }) => setAuthToken(token))
}

app.use(router)
app.mount('#app')