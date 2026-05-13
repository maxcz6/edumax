import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'

import LoginPage from '../pages/LoginPage.vue'
import DashboardPage from '../pages/DashboardPage.vue'

const routes = [
  {
    path: '/',
    redirect: '/login',
  },

  {
    path: '/login',
    component: LoginPage,
    meta: {
      requiereAutenticacion: false,
    },
  },

  {
    path: '/dashboard',
    component: DashboardPage,
    meta: {
      requiereAutenticacion: true,
    },
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

/**
 * Navigation Guard: Validar autenticación
 */
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()
  const requiereAutenticacion = to.meta.requiereAutenticacion === true

  if (requiereAutenticacion && !authStore.estaAutenticado) {
    // Redirigir a login si la ruta requiere autenticación
    next('/login')
  } else if (to.path === '/login' && authStore.estaAutenticado) {
    // Redirigir a dashboard si ya está autenticado
    next('/dashboard')
  } else {
    next()
  }
})

export default router