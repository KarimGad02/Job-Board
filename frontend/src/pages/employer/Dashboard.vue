<template>
  <div class="max-w-6xl mx-auto mt-10 p-6">
    <h2 class="text-3xl font-bold mb-6">Employer Dashboard</h2>

    <div v-if="loading" class="text-gray-500 text-center py-10">
      Loading your dashboard...
    </div>

    <div v-else>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-blue-500">
          <h3 class="text-gray-500 text-sm font-bold uppercase tracking-wider">Total Postings</h3>
          <p class="text-4xl font-bold mt-2">{{ totalJobs }}</p>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-green-500">
          <h3 class="text-gray-500 text-sm font-bold uppercase tracking-wider">Active (Open)</h3>
          <p class="text-4xl font-bold mt-2 text-green-600">{{ openJobs }}</p>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-gray-400">
          <h3 class="text-gray-500 text-sm font-bold uppercase tracking-wider">Closed / Drafts</h3>
          <p class="text-4xl font-bold mt-2 text-gray-600">{{ inactiveJobs }}</p>
        </div>
      </div>

      <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-xl font-bold mb-4 border-b pb-2">Quick Actions</h3>
        <div class="flex space-x-4 mb-6">
          <router-link to="/employer/jobs/create" class="bg-blue-600 text-white px-5 py-2 rounded font-medium hover:bg-blue-700 transition">
            + Post a New Job
          </router-link>
          <router-link to="/employer/jobs" class="bg-gray-100 text-gray-800 px-5 py-2 rounded font-medium hover:bg-gray-200 transition border">
            Manage Existing Jobs
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import api from '../../services/api';

const jobs = ref([]);
const loading = ref(true);

// Fetch the employer's jobs to calculate stats
onMounted(async () => {
  try {
    const response = await api.get('/employer/jobs');
    jobs.value = response.data;
  } catch (error) {
    console.error('Error fetching dashboard data:', error);
  } finally {
    loading.value = false;
  }
});

// Calculate statistics based on fetched jobs
const totalJobs = computed(() => jobs.value.length);
const openJobs = computed(() => jobs.value.filter(job => job.status === 'open').length);
const inactiveJobs = computed(() => jobs.value.filter(job => job.status !== 'open').length);
</script>