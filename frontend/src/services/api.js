import axios from 'axios';

const api = axios.create({
    baseURL: 'http://127.0.0.1:8000/api',
   // withCredentials: true,
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
    }
});

// This is the function your main.js and Login pages are looking for!
export const setAuthToken = (token) => {
    if (token) {
        // Attach the Bearer token to every subsequent request
        api.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    } else {
        // Remove it if logging out
        delete api.defaults.headers.common['Authorization'];
    }
};

// Automatically attach the token when the page first loads
const token = localStorage.getItem('api_token');
if (token) {
    setAuthToken(token);
}

export default api;