import api from '@/api/axios'

export interface LoginPayload {
  email: string
  password: string
}

export interface RegisterPayload {
  institucion_id: number
  nombres: string
  apellidos: string
  email: string
  password: string
  password_confirmation: string
  telefono?: string
}

export interface User {
  id: number
  nombres: string
  apellidos: string
  email: string
  telefono?: string
  foto_perfil?: string
  institucion_id: number
  estado: string
  roles: string[]
  ultimo_acceso?: string
}

export interface AuthResponse {
  success: boolean
  message: string
  data?: {
    token: string
    user: User
  }
}

class AuthService {
  /**
   * Realizar login
   */
  async login(payload: LoginPayload): Promise<AuthResponse> {
    try {
      const response = await api.post<AuthResponse>('/auth/login', payload)
      
      if (response.data.success && response.data.data) {
        // Guardar token
        localStorage.setItem('auth_token', response.data.data.token)
        // Guardar datos del usuario
        localStorage.setItem('user_data', JSON.stringify(response.data.data.user))
      }

      return response.data
    } catch (error: any) {
      return {
        success: false,
        message: error.response?.data?.message || 'Error en el login',
      }
    }
  }

  /**
   * Registrar nuevo usuario
   */
  async register(payload: RegisterPayload): Promise<AuthResponse> {
    try {
      const response = await api.post<AuthResponse>('/auth/register', payload)

      if (response.data.success && response.data.data) {
        // Guardar token
        localStorage.setItem('auth_token', response.data.data.token)
        // Guardar datos del usuario
        localStorage.setItem('user_data', JSON.stringify(response.data.data.user))
      }

      return response.data
    } catch (error: any) {
      return {
        success: false,
        message: error.response?.data?.message || 'Error en el registro',
      }
    }
  }

  /**
   * Realizar logout
   */
  async logout(): Promise<AuthResponse> {
    try {
      const response = await api.post<AuthResponse>('/auth/logout')

      // Limpiar almacenamiento local
      localStorage.removeItem('auth_token')
      localStorage.removeItem('user_data')

      return response.data
    } catch (error: any) {
      return {
        success: false,
        message: error.response?.data?.message || 'Error en el logout',
      }
    }
  }

  /**
   * Obtener usuario autenticado
   */
  async obtenerMe(): Promise<AuthResponse> {
    try {
      const response = await api.get<AuthResponse>('/auth/me')
      return response.data
    } catch (error: any) {
      return {
        success: false,
        message: error.response?.data?.message || 'Error al obtener el usuario',
      }
    }
  }

  /**
   * Renovar token
   */
  async renovarToken(): Promise<AuthResponse> {
    try {
      const response = await api.post<AuthResponse>('/auth/refresh')

      if (response.data.success && response.data.data) {
        // Guardar nuevo token
        localStorage.setItem('auth_token', response.data.data.token)
      }

      return response.data
    } catch (error: any) {
      return {
        success: false,
        message: error.response?.data?.message || 'Error al renovar el token',
      }
    }
  }

  /**
   * Obtener token almacenado
   */
  getToken(): string | null {
    return localStorage.getItem('auth_token')
  }

  /**
   * Obtener datos del usuario almacenados
   */
  obtenerUsuarioAlmacenado(): User | null {
    const userData = localStorage.getItem('user_data')
    return userData ? JSON.parse(userData) : null
  }

  /**
   * Verificar si hay usuario autenticado
   */
  estaAutenticado(): boolean {
    return !!this.getToken()
  }

  /**
   * Limpiar autenticación
   */
  limpiarAutenticacion(): void {
    localStorage.removeItem('auth_token')
    localStorage.removeItem('user_data')
  }
}

export default new AuthService()
