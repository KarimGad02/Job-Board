<template>
  <div class="jobs-page-container">
    <div class="jobs-page-header">
      <h2>Explore Job Openings</h2>
      <p>Discover your next career move</p>
    </div>

    <!-- Main Sidebar Layout -->
    <div class="jobs-layout">
      <!-- Left Sidebar Filters -->
      <aside class="sidebar-filters">
        <h3>Filters</h3>
        
        <div class="filter-group">
          <label>Keywords</label>
          <input 
            v-model="filters.search" 
            @input="debouncedSearch"
            type="text" 
            placeholder="Title, description..." 
          />
        </div>

        <div class="filter-group">
          <label>Location</label>
          <input 
            v-model="filters.location" 
            @input="debouncedSearch"
            type="text" 
            placeholder="City, or Remote..." 
          />
        </div>

        <div class="filter-group">
          <label>Category</label>
          <select v-model="filters.category_id" @change="fetchJobs(1)">
            <option value="">All Categories</option>
            <option v-for="cat in categories" :key="cat.id" :value="cat.id">
              {{ cat.name }}
            </option>
          </select>
        </div>

        <div class="filter-group">
          <label>Work Type</label>
          <select v-model="filters.work_type" @change="fetchJobs(1)">
            <option value="">All Work Types</option>
            <option value="remote">Remote</option>
            <option value="onsite">On-site</option>
            <option value="hybrid">Hybrid</option>
          </select>
        </div>

        <div class="filter-group">
          <label>Sort By</label>
          <select v-model="filters.sort" @change="fetchJobs(1)">
            <option value="latest">Newest First</option>
            <option value="oldest">Oldest First</option>
          </select>
        </div>

        <button @click="resetFilters" class="btn-reset">
          Reset Filters
        </button>
      </aside>

      <!-- Right Main Content Area -->
      <section class="jobs-content">
        <!-- Loading State -->
        <div v-if="loading" class="jobs-loading">
          <div class="spinner"></div>
          <p>Fetching job listings...</p>
        </div>

        <!-- Empty State -->
        <div v-else-if="jobs.length === 0" class="jobs-empty">
          <svg class="empty-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <h3>No jobs found</h3>
          <p>Try adjusting your search criteria.</p>
        </div>

        <!-- Jobs Grid -->
        <div v-else>
          <div class="jobs-grid">
            <div 
              v-for="job in jobs" 
              :key="job.id" 
              class="job-card"
            >
              <div class="card-header">
                <img 
                  v-if="job.company_logo" 
                  :src="`http://127.0.0.1:8000/storage/${job.company_logo}`" 
                  alt="Company Logo" 
                  class="company-logo"
                />
                <div class="company-info">
                  <h4>{{ job.title }}</h4>
                  <p class="company-name">{{ job.employer?.name || 'Company Name' }}</p>
                </div>
              </div>

              <div class="card-badges">
                <span class="badge badge-category">{{ job.category?.name || 'Uncategorized' }}</span>
                <span class="badge badge-worktype">{{ job.work_type }}</span>
                <span class="badge badge-location">{{ job.location || 'Remote' }}</span>
              </div>

              <p class="card-description">{{ job.description }}</p>

              <div class="card-footer">
                <div class="salary-box">
                  <span class="salary-label">Salary</span>
                  <span class="salary-value">{{ job.salary_range || 'Negotiable' }}</span>
                </div>
                <router-link :to="`/jobs/${job.id}`" class="btn-details">
                  View Details
                </router-link>
              </div>
            </div>
          </div>

          <!-- Pagination -->
          <div class="jobs-pagination">
            <span class="pagination-info">
              Showing page {{ pagination.current_page }} of {{ pagination.last_page }} (Total {{ pagination.total }} jobs)
            </span>
            <div class="pagination-buttons">
              <button 
                @click="fetchJobs(pagination.current_page - 1)" 
                :disabled="pagination.current_page === 1"
                class="btn-page"
              >
                Previous
              </button>
              
              <button 
                v-for="page in pagesArray" 
                :key="page"
                @click="fetchJobs(page)"
                :class="{ active: page === pagination.current_page }"
                class="btn-page"
              >
                {{ page }}
              </button>

              <button 
                @click="fetchJobs(pagination.current_page + 1)" 
                :disabled="pagination.current_page === pagination.last_page"
                class="btn-page"
              >
                Next
              </button>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import api from '../../services/api';

const jobs = ref([]);
const categories = ref([]);
const loading = ref(true);

const filters = ref({
  search: '',
  location: '',
  category_id: '',
  work_type: '',
  sort: 'latest'
});

const pagination = ref({
  current_page: 1,
  last_page: 1,
  total: 0
});

// Debounce helper for live keyword search
let searchTimeout;
const debouncedSearch = () => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    fetchJobs(1);
  }, 350);
};

const fetchCategories = async () => {
  try {
    const res = await api.get('/categories');
    categories.value = res.data;
  } catch (error) {
    console.error('Error fetching categories:', error);
  }
};

const fetchJobs = async (page = 1) => {
  loading.value = true;
  try {
    const params = {
      page,
      search: filters.value.search,
      location: filters.value.location,
      category_id: filters.value.category_id,
      work_type: filters.value.work_type,
      sort: filters.value.sort
    };
    const response = await api.get('/jobs', { params });
    
    // Store jobs and pagination metadata
    jobs.value = response.data.data;
    pagination.value = {
      current_page: response.data.current_page,
      last_page: response.data.last_page,
      total: response.data.total
    };
  } catch (error) {
    console.error('Error fetching jobs:', error);
  } finally {
    loading.value = false;
  }
};

const resetFilters = () => {
  filters.value = {
    search: '',
    location: '',
    category_id: '',
    work_type: '',
    sort: 'latest'
  };
  fetchJobs(1);
};

// Generates the page numbers list to render in pagination
const pagesArray = computed(() => {
  const arr = [];
  for (let i = 1; i <= pagination.value.last_page; i++) {
    arr.push(i);
  }
  return arr;
});

onMounted(() => {
  fetchCategories();
  fetchJobs(1);
});
</script>
