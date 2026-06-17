<template>
  <div class="container">
    <h1>Job Management</h1>

    <div v-if="loading">Loading jobs...</div>

    <div v-else-if="error" class="error">
      {{ error }}
    </div>

    <table v-else class="jobs-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Title</th>
          <th>Location</th>
          <th>Work Type</th>
          <th>Salary Range</th>
          <th>Current Status</th>
          <th>Change Status</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="job in jobs" :key="job.id">
          <td>{{ job.id }}</td>
          <td>{{ job.title }}</td>
          <td>{{ job.location }}</td>
          <td>{{ job.work_type }}</td>
          <td>{{ job.salary_range }}</td>
          <td>
            <span :class="'status-' + job.status">
              {{ job.status }}
            </span>
          </td>

          <td>
            <select
              v-model="job.status"
              @change="updateStatus(job)"
            >
              <option value="draft">Draft</option>
              <option value="open">Open</option>
              <option value="closed">Closed</option>
            </select>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import axios from 'axios';
import api from '../services/api'

export default {
  name: "AdminJobs",

  data() {
    return {
      jobs: [],
      loading: true,
      error: null,
    };
  },

  async mounted() {
    await this.loadJobs();
  },

  methods: {
    async loadJobs() {
      try {
        const response = await api.get('/admin/jobs')
        this.jobs = response.data;

      } catch (err) {
        this.error =
          err.response?.data?.message ||
          "Failed to load jobs.";
      } finally {
        this.loading = false;
      }
    },

    async updateStatus(job) {
      try {
        const res = await api.put(`/admin/job/${job.id}`, { status: job.status })
        // alert("Status updated successfully.");
      } catch (err) {
        alert(
          err.response?.data?.message ||
          "Failed to update status."
        );

        await this.loadJobs();
      }
    },
  },
};
</script>

<style scoped>
.container {
  padding: 24px;
}

.jobs-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

.jobs-table th,
.jobs-table td {
  border: 1px solid #ddd;
  padding: 12px;
  text-align: left;
}

.jobs-table th {
  background-color: #f4f4f4;
}

select {
  padding: 6px;
  width: 120px;
}

.status-open {
  color: green;
  font-weight: bold;
}

.status-draft {
  color: orange;
  font-weight: bold;
}

.status-closed {
  color: red;
  font-weight: bold;
}

.error {
  color: red;
  margin-top: 20px;
}
</style>