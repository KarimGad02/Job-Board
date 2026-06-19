<template>
  <div class="max-w-6xl mx-auto mt-10 p-6">
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-3xl font-bold">My Job Postings</h2>
      <router-link to="/employer/jobs/create" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        + Post New Job
      </router-link>
    </div>

    <div v-if="jobs.length === 0" class="text-gray-500 bg-white p-6 rounded shadow">
      You haven't posted any jobs yet. Click the button above to get started.
    </div>

    <div v-else class="bg-white shadow-md rounded-lg overflow-hidden">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="bg-gray-100 border-b">
            <th class="p-4">Title</th>
            <th class="p-4">Category</th>
            <th class="p-4">Status</th>
            <th class="p-4 text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="job in jobs" :key="job.id" class="border-b hover:bg-gray-50">
            <td class="p-4 font-medium">{{ job.title }}</td>
            <td class="p-4">{{ job.category?.name || 'N/A' }}</td>
            <td class="p-4">
              <span :class="{
                'text-green-800 bg-green-100 px-2 py-1 rounded text-sm': job.status === 'open',
                'text-red-800 bg-red-100 px-2 py-1 rounded text-sm': job.status === 'closed',
                'text-yellow-800 bg-yellow-100 px-2 py-1 rounded text-sm': job.status === 'draft'
              }">
                {{ job.status }}
              </span>
            </td>
            <td class="p-4 text-right space-x-4">
              <router-link :to="`/employer/jobs/${job.id}/applications`" class="text-green-600 hover:underline font-medium">
                Applications
              </router-link>
              <router-link :to="`/employer/jobs/${job.id}/edit`" class="text-blue-500 hover:underline">
                Edit
              </router-link>
              <button @click="deleteJob(job.id)" class="text-red-500 hover:underline">
                Delete
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

const jobs = ref([]);

// Fetch the employer's jobs when the component loads
const fetchJobs = async () => {
  try {
    const response = await api.get('/employer/jobs');
    jobs.value = response.data;
  } catch (error) {
    console.error('Error fetching jobs:', error);
  }
};

// Handle job deletion
const deleteJob = async (id) => {
  if (!confirm('Are you sure you want to delete this job posting?')) return;
  
  try {
    await api.delete(`/jobs/${id}`);
    // Remove the deleted job from the UI without reloading the page
    jobs.value = jobs.value.filter(job => job.id !== id);
  } catch (error) {
    console.error('Error deleting job:', error);
    alert('Failed to delete job. Ensure you have the correct permissions.');
  }
};

onMounted(fetchJobs);
</script>