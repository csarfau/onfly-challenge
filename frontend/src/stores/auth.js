import { ref } from 'vue';
import { defineStore } from "pinia";
import http from '../services/http.js';

export const useAuth = defineStore('auth', () => {
    const isAuthenticated = ref(!!localStorage.getItem('token'));
    const token = ref(localStorage.getItem('token'));
    const user = ref(JSON.parse(localStorage.getItem('user')));
    const isExiting = ref(false);

    function setToken(tokenValue) {
        localStorage.setItem('token', tokenValue);
        token.value = tokenValue;
    }

    function setUser(userValue) {
        localStorage.setItem('user', JSON.stringify(userValue));
        user.value = userValue;
    }

    function removeAuthData() {
        token.value = null;
        user.value = null;
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        if (http.defaults.headers.common['Authorization']) {
            delete http.defaults.headers.common['Authorization'];
        }
        isAuthenticated.value = false;
    }

    async function checkToken() {
        if (!token.value) {
            removeAuthData();
            return false;
        }

        http.defaults.headers.common['Authorization'] = `Bearer ${token.value}`;
        try {
            const { data } = await http.get('/me');
            setUser(data);
            isAuthenticated.value = true
            return true;
        } catch (error) {
            removeAuthData();
            return false;
        }
    }

    async function login(credentials) {
        try {
            const response = await http.post('/auth/login', credentials);
            setToken(response.data.access_token);
            http.defaults.headers.common['Authorization'] = `Bearer ${token.value}`;
            await checkToken();
            isAuthenticated.value = true
            return true;
        } catch (error) {
            console.error("Login error:", error.response?.data || error.message);
            removeAuthData();
            throw error;
        }
    }

    async function logout() {
        isExiting.value = true;
        if (token.value) {
            try {
                await http.post('/logout', {}, {
                    headers: { 'Authorization': `Bearer ${token.value}` }
                });
            } catch (error) {
                console.warn("Failed to logout, but clear local data:", error.response?.data || error.message);
            }
            removeAuthData();
            isExiting.value = false;
        }
    }

    return {
        isAuthenticated,
        isExiting,
        token,
        user,
        login,
        logout,
        setToken,
        setUser,
        checkToken
    }
});
