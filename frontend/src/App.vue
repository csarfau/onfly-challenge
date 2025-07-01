<script setup>
import { RouterLink, RouterView } from 'vue-router'
import { useAuth } from '@/stores/auth.js'

const auth = useAuth()
</script>

<template>
  <header class="bg-gray-800 text-gray-50 p-4 shadow-md">
    <div class="container mx-auto flex flex-col sm:flex-row justify-between items-center flex-wrap gap-4">
      <h1 class="text-3xl font-bold text-blue-400">Travel Flow</h1>
      <nav class="flex flex-col sm:flex-row items-center gap-4 sm:gap-6 w-full sm:w-auto">
        <template v-if="auth.isAuthenticated">
          <RouterLink :to="{ name: 'dashboard' }"
            class="text-gray-50 hover:text-blue-300 transition-colors duration-200 ease-in-out px-2 py-1 rounded"
            active-class="font-semibold text-blue-300 bg-gray-700">
            Dashboard
          </RouterLink>
          <RouterLink :to="{ name: 'create' }"
            class="text-gray-50 hover:text-blue-300 transition-colors duration-200 ease-in-out px-2 py-1 rounded"
            active-class="font-semibold text-blue-300 bg-gray-700">
            New Travel Request
          </RouterLink>
          <button @click="auth.logout()"
            class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition-colors duration-200 ease-in-out text-base">
            Logout
          </button>
        </template>

        <template v-else>
          <RouterLink :to="{ name: 'login' }"
            class="text-gray-50 hover:text-blue-300 transition-colors duration-200 ease-in-out px-2 py-1 rounded"
            active-class="font-semibold text-blue-300 bg-gray-700">
            Login
          </RouterLink>
          <RouterLink :to="{ name: 'register' }"
            class="text-gray-50 hover:text-blue-300 transition-colors duration-200 ease-in-out px-2 py-1 rounded"
            active-class="font-semibold text-blue-300 bg-gray-700">
            Register
          </RouterLink>
        </template>
      </nav>
    </div>
  </header>

  <main class="py-8 bg-gray-50 min-h-[calc(100vh-6rem)]">
    <div class="container mx-auto px-4">
      <RouterView />
    </div>
  </main>
</template>
