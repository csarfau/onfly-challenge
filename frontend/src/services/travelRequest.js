import http from './http.js';

export async function fetchTravelRequests(params = {}) {
    const { data } = await http.get('/travel-requests', { params });
    return data;
}

export async function createTravelRequest(payload) {
    const { data } = await http.post('/travel-requests/create', payload);
    return data;
}
