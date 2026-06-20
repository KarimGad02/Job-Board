<template>
  <div class="job-details-container">
    <div class="job-details-header">
      <div class="header-left">
        <img v-if="job.company_logo" :src="`http://127.0.0.1:8000/storage/${job.company_logo}`" alt="Company Logo" class="company-logo" />
        <div class="header-text">
          <h2>{{ job.title }}</h2>
          <p class="employer-name">{{ job.employer?.name || 'Company Name' }}</p>
        </div>
      </div>
      <button @click="handleApplyClick" class="btn-apply">
        Apply Now
      </button>
    </div>

    <div v-if="loading" class="jobs-loading">
      <div class="spinner"></div>
      <p>Loading job details...</p>
    </div>

    <div v-else class="job-details-body">
      <!-- Tag Badges -->
      <div class="card-badges mb-4">
        <span class="badge badge-category">{{ job.category?.name || 'Uncategorized' }}</span>
        <span class="badge badge-worktype">{{ job.work_type }}</span>
      </div>

      <!-- Details Meta Grid -->
      <div class="job-details-meta-grid">
        <div class="meta-item">
          <span class="meta-label">Location</span>
          <span class="meta-value">{{ job.location || 'Remote' }}</span>
        </div>
        <div class="meta-item">
          <span class="meta-label">Salary Range</span>
          <span class="meta-value">{{ job.salary_range || 'Not specified' }}</span>
        </div>
        <div class="meta-item">
          <span class="meta-label">Deadline</span>
          <span class="meta-value">{{ job.application_deadline || 'No deadline' }}</span>
        </div>
        <div class="meta-item">
          <span class="meta-label">Date Posted</span>
          <span class="meta-value">{{ new Date(job.created_at).toLocaleDateString() }}</span>
        </div>
      </div>

      <!-- Text Content Sections -->
      <div class="job-details-sections">
        <div class="details-section">
          <h3>Job Description</h3>
          <p class="section-text">{{ job.description }}</p>
        </div>

        <div v-if="job.responsibilities" class="details-section">
          <h3>Responsibilities</h3>
          <p class="section-text">{{ job.responsibilities }}</p>
        </div>

        <div v-if="job.benefits" class="details-section">
          <h3>Benefits</h3>
          <p class="section-text">{{ job.benefits }}</p>
        </div>

        <div v-if="job.skills_and_qualifications" class="details-section">
          <h3>Required Skills & Qualifications</h3>
          <p class="section-text">{{ job.skills_and_qualifications }}</p>
        </div>
      </div>
    </div>

    <!-- Comments Section -->
    <div class="comments-section">
      <h3>Comments</h3>

      <!-- Logged-in Comment Input -->
      <div v-if="isLoggedIn" class="comment-form-container">
        <form @submit.prevent="submitComment" class="comment-form">
          <textarea 
            v-model="newCommentText" 
            placeholder="Ask a question or leave a comment on this job listing..." 
            rows="3" 
            required
          ></textarea>
          <button type="submit" :disabled="postingComment">
            {{ postingComment ? 'Posting...' : 'Post Comment' }}
          </button>
        </form>
      </div>
      <div v-else class="comment-login-prompt">
        Please <router-link to="/login">log in</router-link> to leave a comment on this job listing.
      </div>

      <!-- Comments List -->
      <div v-if="commentsLoading" class="comments-loading">
        Loading comments...
      </div>
      <div v-else-if="comments.length === 0" class="comments-empty-message">
        No comments yet. Be the first to share your thoughts!
      </div>
      <div v-else class="comments-list">
        <div 
          v-for="c in comments" 
          :key="c.id" 
          class="comment-card"
        >
          <div class="comment-card-header">
            <span class="comment-author">{{ c.user?.name || 'Anonymous' }}</span>
            <span class="comment-time">{{ new Date(c.created_at).toLocaleString() }}</span>
          </div>
          <p class="comment-text">{{ c.text }}</p>
        </div>
      </div>
    </div>

    <!-- Apply Modal -->
    <div v-if="showApplyModal" class="modal-overlay">
      <div class="modal-card">
        <button @click="showApplyModal = false" class="modal-close-btn">&times;</button>
        <h3>Apply for {{ job.title }}</h3>
        
        <form @submit.prevent="submitApplication" class="modal-form">
          <div class="form-group">
            <label>Email</label>
            <input v-model="form.email" type="email" required>
          </div>
          <div class="form-group">
            <label>Phone</label>
            <input v-model="form.phone" type="text" required>
          </div>
          <div class="form-group">
            <label>Resume (PDF/DOC)</label>
            <input @change="handleFileUpload" type="file" required accept=".pdf,.doc,.docx">
          </div>
          
          <button type="submit" :disabled="submitting" class="btn-submit-app">
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
import Swal from 'sweetalert2';

const route = useRoute();
const router = useRouter();
const jobId = route.params.id;

const job = ref({});
const loading = ref(true);
const showApplyModal = ref(false);
const submitting = ref(false);

const comments = ref([]);
const commentsLoading = ref(true);
const isLoggedIn = ref(!!localStorage.getItem('api_token'));
const newCommentText = ref('');
const postingComment = ref(false);

const form = ref({
  email: '',
  phone: '',
  resume: null
});

const fetchComments = async () => {
  commentsLoading.value = true;
  try {
    const res = await api.get(`/jobs/${jobId}/comments`);
    comments.value = res.data;
  } catch (error) {
    console.error('Error fetching comments:', error);
  } finally {
    commentsLoading.value = false;
  }
};

const submitComment = async () => {
  postingComment.value = true;
  try {
    const res = await api.post(`/jobs/${jobId}/comments`, { text: newCommentText.value });
    comments.value.unshift(res.data.comment);
    newCommentText.value = '';
  } catch (error) {
    console.error('Error posting comment:', error);
    alert('Failed to post comment.');
  } finally {
    postingComment.value = false;
  }
};

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
  
  fetchComments();
});

const handleApplyClick = async () => {
  const token = localStorage.getItem('api_token');
  if (!token) {
    Swal.fire({
      title: 'Login or Register Required',
      text: 'You must be logged in to apply for jobs. Please log in or create an account first.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Log In',
      cancelButtonText: 'Register',
      confirmButtonColor: '#4A70A9',
      cancelButtonColor: '#8FABD4',
      background: '#EFECE3',
      color: '#000000'
    }).then((result) => {
      if (result.isConfirmed) {
        router.push('/login');
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        router.push('/register');
      }
    });
    return;
  }
  
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
