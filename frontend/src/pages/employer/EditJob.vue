<template>
  <div class="max-w-3xl mx-auto mt-10 p-6 bg-white shadow-md rounded-lg">
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-2xl font-bold">Edit Job Posting</h2>
      <router-link to="/employer/jobs" class="text-gray-500 hover:underline">
        &larr; Back to Jobs
      </router-link>
    </div>

    <div v-if="loading" class="text-center py-4 text-gray-500">
      Loading job details...
    </div>

    <form v-else @submit.prevent="updateJob" class="space-y-4">
      <div>
        <label class="block font-medium">Job Title</label>
        <input v-model="form.title" type="text" required class="w-full border rounded p-2" />
      </div>

      <div>
        <label class="block font-medium">Category</label>
        <select v-model="form.category_id" required class="w-full border rounded p-2">
          <option value="" disabled>Select a Category</option>
          <option v-for="category in categories" :key="category.id" :value="category.id">
            {{ category.name }}
          </option>
        </select>
      </div>

      <div>
        <label class="block font-medium">Description</label>
        <textarea v-model="form.description" required rows="4" class="w-full border rounded p-2"></textarea>
      </div>
      <div>
        <label class="block font-medium">Responsibilities</label>
        <textarea v-model="form.responsibilities" rows="3" class="w-full border rounded p-2"></textarea>
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block font-medium">Work Type</label>
          <select v-model="form.work_type" required class="w-full border rounded p-2">
            <option value="onsite">On-site</option>
            <option value="remote">Remote</option>
            <option value="hybrid">Hybrid</option>
          </select>
        </div>
        <div>
          <label class="block font-medium">Location</label>
          <input v-model="form.location" type="text" class="w-full border rounded p-2" />
        </div>
      </div>

      <div class="grid grid-cols-3 gap-4">
        <div>
          <label class="block font-medium">Salary Range</label>
          <input v-model="form.salary_range" type="text" class="w-full border rounded p-2" />
        </div>
        <div>
          <label class="block font-medium">Benefits</label>
          <input v-model="form.benefits" type="text" class="w-full border rounded p-2" />
        </div>
        <div>
          <label class="block font-medium">Application Deadline</label>
          <input v-model="form.application_deadline" type="date" class="w-full border rounded p-2" />
        </div>
      </div>

      <div>
        <label class="block font-medium mb-2">Required Technologies</label>
        <div class="flex flex-wrap gap-4 border p-3 rounded bg-gray-50">
          <label v-for="tech in technologies" :key="tech.id" class="flex items-center space-x-2">
            <input type="checkbox" :value="tech.id" v-model="form.technologies" />
            <span>{{ tech.name }}</span>
          </label>
        </div>
      </div>

      <div>
        <label class="block font-medium">Job Status</label>
        <select v-model="form.status" required class="w-full border rounded p-2">
          <option value="open">Open</option>
          <option value="draft">Draft</option>
          <option value="closed">Closed</option>
        </select>
      </div>

      <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 w-full font-bold">
        Save Changes
      </button>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '../../services/api';

const route = useRoute();
const router = useRouter();
const jobId = route.params.id;

const loading = ref(true);
const categories = ref([]);
const technologies = ref([]);

const form = ref({
  title: '',
  category_id: '',
  description: '',
  responsibilities: '',
  work_type: 'onsite',
  location: '',
  salary_range: '',
  benefits: '',
  application_deadline: '',
  status: 'open',
  technologies: []
});

onMounted(async () => {
  try {
    // Fetch taxonomy and existing job data simultaneously
    const [catRes, techRes, jobRes] = await Promise.all([
      api.get('/categories'),
      api.get('/technologies'),
      api.get(`/jobs/${jobId}`)
    ]);

    categories.value = catRes.data;
    technologies.value = techRes.data;
    
    // Populate form with existing job data
    const jobData = jobRes.data;
    form.value = {
      title: jobData.title,
      category_id: jobData.category_id,
      description: jobData.description,
      responsibilities: jobData.responsibilities || '',
      work_type: jobData.work_type || 'onsite',
      location: jobData.location || '',
      salary_range: jobData.salary_range || '',
      benefits: jobData.benefits || '',
      application_deadline: jobData.application_deadline || '',
      status: jobData.status,
      // Extract just the IDs from the attached technology objects
      technologies: jobData.technologies.map(t => t.id)
    };
  } catch (error) {
    console.error('Error fetching job details:', error);
    alert('Could not load job data. It may have been deleted.');
    router.push('/employer/jobs');
  } finally {
    loading.value = false;
  }
});

const updateJob = async () => {
  try {
    await api.put(`/jobs/${jobId}`, form.value);
    alert('Job updated successfully!');
    router.push('/employer/jobs');
  } catch (error) {
    console.error('Error updating job:', error);
    alert('Failed to update job.');
  }
};
</script>