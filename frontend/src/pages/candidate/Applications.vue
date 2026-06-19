<template>
  <div class="max-w-6xl mx-auto mt-10 p-6 bg-white shadow-md rounded-lg">
    <div class="flex justify-between items-center mb-6 border-b pb-4">
      <h2 class="text-3xl font-bold">My Applications</h2>
    </div>

    <div v-if="loading" class="text-center py-10 text-gray-500">
      Loading applications...
    </div>

    <div v-else-if="applications.length === 0" class="text-center py-10 text-gray-500">
      You have not applied to any jobs yet.
    </div>

    <div v-else class="overflow-x-auto">
      <table class="min-w-full bg-white border">
        <thead>
          <tr class="bg-gray-100 border-b">
            <th class="py-3 px-4 text-left font-semibold text-gray-600">Job Title</th>
            <th class="py-3 px-4 text-left font-semibold text-gray-600">Employer</th>
            <th class="py-3 px-4 text-left font-semibold text-gray-600">Applied On</th>
            <th class="py-3 px-4 text-left font-semibold text-gray-600">Status</th>
            <th class="py-3 px-4 text-left font-semibold text-gray-600">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="app in applications" :key="app.id" class="border-b hover:bg-gray-50">
            <td class="py-3 px-4">
              <router-link :to="`/jobs/${app.job.id}`" class="text-blue-600 hover:underline font-medium">
                {{ app.job.title }}
              </router-link>
            </td>
            <td class="py-3 px-4 text-gray-700">
              {{ app.job.employer?.name || 'Unknown' }}
            </td>
            <td class="py-3 px-4 text-gray-700">
              {{ new Date(app.created_at).toLocaleDateString() }}
            </td>
            <td class="py-3 px-4">
              <span :class="{
                'bg-yellow-100 text-yellow-800': app.status === 'pending',
                'bg-green-100 text-green-800': app.status === 'accepted',
                'bg-red-100 text-red-800': app.status === 'rejected'
              }" class="px-3 py-1 rounded-full text-xs font-semibold uppercase border">
                {{ app.status }}
              </span>
            </td>
            <td class="py-3 px-4">
              <button 
                v-if="app.status === 'pending'"
                @click="cancelApplication(app.id)" 
                class="text-red-600 hover:text-red-800 font-medium text-sm border border-red-600 hover:bg-red-50 px-3 py-1 rounded transition"
              >
                Cancel
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../../services/api';

const applications = ref([]);
const loading = ref(true);

const fetchApplications = async () => {
  try {
    const response = await api.get('/candidate/applications');
    applications.value = response.data;
  } catch (error) {
    console.error('Error fetching applications:', error);
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchApplications();
});

const cancelApplication = async (id) => {
  if (!confirm('Are you sure you want to cancel this application?')) return;
  
  try {
    await api.delete(`/applications/${id}`);
    // Remove from UI
    applications.value = applications.value.filter(app => app.id !== id);
  } catch (error) {
    console.error('Error canceling application:', error);
    alert('Failed to cancel the application.');
  }
};
</script>
