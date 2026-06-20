import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import './styles/app.scss'
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
  { path: '/', component: CandidateJobs },
  { path: '/login', component: Login },
  { path: '/register', component: Register },
  { path: '/profile', component: Profile, meta: { requiresAuth: true } },
  { path: '/profile/edit', component: EditProfile, meta: { requiresAuth: true } },
  { path: '/admin', component: Admin, meta: { requiresAuth: true, role: 'admin' } },
  { path: '/payment', component: Payment, meta: { requiresAuth: true } },


  // --- Khaled's Employer Routes ---
  { path: '/employer/dashboard', component: Dashboard, meta: { requiresAuth: true, role: 'employer' } },
  { path: '/employer/jobs', component: JobsManagement, meta: { requiresAuth: true, role: 'employer' } },
  { path: '/employer/jobs/create', component: CreateJob, meta: { requiresAuth: true, role: 'employer' } },
  { path: '/employer/jobs/:id/edit', component: EditJob, meta: { requiresAuth: true, role: 'employer' } },
  { path: '/employer/jobs/:id', component: JobDetails, meta: { requiresAuth: true, role: 'employer' } },
//---------------------------------------------------------------
  { path: '/admin/stats', component: AdminStats, meta: { requiresAuth: true, role: 'admin' } },
  { path: '/admin/jobs', component: AdminJobs, meta: { requiresAuth: true, role: 'admin' } },
  { path: '/admin/comments', component: AdminComment, meta: { requiresAuth: true, role: 'admin' } },

  // --- Candidate Application Routes ---
  { path: '/jobs', component: CandidateJobs },
  { path: '/jobs/:id', component: CandidateJobDetails },
  { path: '/candidate/applications', component: CandidateApplications, meta: { requiresAuth: true, role: 'candidate' } },

  // --- Employer Application Route ---
  { path: '/employer/jobs/:id/applications', component: JobApplications, meta: { requiresAuth: true, role: 'employer' } },
  { path: '/stats', redirect: '/admin/stats' },
  { path: '/:pathMatch(.*)*', redirect: '/' }
]

const router = createRouter({ history: createWebHistory(), routes })

// Navigation Guard for Route Security
router.beforeEach(async (to, from) => {
  const requiresAuth = to.matched.some(record => record.meta.requiresAuth)
  const requiredRole = to.meta.role

  const token = localStorage.getItem('api_token')

  if (requiresAuth) {
    if (!token) {
      return { path: '/login' }
    }

    const { authUser, checkAuth } = await import('./services/auth')
    let user = authUser.value
    if (!user) {
      user = await checkAuth()
    }

    if (!user) {
      return { path: '/login' }
    }

    if (requiredRole) {
      const hasRole = user.roles?.some(r => r.name === requiredRole)
      if (!hasRole) {
        alert(`Access Denied: You do not have the "${requiredRole}" role to view this page.`)
        return { path: '/profile' }
      }
    }
  } else {
    // If logged in, don't allow going back to login or register page
    if (token && (to.path === '/login' || to.path === '/register')) {
      return { path: '/profile' }
    }
  }
})

const app = createApp(App)

// auto-set token from localStorage if present
const token = localStorage.getItem('api_token')
if (token) {
  import('./services/api').then(({ setAuthToken }) => setAuthToken(token))
}

app.use(router)
app.mount('#app')