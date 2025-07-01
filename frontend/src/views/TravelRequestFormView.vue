<template>
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative">
            <button class="absolute top-2 right-2 text-gray-400 hover:text-gray-600" @click="$emit('close')">
                <span class="text-2xl">&times;</span>
            </button>
            <h2 class="text-xl font-bold mb-4">Novo Pedido de Viagem</h2>
            <form @submit.prevent="submitForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Solicitante</label>
                    <input v-model="form.requester_name" type="text" class="w-full border rounded px-3 py-2" required />
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Destino</label>
                    <input v-model="form.destination" type="text" class="w-full border rounded px-3 py-2" required />
                </div>
                <div class="flex gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium mb-1">Data de Partida</label>
                        <input v-model="form.departure_date" type="date" class="w-full border rounded px-3 py-2"
                            required />
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium mb-1">Data de Retorno</label>
                        <input v-model="form.return_date" type="date" class="w-full border rounded px-3 py-2"
                            required />
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Criar
                        Pedido</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { createTravelRequest } from '../services/travelRequest.js'

const emit = defineEmits(['close', 'created'])

const form = ref({
    destination: '',
    requester_name: '',
    departure_date: '',
    return_date: ''
})

async function submitForm() {
    try {
        await createTravelRequest(form.value)
        emit('created')
        emit('close')
    } catch (e) {
        alert('Erro ao criar pedido de viagem.')
    }
}
</script>
