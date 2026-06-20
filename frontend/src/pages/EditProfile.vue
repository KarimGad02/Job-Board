<template>
  <div class="profile-page-container">
    <div class="profile-card">
      <h2>Edit Profile</h2>
      <form @submit.prevent="submit" class="auth-form">
        <div class="form-group">
          <label>Name</label>
          <input v-model="form.name" placeholder="Enter your full name" required />
        </div>
        <div class="form-group">
          <label>Avatar URL</label>
          <input v-model="form.avatar" placeholder="Paste avatar image link (optional)" />
        </div>
        <div class="profile-btn-group">
          <button type="submit" class="btn btn-primary">Save Changes</button>
          <router-link to="/profile" class="btn btn-secondary">Back to Profile</router-link>
        </div>
      </form>
      <div v-if="message" class="success">{{ message }}</div>
    </div>
  </div>
</template>

<script>
import api from '../services/api'
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

export default {
  setup() {
    const form = ref({ name: '', avatar: '' })
    const message = ref(null)
    const router = useRouter()

    onMounted(async () => {
      const token = localStorage.getItem('api_token')
      if (!token) {
        router.push('/')
        return
      }
      try {
        const res = await api.get('/profile')
        form.value.name = res.data.name
        form.value.avatar = res.data.avatar || ''
      } catch (e) {
        console.error(e)
        router.push('/')
      }
    })

    async function submit() {
      message.value = null
      try {
        const res = await api.put('/profile', form.value)
        message.value = 'Profile saved successfully!'
        setTimeout(() => router.push('/profile'), 1500)
      } catch (e) {
        message.value = 'Save failed: ' + (e.response?.data?.message || e.message)
      }
    }

    return { form, submit, message }
  }
}
</script>
