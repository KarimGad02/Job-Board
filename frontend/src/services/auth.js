import { ref } from 'vue';
import api, { setAuthToken } from './api';

export const authUser = ref(null);
export const isLoggedIn = ref(!!localStorage.getItem('api_token'));

export const checkAuth = async () => {
    const token = localStorage.getItem('api_token');
    if (!token) {
        authUser.value = null;
        isLoggedIn.value = false;
        return null;
    }
    
    try {
        const res = await api.get('/profile');
        authUser.value = res.data;
        isLoggedIn.value = true;
        return res.data;
    } catch (e) {
        console.error('Auth verification failed', e);
        logoutUser();
        return null;
    }
};

export const loginUser = (token, user) => {
    localStorage.setItem('api_token', token);
    setAuthToken(token);
    authUser.value = user;
    isLoggedIn.value = true;
};

export const logoutUser = () => {
    localStorage.removeItem('api_token');
    setAuthToken(null);
    authUser.value = null;
    isLoggedIn.value = false;
};
