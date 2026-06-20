<template>
  <div class="profile-page-container">
    <div class="profile-card">
      <h2>My Profile</h2>
      <div v-if="user" class="profile-content">
        <div class="profile-avatar">
          {{ user.name ? user.name.charAt(0).toUpperCase() : 'U' }}
        </div>
        <div class="profile-info">
          <div class="info-group">
            <span class="info-label">Full Name</span>
            <span class="info-value">{{ user.name }}</span>
          </div>
          <div class="info-group">
            <span class="info-label">Email Address</span>
            <span class="info-value">{{ user.email }}</span>
          </div>
          <div class="info-group" v-if="user.roles && user.roles.length">
            <span class="info-label">Account Role</span>
            <span class="info-value text-capitalize">{{ user.roles.map(r => r.name).join(', ') }}</span>
          </div>
        </div>
        
        <div class="profile-btn-group">
          <router-link to="/profile/edit" class="btn btn-secondary">Edit Profile</router-link>
          <button @click="logout" class="btn btn-danger">Logout</button>
        </div>
      </div>
      <div v-else class="jobs-loading">
        <div class="spinner"></div>
        <p>Loading profile details...</p>
      </div>
    </div>
  </div>
</template>

<script>
import api from '../services/api'
import { logoutUser } from '../services/auth'
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

export default {
  setup() {
    const user = ref(null)
    const router = useRouter()

    onMounted(async () => {
      const token = localStorage.getItem('api_token')
      if (!token) {
        router.push('/')
        return
      }
      try {
        const res = await api.get('/profile')
        user.value = res.data
      } catch (e) {
        console.error(e)
        router.push('/')
      }
    })

    async function logout() {
      try {
        await api.post('/logout')
      } catch (e) {
        console.error(e)
      }
      logoutUser()
      router.push('/login')
    }

    return { user, logout }
  }
}
</script>
