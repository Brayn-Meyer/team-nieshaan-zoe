import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import UserGuide from '@/views/UserGuide.vue'
import HistoryView from '../views/HistoryView.vue'

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
  },
  {
    path:'/history',
    name:'History',
    component: HistoryView,
  },
{
  path:'/Timelog',
    name:'Timelog',
    component: () => import('../views/Timelog.View.vue'),
}
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

export default router
