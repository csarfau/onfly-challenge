import { ref } from 'vue';
import { defineStore } from "pinia";
import http from '@/services/http.js';

export const useAuth = defineStore('auth', () => {
  const isAuthenticated = ref(false);
  const token = ref(localStorage.getItem('token'));
  const user = ref(JSON.parse(localStorage.getItem('user')));

  function setToken(tokenValue) {
    localStorage.setItem('token', tokenValue);
    token.value = tokenValue;
  }

  function setUser(userValue) {
    localStorage.setItem('user', JSON.stringify(userValue));
    user.user = userValue;
  }

  function removeAuthData() {
    token.value = null;
    user.value = null;
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    if (http.defaults.headers.common['Authorization']) {
      delete http.defaults.headers.common['Authorization'];
    }
  }

  async function checkToken() {
    if (!token.value) {
      removeAuthData();
      return false;
    }

    http.defaults.headers.common['Authorization'] = `Bearer ${token.value}`;
    try {
      const { data } = await http.get('/auth/me');
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
    if (token.value) {
      try {
        await http.post('/logout', {}, {
          headers: { 'Authorization': `Bearer ${token.value}` }
        });
      } catch (error) {
        console.warn("Failed to logout, but clear local data:", error.response?.data || error.message);
      }
    }
    removeAuthData();
  }

  return {
    isAuthenticated,
    token,
    user,
    login,
    logout,
    setToken,
    setUser,
    checkToken
  }
});
