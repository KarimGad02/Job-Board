<template>
  <div class="max-w-6xl mx-auto mt-10 p-6 bg-white shadow-md rounded-lg">
    <div class="flex justify-between items-center mb-6 border-b pb-4">
      <h2 class="text-3xl font-bold">Applications for Job #{{ jobId }}</h2>
      <router-link to="/employer/jobs" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300 font-medium">
        &larr; Back to Jobs
      </router-link>
    </div>

    <div v-if="loading" class="text-center py-10 text-gray-500">
      Loading applications...
    </div>

    <div v-else-if="applications.length === 0" class="text-center py-10 text-gray-500">
      No applications received for this job yet.
    </div>

    <div v-else class="overflow-x-auto">
      <table class="min-w-full bg-white border">
        <thead>
          <tr class="bg-gray-100 border-b">
            <th class="py-3 px-4 text-left font-semibold text-gray-600">Candidate Name</th>
            <th class="py-3 px-4 text-left font-semibold text-gray-600">Email</th>
            <th class="py-3 px-4 text-left font-semibold text-gray-600">Phone</th>
            <th class="py-3 px-4 text-left font-semibold text-gray-600">Resume</th>
            <th class="py-3 px-4 text-left font-semibold text-gray-600">Status</th>
            <th class="py-3 px-4 text-left font-semibold text-gray-600">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="app in applications" :key="app.id" class="border-b hover:bg-gray-50">
            <td class="py-3 px-4 font-medium text-gray-800">
              {{ app.candidate?.name || 'Unknown' }}
            </td>
            <td class="py-3 px-4 text-gray-700">{{ app.email }}</td>
            <td class="py-3 px-4 text-gray-700">{{ app.phone }}</td>
            <td class="py-3 px-4">
              <a 
                v-if="app.resume_path" 
                :href="getResumeUrl(app.resume_path)" 
                target="_blank" 
                class="text-blue-600 hover:underline flex items-center gap-1"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                </svg>
                View
              </a>
              <span v-else class="text-gray-400 text-sm">No Resume</span>
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
              <div class="flex gap-2" v-if="app.status === 'pending'">
                <button 
                  @click="updateStatus(app.id, 'accepted')" 
                  class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm font-medium transition"
                >
                  Accept
                </button>
                <button 
                  @click="updateStatus(app.id, 'rejected')" 
                  class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm font-medium transition"
                >
                  Reject
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import api from '../../services/api';

const route = useRoute();
const jobId = route.params.id;

const applications = ref([]);
const loading = ref(true);

const fetchApplications = async () => {
  try {
    const response = await api.get(`/employer/jobs/${jobId}/applications`);
    applications.value = response.data;
  } catch (error) {
    console.error('Error fetching applications:', error);
    alert('Failed to load applications.');
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchApplications();
});

const getResumeUrl = (path) => {
  // Replace this with actual base URL if it differs
  return `http://127.0.0.1:8000/storage/${path}`;
};

const updateStatus = async (id, status) => {
  if (!confirm(`Are you sure you want to mark this application as ${status}?`)) return;
  
  try {
    const response = await api.put(`/applications/${id}/status`, { status });
    // Update local state
    const index = applications.value.findIndex(app => app.id === id);
    if (index !== -1) {
      applications.value[index].status = status;
    }
  } catch (error) {
    console.error(`Error updating status to ${status}:`, error);
    alert('Failed to update status.');
  }
};
</script>
