import { createRouter, createWebHistory } from 'vue-router'
import store from '@/store'

const routes = [
  {
    path: '/',
    name: 'login',
    component: () => import('@/views/auth/Login.vue'),
    meta: {
      requiresAuth: false
    }
  },
  {
    path: '/home',
    name: 'user-home',
    component: () => import('@/views/user/Home.vue'),
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/admin/dashboard',
    name: 'admin-home',
    component: () => import('@/views/admin/Dashboard.vue'),
    meta: {
      requiresAuth: true,
      isAdmin: true
    }
  },
  {
    path: '/admin/users',
    name: 'admin-users',
    component: () => import('@/views/admin/Users.vue'),
    meta: {
      requiresAuth: true,
      isAdmin: true
    }
  },
]

const router = createRouter({
    history: createWebHistory(process.env.BASE_URL),
    routes
  })

router.beforeEach((to, from, next) => {
  if (!to.meta.requiresAuth && store.getters['auth/isAuthenticated'] && !store.getters['auth/isAdmin']) next({ name: 'user-home' })
  else if (!to.meta.requiresAuth && store.getters['auth/isAuthenticated'] && store.getters['auth/isAdmin']) next({ name: 'admin-home' })
  else if (to.meta.requiresAuth && !store.getters['auth/isAuthenticated']) next({ name: 'login' })
  else if (to.meta.requiresAuth && to.meta.isAdmin && store.getters['auth/isAuthenticated'] && !store.getters['auth/isAdmin']) next({ name: 'user-home' })
  else next()

})

export default router