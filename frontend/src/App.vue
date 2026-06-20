<template>
  <div id="app">
    <header>
      <router-link to="/" class="brand-link">
        <h1>JobBoard</h1>
      </router-link>
      <nav>
        <router-link to="/">Home / Find Jobs</router-link>
        
        <!-- Guest Links (Only show when not logged in) -->
        <template v-if="!isLoggedIn">
          <router-link to="/login">Login</router-link>
          <router-link to="/register">Register</router-link>
        </template>
        
        <!-- Logged-in User Links -->
        <template v-else>
          <!-- Candidate Links -->
          <router-link v-if="hasRole('candidate')" to="/candidate/applications">My Applications</router-link>
          
          <!-- Employer Links -->
          <router-link v-if="hasRole('employer')" to="/employer/jobs">Employer Jobs</router-link>
          
          <!-- Admin Links -->
          <router-link v-if="hasRole('admin')" to="/admin">Admin Roles</router-link>
          <router-link v-if="hasRole('admin')" to="/admin/stats">Admin Stats</router-link>
          <router-link v-if="hasRole('admin')" to="/admin/jobs">Admin Jobs</router-link>
          <router-link v-if="hasRole('admin')" to="/admin/comments">Admin Comments</router-link>
          
          <!-- Profile Link -->
          <router-link to="/profile">Profile</router-link>
          
          <!-- Logout Button -->
          <a href="#" @click.prevent="handleLogout" class="logout-link">Logout</a>
        </template>
      </nav>
    </header>
    <main>
      <router-view></router-view>
    </main>
    <footer>
      <p>&copy; {{ new Date().getFullYear() }} JobBoard. All rights reserved.</p>
    </footer>
  </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { authUser, isLoggedIn, checkAuth, logoutUser } from './services/auth';

const router = useRouter();

const hasRole = (roleName) => {
  return authUser.value?.roles?.some(r => r.name === roleName) || false;
};

const handleLogout = () => {
  logoutUser();
  router.push('/login');
};

onMounted(() => {
  checkAuth();
});
</script>

<style>
body { margin: 0; }
.logout-link {
  cursor: pointer;
}
</style>
