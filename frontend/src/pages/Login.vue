<template>
  <div>
    <h2>Login</h2>
    <form @submit.prevent="submit">
      <div>
        <label>Email:</label>
        <input v-model="email" placeholder="email" type="email" required />
      </div>
      <div>
        <label>Password:</label>
        <input v-model="password" type="password" placeholder="password" required />
      </div>
      <button type="submit">Login</button>
    </form>
    <p><router-link to="/register">Don't have an account? Register here.</router-link></p>
    <div v-if="error" style="color: red; margin-top: 10px;">{{ error }}</div>
  </div>
</template>

<script>
import api, { setAuthToken } from '../services/api'
import { ref } from 'vue'
import { useRouter } from 'vue-router'

export default {
  setup() {
    const email = ref('')
    const password = ref('')
    const error = ref(null)
    const router = useRouter()

    async function submit() {
      error.value = null
      try {
        const res = await api.post('/login', { email: email.value, password: password.value })
        const token = res.data.token
        setAuthToken(token)
        localStorage.setItem('api_token', token)
        router.push('/profile')
      } catch (e) {
        error.value = e.response?.data?.message || 'Login failed'
      }
    }

    return { email, password, submit, error }
  }
}
</script>

<style scoped>
form div { margin: 10px 0; }
input { padding: 5px; width: 200px; }
button { padding: 8px 15px; }
</style>
