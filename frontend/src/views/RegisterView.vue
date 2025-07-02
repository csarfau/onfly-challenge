<template>
    <div class="flex flex-col flex-1 min-h-lvh ">
        <NavBar />

        <div class="min-h-screen flex items-center justify-center bg-gray-100 p-4 sm:p-6">
            <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-md text-center md:p-10">
                <h2 class="text-3xl font-extrabold text-gray-800 mb-6">New User</h2>

                <form @submit.prevent="register" class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 text-left mb-2">Name</label>
                        <input type="text" id="name" placeholder="Your name" v-model="user.name" required
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-base transition duration-200 ease-in-out" />
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 text-left mb-2">E-mail</label>
                        <input type="email" id="email" placeholder="Insert a valid email" v-model="user.email" required
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-base transition duration-200 ease-in-out" />
                    </div>

                    <div>
                        <label for="password"
                            class="block text-sm font-medium text-gray-700 text-left mb-2">Password</label>
                        <input type="password" id="password" placeholder="Create a password" v-model="user.password"
                            required
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-base transition duration-200 ease-in-out" />
                    </div>

                    <div>
                        <label for="confirm-password"
                            class="block text-sm font-medium text-gray-700 text-left mb-2">Confirm
                            password</label>
                        <input type="password" id="confirm-password" placeholder="Confirm the password"
                            v-model="user.password_confirmation" required
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-base transition duration-200 ease-in-out" />
                    </div>

                    <button type="submit" :disabled="isLoading"
                        class="w-full flex items-center justify-center gap-2 bg-blue-600 text-white py-3 px-4 rounded-md font-semibold text-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 ease-in-out transform hover:-translate-y-0.5">
                        <svg v-if="isLoading" class="size-4 animate-spin text-white" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        Registrar
                    </button>
                </form>

                <p class="mt-8 text-gray-600 text-sm">
                    Already registered?
                    <RouterLink :to="{ name: 'login' }"
                        class="font-medium text-blue-600 hover:text-blue-500 transition duration-200 ease-in-out">
                        Login here
                    </RouterLink>
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuth } from '../stores/auth.js'
import NavBar from '../components/NavBar.vue'

const auth = useAuth()
const router = useRouter()
const isLoading = ref(false)

const user = reactive({
    name: '',
    email: '',
    password: '',
    password_confirmation: ''
})

async function register() {
    if (user.password !== user.password_confirmation) {
        alert('The passwords don not match!')
        return
    }

    try {
        isLoading.value = true;
        const response = await auth.register(user)

        if (response) {
            router.push({ name: 'dashboard' })
        } else {
            router.push({ name: 'login' })
        }
    } catch (error) {
        console.error('Failed register:', error?.response?.data || error.message)
    } finally {
        isLoading.value = false;
    }
}
</script>
