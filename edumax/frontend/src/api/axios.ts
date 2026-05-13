import axios, { AxiosInstance, AxiosError } from 'axios'

const api: AxiosInstance = axios.create({
  baseURL: import.meta.env.VITE_API_URL || 'http://127.0.0.1:8000/api',
  withCredentials: true,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
})

/**
 * Interceptor: Agregar token al header Authorization
 */
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('auth_token')
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }
    return config
  },
  (error: AxiosError) => {
    return Promise.reject(error)
  }
)

/**
 * Interceptor: Manejar respuestas y errores
 */
api.interceptors.response.use(
  (response) => {
    return response
  },
  (error: AxiosError) => {
    // Si es error 401 (no autorizado), limpiar token y redirigir a login
    if (error.response?.status === 401) {
      localStorage.removeItem('auth_token')
      localStorage.removeItem('user_data')
      // Emit evento para redirigir a login
      window.dispatchEvent(new Event('logout'))
    }

    return Promise.reject(error)
  }
)

export default api