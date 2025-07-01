<script setup>
import { RouterLink, RouterView } from 'vue-router'
import { useAuth } from '@/stores/auth.js'

const auth = useAuth()
</script>

<template>
  <header class="app-header">
    <div class="container header-content">
      <h1 class="app-title">Travel Flow</h1>
      <nav class="main-nav">
        <template v-if="auth.isAuthenticated">
          <RouterLink :to="{ name: 'dashboard' }">Dashboard</RouterLink>
          <RouterLink :to="{ name: 'create' }">New Travel Request</RouterLink>
          <button @click="auth.logout()" class="logout-button">Logout</button>
        </template>

        <template v-else>
          <RouterLink :to="{ name: 'login' }">Login</RouterLink>
          <RouterLink :to="{ name: 'register' }">Register</RouterLink>
        </template>
      </nav>
    </div>
  </header>

  <main class="app-main">
    <div class="container">
      <RouterView />
    </div>
  </main>
</template>

<style scoped>
.app-header {
  background-color: #2c3e50;
  color: #ecf0f1;
  padding: 1.5rem 0;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.header-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 1rem;
}

.app-title {
  font-size: 2rem;
  margin: 0;
  font-weight: 700;
}

.main-nav {
  display: flex;
  gap: 1.5rem;
  flex-wrap: wrap;
}

.main-nav a {
  color: #ecf0f1;
  text-decoration: none;
  font-weight: 500;
  transition: color 0.3s ease;
  padding: 0.5rem 0;
}

.main-nav a:hover,
.main-nav a.router-link-exact-active {
  color: #3498db;
}

.logout-button {
  background-color: #e74c3c;
  color: white;
  border: none;
  padding: 0.75rem 1.25rem;
  border-radius: 4px;
  cursor: pointer;
  font-size: 1rem;
  transition: background-color 0.3s ease;
}

.logout-button:hover {
  background-color: #c0392b;
}

.app-main {
  padding: 2rem 0;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;
}

@media (max-width: 768px) {
  .header-content {
    flex-direction: column;
    text-align: center;
  }

  .main-nav {
    flex-direction: column;
    align-items: center;
    width: 100%;
  }

  .app-title {
    margin-bottom: 1rem;
  }
}
</style>
