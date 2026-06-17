<template>
  <div class="container">
    <h1 class="title">Admin Dashboard</h1>

    <div v-if="loading" class="loading">
      Loading statistics...
    </div>

    <div v-else-if="error" class="error">
      {{ error }}
    </div>

    <div v-else class="stats-grid">
      <div class="card">
        <h3>Total Users</h3>
        <p>{{ stats.total_users }}</p>
      </div>

      <div class="card">
        <h3>Total Employers</h3>
        <p>{{ stats.total_employers }}</p>
      </div>

      <div class="card">
        <h3>Total Candidates</h3>
        <p>{{ stats.total_candidates }}</p>
      </div>

      <div class="card">
        <h3>Total Jobs</h3>
        <p>{{ stats.total_jobs }}</p>
      </div>

      <div class="card">
        <h3>Pending Jobs</h3>
        <p>{{ stats.pending_jobs }}</p>
      </div>

      <div class="card">
        <h3>Approved Jobs</h3>
        <p>{{ stats.approved_jobs }}</p>
      </div>

      <div class="card">
        <h3>Rejected Jobs</h3>
        <p>{{ stats.rejected_jobs }}</p>
      </div>

      
    </div>
  </div>
</template>

<script>
import axios from "axios";
import api from '../services/api'

export default {
  name: "AdminStats",

  data() {
    return {
      stats: {},
      loading: true,
      error: null,
    };
  },

  async mounted() {
    try {
      const res = await api.get('/admin/stats')

      this.stats = res.data;
    } catch (err) {
        console.log(err)
      this.error =
        err.res?.data?.message ||
        "Failed to load dashboard statistics.";
    } finally {
      this.loading = false;
    }
  },
};
</script>

<style scoped>
.container {
  padding: 30px;
}

.title {
  margin-bottom: 20px;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
  gap: 20px;
}

.card {
  background: #fff;
  border-radius: 10px;
  padding: 20px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.card h3 {
  margin-bottom: 10px;
  font-size: 16px;
}

.card p {
  font-size: 28px;
  font-weight: bold;
}

.loading,
.error {
  font-size: 18px;
  margin-top: 20px;
}
</style>