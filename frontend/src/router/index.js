import { createRouter, createWebHistory } from 'vue-router'
import { useAuth } from '@/stores/auth.js'
import DashboardView from '../views/DashboardView.vue'
import LoginView from '../views/LoginView.vue'
import RegisterView from '../views/RegisterView.vue'
import TravelRequestFormView from '../views/TravelRequestFormView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/login',
      name: 'login',
      component: LoginView,
      meta: { requiresAuth: false }
    },
    {
      path: '/register',
      name: 'register',
      component: RegisterView,
      meta: { requiresAuth: false }
    },
    {
      path: '/',
      name: 'dashboard',
      component: DashboardView,
      meta: { requiresAuth: true }
    },
    {
      path: '/create',
      name: 'create',
      component: TravelRequestFormView,
      meta: { requiresAuth: true }
    },
    {
      path: '/:pathMatch(.*)*',
      name: 'NotFound',
      redirect: '/login'
    }
  ]
})

router.beforeEach((to, from, next) => {
  const auth = useAuth()

  if (to.meta.requiresAuth && !auth.isAuthenticated) {
    next({ name: 'login' })
  } else if (!to.meta.requiresAuth && auth.isAuthenticated) {
    next({ name: 'dashboard' })
  } else {
    next()
  }
})

export default router
