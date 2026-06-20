<template>
  <div class="auth-page-container">
    <div class="auth-card">
      <h2>Register</h2>
      <form @submit.prevent="submit" class="auth-form">
        <div class="form-group">
          <label>Full Name</label>
          <input 
            v-model="name" 
            type="text"
            placeholder="Enter your name" 
            required 
            minlength="2"
            autocomplete="name"
          />
          <small class="form-help">Enter your first and last name</small>
        </div>
        <div class="form-group">
          <label>Email</label>
          <input v-model="email" placeholder="Enter your email" type="email" required />
        </div>
        <div class="form-group">
          <label>Password</label>
          <input v-model="password" type="password" placeholder="Choose a password" required />
        </div>
        <div class="form-group">
          <label>Role</label>
          <select v-model="role">
            <option value="candidate">Candidate</option>
            <option value="employer">Employer</option>
          </select>
        </div>
        <button type="submit">Register</button>
      </form>
      <p><router-link to="/login">Already have an account? Login here.</router-link></p>
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
    const name = ref('')
    const email = ref('')
    const password = ref('')
    const role = ref('candidate')
    const error = ref(null)
    const router = useRouter()

    async function submit() {
      error.value = null
      try {
        const res = await api.post('/register', { name: name.value, email: email.value, password: password.value, role: role.value })
        loginUser(res.data.token, res.data.user)
        router.push('/profile')
      } catch (e) {
        error.value = e.response?.data?.message || 'Register failed'
      }
    }

    return { name, email, password, role, submit, error }
  }
}
</script>


