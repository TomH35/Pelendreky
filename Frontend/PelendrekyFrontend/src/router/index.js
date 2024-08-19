import { createRouter, createWebHashHistory } from 'vue-router';
import HomeView from '../views/HomeView.vue';
import { useLoginStore } from '../stores/loginStore';

const router = createRouter({
  history: createWebHashHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path: '/admin-login',
      name: 'adminLogin',
      component: () => import('../views/AdminLoginView.vue')
    },
    {
      path: '/admin-registration',
      name: 'adminRegistration',
      component: () => import('../views/AdminRegistrationView.vue')
    },
    {
      path: '/admin-main-menu',
      name: 'adminMainMenu',
      component: () => import('../views/AdminMainMenuView.vue'),
      meta: { requiresAdminAuth: true } // Require admin privileges
    },
    {
      path: '/admin-article-manager',
      name: 'adminArticleManager',
      component: () => import('../views/AdminArticleManagerView.vue'),
      meta: { requiresAdminAuth: true } // Require admin privileges
    },
    {
      path: '/admin-category-manager',
      name: 'adminCategoryManager',
      component: () => import('../views/AdminCategoryManagerView.vue'),
      meta: { requiresAdminAuth: true } // Require admin privileges
    }
  ],
  scrollBehavior(to, from, savedPosition) {
    return new Promise((resolve, reject) => {
      setTimeout(() => {
        resolve({ left: 0, top: 0 });
      }, 10);
    });
  }
});

// Global navigation guard
router.beforeEach((to, from, next) => {
  const loginStore = useLoginStore();

  if (to.matched.some(record => record.meta.requiresAuth)) {
    // If the route requires authentication
    if (!loginStore.token) {
      return next({ name: 'adminLogin' }); // Redirect to login if not authenticated
    }
  }

  if (to.matched.some(record => record.meta.requiresAdminAuth)) {
    // If the route requires admin authentication
    if (!loginStore.token && !loginStore.user_is_admin) {
      return next({ name: 'adminLogin' }); // Redirect to login if not authenticated or not an admin
    }
  }

  next(); // Proceed to the route if all checks pass
});

export default router;


