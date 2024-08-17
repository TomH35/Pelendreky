import { createRouter, createWebHashHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

const router = createRouter({
  history: createWebHashHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path:'/admin-login',
      name:'adminLogin',
      component: () => import('../views/AdminLoginView.vue')
    },
    {
      path:'/admin-registration',
      name:'adminRegistration',
      component: () => import('../views/AdminRegistrationView.vue')
    },
    {
      path:'/admin-main-menu',
      name:'adminMainMenu',
      component: () => import('../views/AdminMainMenuView.vue')
    },
    {
      path:'/admin-article-manager',
      name:'adminArticleManager',
      component: () => import('../views/AdminArticleManagerView.vue')
    },
    {
      path:'/admin-category-manager',
      name:'adminCategoryManager',
      component: () => import('../views/AdminCategoryManagerView.vue')
    }
  ]
})

export default router

