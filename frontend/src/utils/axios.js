// src/utils/axios.js
import axios from 'axios';

const instance = axios.create({
    baseURL: 'http://localhost:8000',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    }
});

// Add request interceptor
instance.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('token');
        if (token) {
            // Remove 'Bearer ' if it's already included in the token
            const finalToken = token.replace('Bearer ', '');
            config.headers.Authorization = `Bearer ${finalToken}`;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// Add response interceptor
instance.interceptors.response.use(
    (response) => response,
    (error) => {
        // Only handle 401 errors that aren't from the login endpoint
        if (error.response?.status === 401 && !error.config.url.includes('/login')) {
            // Check if this is an actual authentication error
            const errorMessage = error.response?.data?.message;
            if (errorMessage === 'Invalid credentials' ||
                errorMessage === 'No API token provided' ||
                errorMessage === 'Invalid token') {
                localStorage.removeItem('token');
                localStorage.removeItem('user');
                window.location.href = '/login';
            }
        }
        return Promise.reject(error);
    }
);

export default instance;