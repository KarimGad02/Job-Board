<template>
  <div class="auth-page-container">
    <div class="auth-card">
      <h2>Login</h2>
      <form @submit.prevent="submit" class="auth-form">
        <div class="form-group">
          <label>Email</label>
          <input v-model="email" placeholder="Enter your email" type="email" required />
        </div>
        <div class="form-group">
          <label>Password</label>
          <input v-model="password" type="password" placeholder="Enter your password" required />
        </div>
        <button type="submit">Login</button>
      </form>
      <p><router-link to="/register">Don't have an account? Register here.</router-link></p>
      <div v-if="error" class="error">{{ error }}</div>
    </div>
  </div>
</template>

<script>
import api from '../services/api'
import { loginUser } from '../services/auth'
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
        loginUser(res.data.token, res.data.user)
        router.push('/profile')
      } catch (e) {
        error.value = e.response?.data?.message || 'Login failed'
      }
    }

    return { email, password, submit, error }
  }
}
</script>


