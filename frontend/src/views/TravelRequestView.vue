<template>
    <NavBar />
    <div class="bg-gray-50 min-h-screen py-10 px-4">
        <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-lg p-8">

            <div v-if="loading" class="text-center py-10 text-gray-500">Carregando detalhes...</div>
            <div v-else-if="error" class="text-center py-10 text-red-500">Erro ao carregar o pedido.</div>

            <div v-else>
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-800">Detalhes da Viagem</h2>
                    <p class="text-gray-500 mt-1">ID do Pedido: {{ travelRequest.id }}</p>
                </div>

                <div class="border-t border-gray-200"></div>

                <div v-if="isAdmin" class="py-6">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Solicitante</h3>
                    <div class="flex items-center gap-4">
                        <span class="bg-gray-100 rounded-full p-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </span>
                        <span class="text-lg font-medium text-gray-800 capitalize">{{ travelRequest.requester_name
                            }}</span>
                    </div>
                </div>

                <div class="py-6 border-t border-gray-200">
                    <div class="space-y-5">
                        <div class="flex items-center gap-4">
                            <span class="bg-gray-100 rounded-full p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </span>
                            <div>
                                <p class="text-sm text-gray-500">Destino</p>
                                <p class="text-lg font-semibold text-gray-800 capitalize">{{ travelRequest.destination
                                }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="bg-gray-100 rounded-full p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </span>
                            <div>
                                <p class="text-sm text-gray-500">Período da Viagem</p>
                                <p class="text-lg font-semibold text-gray-800">{{ travelRequest.departure_date }} → {{
                                    travelRequest.return_date }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="py-6 border-t border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Status</h3>
                    <span :class="statusBadgeClass(travelRequest.status)"
                        class="inline-flex items-center gap-2 px-3 py-1 text-base font-semibold rounded-full">
                        <span class="h-2 w-2 rounded-full" :class="statusDotClass(travelRequest.status)"></span>
                        {{ statusLabel(travelRequest.status) }}
                    </span>
                </div>

                <div v-if="isAdmin && travelRequest.status === 'requested'"
                    class="mt-6 pt-6 border-t border-gray-200 flex flex-col sm:flex-row gap-4">
                    <button @click="approveRequest"
                        class="w-full bg-green-600 text-white font-bold py-3 px-5 rounded-lg hover:bg-green-700 transition-colors duration-200">
                        Aprovar Pedido
                    </button>
                    <button @click="rejectRequest"
                        class="w-full bg-red-600 text-white font-bold py-3 px-5 rounded-lg hover:bg-red-700 transition-colors duration-200">
                        Rejeitar Pedido
                    </button>
                </div>

                <div class="mt-8 text-center">
                    <router-link to="/dashboard"
                        class="font-semibold text-blue-600 hover:text-blue-800 transition-colors">
                        ← Voltar para o Painel
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import NavBar from '../components/NavBar.vue'
import http from '../services/http.js'
import { useAuth } from '../stores/auth'

const auth = useAuth();
const route = useRoute();
const travelRequest = ref({});
const loading = ref(true);
const error = ref(false);

const isAdmin = auth.user.is_admin

const statusLabel = (status) => {
    const labels = {
        requested: 'Pendente',
        approved: 'Aprovado',
        cancelled: 'Rejeitado'
    }
    return labels[status] || 'Pendente';
}

const statusBadgeClass = (status) => {
    const classes = {
        requested: 'bg-yellow-100 text-yellow-800',
        approved: 'bg-green-100 text-green-800',
        cancelled: 'bg-red-100 text-red-800',
    };
    return classes[status] || 'bg-gray-100 text-gray-800';
};

const statusDotClass = (status) => {
    const classes = {
        requested: 'bg-yellow-500',
        approved: 'bg-green-500',
        cancelled: 'bg-red-500',
    };
    return classes[status] || 'bg-gray-500';
};

async function approveRequest() {
    try {
        loading.value = true
        const { data } = await http.patch(`/travel-requests/${route.params.id}`, { status: 'approved' })
        travelRequest.value = data.data
    } catch (error) {
        console.error(error);
    } finally {
        loading.value = false
    }
}

async function rejectRequest() {
    try {
        loading.value = true
        const { data } = await http.patch(`/travel-requests/${route.params.id}`, { status: 'cancelled' })
        travelRequest.value = data.data
    } catch (error) {
        console.error(error);
    } finally {
        loading.value = false
    }
}

async function fetchTravelRequest() {
    loading.value = true
    error.value = false
    try {
        const { data } = await http.get(`/travel-requests/${route.params.id}`)
        travelRequest.value = data
    } catch (e) {
        error.value = true
    } finally {
        loading.value = false
    }
}

onMounted(fetchTravelRequest)
</script>
