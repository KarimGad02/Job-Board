<template>
  <div>
    <h2>Profile</h2>
    <div v-if="user">
      <p><strong>Name:</strong> {{ user.name }}</p>
      <p><strong>Email:</strong> {{ user.email }}</p>
      <p v-if="user.roles && user.roles.length"><strong>Roles:</strong> {{ user.roles.map(r => r.name).join(', ') }}</p>
      <div style="margin-top: 20px;">
        <router-link to="/profile/edit" style="margin-right: 10px;">Edit Profile</router-link>
        <button @click="logout">Logout</button>
      </div>
    </div>
    <div v-else>Loading...</div>
  </div>
</template>

<script>
import api from '../services/api'
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
      if (token) api.defaults.headers.common['Authorization'] = `Bearer ${token}`
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
        localStorage.removeItem('api_token')
        delete api.defaults.headers.common['Authorization']
        router.push('/')
      } catch (e) {
        console.error(e)
      }
    }

    return { user, logout }
  }
}
</script>

<style scoped>
p { margin: 10px 0; }
button { padding: 8px 15px; }
</style>
