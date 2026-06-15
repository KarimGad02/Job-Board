<template>
  <div>
    <h2>Register</h2>
    <form @submit.prevent="submit">
      <div>
        <label>Name:</label>
        <input v-model="name" placeholder="name" required />
      </div>
      <div>
        <label>Email:</label>
        <input v-model="email" placeholder="email" type="email" required />
      </div>
      <div>
        <label>Password:</label>
        <input v-model="password" type="password" placeholder="password" required />
      </div>
      <div>
        <label>Role:</label>
        <select v-model="role">
          <option value="candidate">Candidate</option>
          <option value="employer">Employer</option>
        </select>
      </div>
      <button type="submit">Register</button>
    </form>
    <p><router-link to="/">Already have an account? Login here.</router-link></p>
    <div v-if="error" style="color: red; margin-top: 10px;">{{ error }}</div>
  </div>
</template>

<script>
import api, { setAuthToken } from '../services/api'
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
        const token = res.data.token
        setAuthToken(token)
        localStorage.setItem('api_token', token)
        router.push('/profile')
      } catch (e) {
        error.value = e.response?.data?.message || 'Register failed'
      }
    }

    return { name, email, password, role, submit, error }
  }
}
</script>

<style scoped>
form div { margin: 10px 0; }
input, select { padding: 5px; width: 200px; }
button { padding: 8px 15px; }
</style>
