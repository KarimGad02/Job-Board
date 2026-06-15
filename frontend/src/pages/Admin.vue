<template>
  <div>
    <h2>Admin — User Role Management</h2>
    <router-link to="/profile">Back to Profile</router-link>
    <div v-if="loading">Loading users...</div>
    <div v-else>
      <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <thead>
          <tr style="background-color: #f0f0f0; border: 1px solid #ccc;">
            <th style="padding: 10px; border: 1px solid #ccc; text-align: left;">Name</th>
            <th style="padding: 10px; border: 1px solid #ccc; text-align: left;">Email</th>
            <th style="padding: 10px; border: 1px solid #ccc; text-align: left;">Roles</th>
            <th style="padding: 10px; border: 1px solid #ccc; text-align: left;">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="u in users" :key="u.id" style="border: 1px solid #ccc;">
            <td style="padding: 10px; border: 1px solid #ccc;">{{ u.name }}</td>
            <td style="padding: 10px; border: 1px solid #ccc;">{{ u.email }}</td>
            <td style="padding: 10px; border: 1px solid #ccc;">{{ u.roles.map(r => r.name).join(', ') }}</td>
            <td style="padding: 10px; border: 1px solid #ccc;">
              <button @click="edit(u)">Edit Roles</button>
            </td>
          </tr>
        </tbody>
      </table>

      <div v-if="editing" style="margin-top: 30px; padding: 20px; background-color: #f9f9f9; border: 1px solid #ddd;">
        <h3>Edit Roles for {{ editing.name }}</h3>
        <div v-for="r in allRoles" :key="r" style="margin: 10px 0;">
          <label>
            <input type="checkbox" :value="r" v-model="selectedRoles" /> {{ r }}
          </label>
        </div>
        <button @click="save" style="margin-right: 10px;">Save</button>
        <button @click="cancel">Cancel</button>
        <div v-if="message" style="margin-top: 10px; color: green;">{{ message }}</div>
      </div>
    </div>
  </div>
</template>

<script>
import api from '../services/api'
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

export default {
  setup() {
    const users = ref([])
    const loading = ref(true)
    const editing = ref(null)
    const allRoles = ['admin','employer','candidate']
    const selectedRoles = ref([])
    const message = ref(null)
    const router = useRouter()

    async function load() {
      loading.value = true
      try {
        const res = await api.get('/admin/users')
        users.value = res.data
      } catch (e) {
        console.error(e)
        if (e.response?.status === 403) {
          alert('You do not have permission to access this page.')
          router.push('/profile')
        }
      } finally {
        loading.value = false
      }
    }

    function edit(u) {
      editing.value = u
      selectedRoles.value = u.roles.map(r => r.name)
      message.value = null
    }

    function cancel() {
      editing.value = null
      selectedRoles.value = []
    }

    async function save() {
      if (!editing.value) return
      try {
        const res = await api.put(`/admin/users/${editing.value.id}/roles`, { roles: selectedRoles.value })
        message.value = 'Saved successfully!'
        setTimeout(async () => {
          await load()
          editing.value = null
        }, 1000)
      } catch (e) {
        message.value = 'Save failed: ' + (e.response?.data?.message || e.message)
      }
    }

    onMounted(load)

    return { users, loading, editing, allRoles, selectedRoles, edit, cancel, save, message }
  }
}
</script>

<style scoped>
button { padding: 8px 15px; }
</style>
