<template>
  <div class="container">
    <h1>Comments</h1>

    <div v-if="loading">Loading comments...</div>

    <div v-else-if="error" class="error">
      {{ error }}
    </div>

    <table v-else class="jobs-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Text</th>
          <th>Current Status</th>
          <th>Change Status</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="comment in comments" :key="comment.id">
          <td>{{ comment.id }}</td>
          <td>{{ comment.text }}</td>
          <td>
            <span :class="'status-' + comment.status">
              {{ comment.status }}
            </span>
          </td>

          <td>
            <select
              v-model="comment.status"
              @change="updateStatus(comment)"
            >
              <option value="active">active</option>
              <option value="hidden">hidden</option>
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
  name: "AdminComments",

  data() {
    return {
      comments: [],
      loading: true,
      error: null,
    };
  },

  async mounted() {
    await this.loadComments();
  },

  methods: {
    async loadComments() {
      try {
        const response = await api.get('/admin/comments')
        console.log(response.data)
        this.comments = response.data;

      } catch (err) {
        this.error =
          err.response?.data?.message ||
          "Failed to load comments.";
      } finally {
        this.loading = false;
      }
    },

    async updateStatus(comment) {
      try {
        const res = await api.put(`/admin/comment/${comment.id}`, { status: comment.status })
        // alert("Status updated successfully.");
      } catch (err) {
        alert(
          err.response?.data?.message ||
          "Failed to update status."
        );

        await this.loadComments();
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

.status-active {
  color: green;
  font-weight: bold;
}


.status-hidden {
  color: red;
  font-weight: bold;
}

.error {
  color: red;
  margin-top: 20px;
}
</style>