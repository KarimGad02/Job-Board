import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import App from './App.vue'
import Login from './pages/Login.vue'
import Register from './pages/Register.vue'
import Profile from './pages/Profile.vue'
import EditProfile from './pages/EditProfile.vue'
import Admin from './pages/Admin.vue'

const routes = [
  { path: '/', component: Login },
  { path: '/register', component: Register },
  { path: '/profile', component: Profile },
  { path: '/profile/edit', component: EditProfile },
  { path: '/admin', component: Admin }
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
