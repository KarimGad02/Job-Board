<template>
  <div class="max-w-3xl mx-auto mt-10 p-6 bg-white shadow-md rounded-lg">
    <h2 class="text-2xl font-bold mb-6">Post a New Job</h2>

    <form @submit.prevent="submitJob" class="space-y-4">
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
      <div>
        <label class="block font-medium">Required Skills & Qualifications</label>
        <textarea v-model="form.skills_and_qualifications" rows="3" class="w-full border rounded p-2"></textarea>
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
          <input v-model="form.salary_range" type="text" placeholder="e.g. $50k - $70k" class="w-full border rounded p-2" />
        </div>
        <div>
          <label class="block font-medium">Benefits</label>
          <input v-model="form.benefits" type="text" placeholder="e.g. Health insurance, Gym membership, Annual bonus" class="w-full border rounded p-2" />
        </div>
        <div>
          <label class="block font-medium">Application Deadline</label>
          <input v-model="form.application_deadline" type="date" class="w-full border rounded p-2" />
        </div>
      </div>

      <div>
        <label class="block font-medium">Company Logo (Optional)</label>
        <input type="file" @change="handleFileUpload" accept="image/*" class="w-full border rounded p-2" />
      </div>

      <div>
        <label class="block font-medium">Job Status</label>
        <select v-model="form.status" required class="w-full border rounded p-2">
          <option value="open">Open</option>
          <option value="draft">Draft</option>
          <option value="closed">Closed</option>
        </select>
      </div>

      <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        Create Job
      </button>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import api from '../../services/api';

const categories = ref([]);
const router = useRouter();

const form = ref({
  title: '',
  category_id: '',
  description: '',
  responsibilities: '',
  skills_and_qualifications: '',
  work_type: 'onsite',
  location: '',
  salary_range: '',
  benefits: '',
  application_deadline: '',
  status: 'open',
  company_logo: null
});

// Fetch categories when the page loads
onMounted(async () => {
  try {
    const catResponse = await api.get('/categories');
    categories.value = catResponse.data;
  } catch (error) {
    console.error('Error fetching taxonomy data:', error);
  }
});

const handleFileUpload = (event) => {
  form.value.company_logo = event.target.files[0];
};

// Submit the form
const submitJob = async () => {
  try {
    const formData = new FormData();
    for (const key in form.value) {
      if (form.value[key] !== null && form.value[key] !== '') {
        formData.append(key, form.value[key]);
      }
    }

    await api.post('/jobs', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
    alert('Job created successfully!');
    router.push('/employer/jobs');
  } catch (error) {
    console.error('Error creating job:', error.response?.data || error);
    alert('Failed to create job. Check console for details.');
  }
};
</script>