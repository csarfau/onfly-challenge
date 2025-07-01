<template>
    <div class="flex flex-col flex-1 min-h-lvh relative">
        <NavBar />

        <div v-if="auth.isExiting"
            class="absolute top-0 left-0 flex items-center justify-center backdrop-blur h-full w-full">
            <svg class="size-20 animate-spin text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
        </div>
        <div class="bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4 md:mb-0">
                        Painel de Controle
                    </h1>
                    <button @click="showModal = true"
                        class="flex items-center justify-center gap-2 bg-blue-600 text-white px-5 py-2.5 rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>Novo Pedido</span>
                    </button>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                        <div class="lg:col-span-1">
                            <label for="statusFilter"
                                class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select id="statusFilter" v-model="filters.status"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-base transition duration-200 ease-in-out">
                                <option value="">Todos</option>
                                <option value="requested">Pendente</option>
                                <option value="approved">Aprovado</option>
                                <option value="cancelled">Rejeitado</option>
                            </select>
                        </div>

                        <div>
                            <label for="departureFilter"
                                class="block text-sm font-medium text-gray-700 mb-1">Partida</label>
                            <input id="departureFilter" v-model="filters.departure_date" type="date"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-base transition duration-200 ease-in-out" />
                        </div>

                        <div>
                            <label for="returnFilter"
                                class="block text-sm font-medium text-gray-700 mb-1">Retorno</label>
                            <input id="returnFilter" v-model="filters.return_date" type="date"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-base transition duration-200 ease-in-out" />
                        </div>

                        <div class="sm:col-span-2 md:col-span-1 lg:col-span-1">
                            <label for="destinationFilter"
                                class="block text-sm font-medium text-gray-700 mb-1">Destino</label>
                            <input id="destinationFilter" v-model="filters.destination" type="text"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-base transition duration-200 ease-in-out"
                                placeholder="Buscar destino..." />
                        </div>

                        <div class="self-end">
                            <button @click="fetchRequests"
                                class="w-full bg-blue-500 text-white px-5 py-2.5 rounded-lg hover:bg-blue-600 font-semibold transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Filtrar
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm overflow-hidden py-4 min-h-[500px]">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-800">Pedidos de Viagem</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="py-3.5 px-6 text-left text-sm font-semibold text-gray-900">
                                        Destino
                                    </th>
                                    <th scope="col" class="py-3.5 px-6 text-left text-sm font-semibold text-gray-900">
                                        Partida
                                    </th>
                                    <th scope="col" class="py-3.5 px-6 text-left text-sm font-semibold text-gray-900">
                                        Retorno
                                    </th>
                                    <th scope="col" class="py-3.5 px-6 text-left text-sm font-semibold text-gray-900">
                                        Status
                                    </th>
                                    <th scope="col" class="py-3.5 px-6 text-left text-sm font-semibold text-gray-900">
                                        Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr v-if="isLoading">
                                    <td colspan="5" class="text-center py-12 px-6 text-gray-500">
                                        <div class="flex flex-col items-center gap-2">
                                            <svg class="size-10 animate-spin text-blue-600"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                    stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>
                                            <span>Carregando</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-else-if="travelRequests.length > 0" v-for="req in travelRequests" :key="req.id"
                                    class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="whitespace-nowrap py-4 px-6 text-sm text-gray-700 capitalize">
                                        {{ req.destination }}
                                    </td>
                                    <td class="whitespace-nowrap py-4 px-6 text-sm text-gray-700">
                                        {{ req.departure_date }}
                                    </td>
                                    <td class="whitespace-nowrap py-4 px-6 text-sm text-gray-700">
                                        {{ req.return_date }}
                                    </td>
                                    <td class="whitespace-nowrap py-4 px-6 text-sm">
                                        <span :class="statusClass(req.status)">
                                            {{ statusLabel(req.status) }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap py-4 px-6 text-sm">
                                        <span>
                                            <RouterLink :to="{ path: `/travel-requests/${req.id}` }">Ver pedido
                                            </RouterLink>
                                        </span>
                                    </td>
                                </tr>
                                <tr v-else-if="travelRequests.length === 0">
                                    <td colspan="5" class="text-center py-12 px-6 text-gray-500">
                                        <div v-if="isLoading" class="flex flex-col items-center gap-2">
                                            <svg class="size-10 animate-spin text-blue-600"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                    stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>
                                            <span>Carregando</span>
                                        </div>
                                        <div v-else class="flex flex-col items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                            <span>Nenhum pedido encontrado.</span>
                                        </div>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <TravelRequestFormView v-if="showModal" @close="showModal = false" @created="onCreated" />
        </div>
    </div>
</template>
<script setup>
import { ref } from "vue";
import NavBar from "../components/NavBar.vue";
import TravelRequestFormView from "./TravelRequestFormView.vue";
import { fetchTravelRequests } from "../services/travelRequest.js";
import { RouterLink } from "vue-router";
import { useAuth } from "../stores/auth";

const auth = useAuth();
const showModal = ref(false);
const travelRequests = ref([]);
const isLoading = ref(false);

const filters = ref({
    status: "",
    departure_date: "",
    return_date: "",
    destination: "",
});

function statusLabel(status) {
    if (status === "requested") return "Pendente";
    if (status === "approved") return "Aprovado";
    if (status === "cancelled") return "Rejeitado";
    return status;
}
function statusClass(status) {
    if (status === "requested") return "text-yellow-600 font-semibold";
    if (status === "approved") return "text-green-600 font-semibold";
    if (status === "cancelled") return "text-red-600 font-semibold";
    return "";
}

async function fetchRequests() {
    isLoading.value = true
    travelRequests.value = await fetchTravelRequests(filters.value);
    isLoading.value = false
}

function onCreated() {
    fetchRequests();
}

fetchRequests();
</script>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto,
        sans-serif;
}
</style>
