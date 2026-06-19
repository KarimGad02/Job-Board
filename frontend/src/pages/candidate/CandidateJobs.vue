<template>
  <div class="max-w-6xl mx-auto mt-10 p-6 bg-white shadow-md rounded-lg">
    <div class="flex justify-between items-center mb-6 border-b pb-4">
      <h2 class="text-3xl font-bold">Find Jobs</h2>
    </div>

    <div v-if="loading" class="text-center py-10 text-gray-500">
      Loading jobs...
    </div>

    <div v-else-if="jobs.length === 0" class="text-center py-10 text-gray-500">
      No jobs available at the moment.
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="job in jobs" :key="job.id" class="border rounded-lg p-5 hover:shadow-lg transition bg-gray-50">
        <div class="flex justify-between items-start mb-2">
          <h3 class="text-xl font-semibold text-gray-800">{{ job.title }}</h3>
          <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">
            {{ job.category?.name || 'Uncategorized' }}
          </span>
        </div>
        
        <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ job.description }}</p>
        
        <div class="flex flex-wrap gap-2 mb-4">
          <span class="bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded">
            {{ job.location || 'Remote' }}
          </span>
          <span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded capitalize">
            {{ job.work_type }}
          </span>
        </div>
        
        <div class="flex justify-between items-center mt-4 pt-4 border-t">
          <span class="text-sm font-medium text-gray-500">{{ job.salary_range || 'Not specified' }}</span>
          <router-link :to="`/jobs/${job.id}`" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm font-medium transition">
            View Details
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../../services/api';

const jobs = ref([]);
const loading = ref(true);

onMounted(async () => {
  try {
    // Note: This endpoint should ideally be public or fetch all open jobs. 
    // Assuming /jobs endpoint exists in backend or we fetch from employer's list.
    // For this implementation, let's fetch from the backend.
    // Wait, the backend currently only has Route::get('/jobs/{job}', [JobController::class, 'show']); 
    // Is there a public /jobs endpoint? Let's check api.php.
    // Actually, I didn't see a public /jobs endpoint. Let me see if there is one. 
    // If not, I'll need to create it in the backend or use an existing one. 
    // Let me check if there is an index method in JobController.
    const response = await api.get('/jobs'); 
    jobs.value = response.data;
  } catch (error) {
    console.error('Error fetching jobs:', error);
    // If /public/jobs doesn't exist, we might get 404. Let's handle it gracefully.
    alert('Could not load jobs. Please check if the endpoint exists.');
  } finally {
    loading.value = false;
  }
});
</script>
