<template>
  <div>
    <h2>Edit Profile</h2>
    <form @submit.prevent="submit">
      <div>
        <label>Name:</label>
        <input v-model="form.name" placeholder="name" />
      </div>
      <div>
        <label>Avatar URL:</label>
        <input v-model="form.avatar" placeholder="avatar url" />
      </div>
      <button type="submit">Save</button>
      <router-link to="/profile" style="margin-left: 10px;">Back to Profile</router-link>
    </form>
    <div v-if="message" style="margin-top: 10px; color: green;">{{ message }}</div>
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

<style scoped>
form div { margin: 10px 0; }
input { padding: 5px; width: 300px; }
button { padding: 8px 15px; }
</style>
