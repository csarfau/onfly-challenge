import axios from 'axios';

const axiosInstance = axios.create({
    // baseURL: 'onfly_challenge_db:6162/api',
    baseURL: 'http://127.0.0.1:6162/api',
    headers: {
        'Content-Type': 'application/json'
    }
});
axiosInstance.interceptors.request.use((config) => {
    config.headers.Authorization = axiosInstance.defaults.headers.common.Authorization
        ? axiosInstance.defaults.headers.common.Authorization
        : `Bearer ${localStorage.getItem('token')}`;
    axiosInstance.defaults.headers.common.Authorization = `Bearer ${localStorage.getItem('token')}`;
    return config
})
export default axiosInstance;
