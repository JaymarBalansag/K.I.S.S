import axios from "axios";

const api = axios.create({
  // baseURL: "http://localhost:8000/api",
  baseURL: import.meta.env.VITE_API_AUTH_URL ? import.meta.env.VITE_API_AUTH_URL : "http://127.0.0.1:8000/api",
  withCredentials: true, // include cookies (refresh_token)
});

// Request interceptor → attach token before each request
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem("access_token");
    if (token) {
      config.headers["Authorization"] = `Bearer ${token}`;
    }
    return config;
  },
  (error) => Promise.reject(error)
);


export default api;
