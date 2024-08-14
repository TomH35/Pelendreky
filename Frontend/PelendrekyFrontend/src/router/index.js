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
      path:'/admin-create-article',
      name:'adminCreateArticle',
      component: () => import('../views/AdminCreateArticleView.vue')
    },
    {
      path:'/admin-delete-article',
      name:'adminDeleteArticle',
      component: () => import('../views/AdminDeleteArticleView.vue')
    }
  ]
})

export default router

