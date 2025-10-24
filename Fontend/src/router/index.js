import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import UserGuide from '@/views/UserGuide.vue'

const routes = [
  {
    path: '/',
    name: 'Dashboard',
    component: HomeView
  },
 {
    path: '/user-guide',
    name: 'UserGuide',
    component: UserGuide,
  }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

export default router
