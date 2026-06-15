<template>
  <div class="max-w-4xl mx-auto mt-10 p-6 bg-white shadow-md rounded-lg">
    <div class="flex justify-between items-center mb-6 border-b pb-4">
      <h2 class="text-3xl font-bold">{{ job.title }}</h2>
      <div class="space-x-3">
        <router-link :to="`/employer/jobs/${job.id}/edit`" class="bg-blue-100 text-blue-700 px-4 py-2 rounded hover:bg-blue-200 font-medium">
          Edit Job
        </router-link>
        <router-link to="/employer/jobs" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300 font-medium">
          &larr; Back to Jobs
        </router-link>
      </div>
    </div>

    <div v-if="loading" class="text-center py-10 text-gray-500">
      Loading job details...
    </div>

    <div v-else class="space-y-6">
      <!-- Badges -->
      <div class="flex flex-wrap gap-3">
        <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
          {{ job.category?.name || 'Uncategorized' }}
        </span>
        <span :class="{
          'bg-green-100 text-green-800': job.status === 'open',
          'bg-red-100 text-red-800': job.status === 'closed',
          'bg-yellow-100 text-yellow-800': job.status === 'draft'
        }" class="px-3 py-1 rounded-full text-sm font-semibold uppercase border">
          {{ job.status }}
        </span>
        <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-semibold capitalize">
          {{ job.work_type }}
        </span>
      </div>

      <!-- Quick Info Grid -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4 bg-gray-50 p-4 rounded border">
        <div>
          <p class="text-xs text-gray-500 uppercase font-bold">Location</p>
          <p class="font-medium">{{ job.location || 'Not specified' }}</p>
        </div>
        <div>
          <p class="text-xs text-gray-500 uppercase font-bold">Salary Range</p>
          <p class="font-medium">{{ job.salary_range || 'Not specified' }}</p>
        </div>
        <div>
          <p class="text-xs text-gray-500 uppercase font-bold">Deadline</p>
          <p class="font-medium">{{ job.application_deadline || 'No deadline' }}</p>
        </div>
        <div>
          <p class="text-xs text-gray-500 uppercase font-bold">Date Posted</p>
          <p class="font-medium">{{ new Date(job.created_at).toLocaleDateString() }}</p>
        </div>
      </div>

      <!-- Main Content -->
      <div>
        <h3 class="text-xl font-bold mb-2 text-gray-800">Job Description</h3>
        <p class="text-gray-700 whitespace-pre-wrap leading-relaxed">{{ job.description }}</p>
      </div>

      <div v-if="job.responsibilities">
        <h3 class="text-xl font-bold mb-2 text-gray-800">Responsibilities</h3>
        <p class="text-gray-700 whitespace-pre-wrap leading-relaxed">{{ job.responsibilities }}</p>
      </div>

      <div v-if="job.benefits">
        <h3 class="text-xl font-bold mb-2 text-gray-800">Benefits</h3>
        <p class="text-gray-700 whitespace-pre-wrap leading-relaxed">{{ job.benefits }}</p>
      </div>

      <!-- Technologies -->
      <div>
        <h3 class="text-xl font-bold mb-3 text-gray-800">Required Technologies</h3>
        <div class="flex flex-wrap gap-2">
          <span v-for="tech in job.technologies" :key="tech.id" class="bg-gray-200 border text-gray-800 px-3 py-1 rounded text-sm font-medium">
            {{ tech.name }}
          </span>
          <span v-if="job.technologies?.length === 0" class="text-gray-500 italic">No specific technologies listed.</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import api from '../../services/api';

const route = useRoute();
const jobId = route.params.id;

const job = ref({});
const loading = ref(true);

onMounted(async () => {
  try {
    const response = await api.get(`/jobs/${jobId}`);
    job.value = response.data;
  } catch (error) {
    console.error('Error fetching job details:', error);
    alert('Could not load job data.');
  } finally {
    loading.value = false;
  }
});
</script>