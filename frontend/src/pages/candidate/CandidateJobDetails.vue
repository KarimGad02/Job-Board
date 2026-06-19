<template>
  <div class="max-w-4xl mx-auto mt-10 p-6 bg-white shadow-md rounded-lg relative">
    <div class="flex justify-between items-center mb-6 border-b pb-4">
      <div class="flex items-center gap-4">
        <img v-if="job.company_logo" :src="`http://127.0.0.1:8000/storage/${job.company_logo}`" alt="Company Logo" class="w-16 h-16 object-contain rounded border bg-white" />
        <h2 class="text-3xl font-bold">{{ job.title }}</h2>
      </div>
      <button @click="handleApplyClick" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 font-bold shadow transition">
        Apply Now
      </button>
    </div>

    <div v-if="loading" class="text-center py-10 text-gray-500">
      Loading job details...
    </div>

    <div v-else class="space-y-6">
      <div class="flex flex-wrap gap-3">
        <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
          {{ job.category?.name || 'Uncategorized' }}
        </span>
        <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-semibold capitalize">
          {{ job.work_type }}
        </span>
      </div>

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

      <div v-if="job.skills_and_qualifications">
        <h3 class="text-xl font-bold mb-2 text-gray-800">Required Skills & Qualifications</h3>
        <p class="text-gray-700 whitespace-pre-wrap leading-relaxed">{{ job.skills_and_qualifications }}</p>
      </div>
    </div>

    <!-- Apply Modal -->
    <div v-if="showApplyModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6 relative">
        <button @click="showApplyModal = false" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800">&times;</button>
        <h3 class="text-2xl font-bold mb-4">Apply for {{ job.title }}</h3>
        
        <form @submit.prevent="submitApplication" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input v-model="form.email" type="email" required class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
            <input v-model="form.phone" type="text" required class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Resume (PDF/DOC)</label>
            <input @change="handleFileUpload" type="file" required accept=".pdf,.doc,.docx" class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200">
          </div>
          
          <button type="submit" :disabled="submitting" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 font-bold transition disabled:opacity-50">
            {{ submitting ? 'Submitting...' : 'Submit Application' }}
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '../../services/api';

const route = useRoute();
const router = useRouter();
const jobId = route.params.id;

const job = ref({});
const loading = ref(true);
const showApplyModal = ref(false);
const submitting = ref(false);

const form = ref({
  email: '',
  phone: '',
  resume: null
});

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

const handleApplyClick = async () => {
  const token = localStorage.getItem('api_token');
  if (!token) {
    alert('You must be logged in to apply for this job.');
    router.push('/');
    return;
  }
  
  // Try to fetch profile to prefill
  try {
    api.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    const res = await api.get('/profile');
    if (res.data && res.data.email) {
      form.value.email = res.data.email;
    }
  } catch (error) {
    console.error('Error fetching profile for prefill:', error);
  }

  showApplyModal.value = true;
};

const handleFileUpload = (event) => {
  form.value.resume = event.target.files[0];
};

const submitApplication = async () => {
  submitting.value = true;
  const formData = new FormData();
  formData.append('email', form.value.email);
  formData.append('phone', form.value.phone);
  formData.append('resume', form.value.resume);

  try {
    await api.post(`/jobs/${jobId}/apply`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
    alert('Application submitted successfully!');
    showApplyModal.value = false;
    router.push('/candidate/applications');
  } catch (error) {
    console.error('Error submitting application:', error);
    if (error.response?.data?.message) {
      alert(error.response.data.message);
    } else {
      alert('An error occurred while submitting your application.');
    }
  } finally {
    submitting.value = false;
  }
};
</script>
