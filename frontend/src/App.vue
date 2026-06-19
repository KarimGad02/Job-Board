<template>
  <div id="app" style="padding: 20px; font-family: Arial, sans-serif;">
    <header style="border-bottom: 1px solid #ccc; padding-bottom: 10px; margin-bottom: 20px;">
      <h1>JobBoard</h1>
      <nav>
        <router-link to="/">Login</router-link> | 
        <router-link to="/register">Register</router-link> | 
        <router-link to="/profile">Profile</router-link> | 
        <router-link to="/admin">Admin</router-link> |
        <router-link to="/employer/jobs">Employer Jobs</router-link> |
        <router-link to="/jobs">Find Jobs</router-link> |
        <router-link to="/candidate/applications">My Applications</router-link>
      </nav>
    </header>
    <main>
      <router-view></router-view>
    </main>
  </div>
</template>

<script>
export default {
  name: 'App'
}
</script>

<style>
body { margin: 0; }
</style>
