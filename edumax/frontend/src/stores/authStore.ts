import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import authService, { type User } from '@/services/authService'

export const useAuthStore = defineStore('auth', () => {
  const usuario = ref<User | null>(authService.obtenerUsuarioAlmacenado())
  const token = ref<string | null>(authService.getToken())
  const cargando = ref(false)
  const error = ref<string | null>(null)

  // Computed
  const estaAutenticado = computed(() => !!token.value)
  const nombreCompleto = computed(() => {
    if (usuario.value) {
      return `${usuario.value.nombres} ${usuario.value.apellidos}`
    }
    return ''
  })

  // Acciones
  async function login(email: string, password: string) {
    cargando.value = true
    error.value = null

    try {
      const resultado = await authService.login({ email, password })

      if (resultado.success && resultado.data) {
        usuario.value = resultado.data.user
        token.value = resultado.data.token
        return resultado
      } else {
        error.value = resultado.message
        return resultado
      }
    } catch (err: any) {
      error.value = err.message
      return {
        success: false,
        message: err.message,
      }
    } finally {
      cargando.value = false
    }
  }

  async function register(datos: {
    institucion_id: number
    nombres: string
    apellidos: string
    email: string
    password: string
    password_confirmation: string
    telefono?: string
  }) {
    cargando.value = true
    error.value = null

    try {
      const resultado = await authService.register(datos)

      if (resultado.success && resultado.data) {
        usuario.value = resultado.data.user
        token.value = resultado.data.token
        return resultado
      } else {
        error.value = resultado.message
        return resultado
      }
    } catch (err: any) {
      error.value = err.message
      return {
        success: false,
        message: err.message,
      }
    } finally {
      cargando.value = false
    }
  }

  async function logout() {
    cargando.value = true
    error.value = null

    try {
      const resultado = await authService.logout()
      usuario.value = null
      token.value = null
      return resultado
    } catch (err: any) {
      error.value = err.message
      return {
        success: false,
        message: err.message,
      }
    } finally {
      cargando.value = false
    }
  }

  async function obtenerMe() {
    try {
      const resultado = await authService.obtenerMe()

      if (resultado.success && resultado.data) {
        usuario.value = resultado.data
      }

      return resultado
    } catch (err: any) {
      error.value = err.message
      return {
        success: false,
        message: err.message,
      }
    }
  }

  async function renovarToken() {
    try {
      const resultado = await authService.renovarToken()

      if (resultado.success && resultado.data) {
        token.value = resultado.data.token
      }

      return resultado
    } catch (err: any) {
      error.value = err.message
      return {
        success: false,
        message: err.message,
      }
    }
  }

  function limpiarError() {
    error.value = null
  }

  return {
    // State
    usuario,
    token,
    cargando,
    error,

    // Computed
    estaAutenticado,
    nombreCompleto,

    // Actions
    login,
    register,
    logout,
    obtenerMe,
    renovarToken,
    limpiarError,
  }
})
