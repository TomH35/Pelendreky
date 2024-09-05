import { createRouter, createWebHistory } from 'vue-router';
import HomeView from '../views/HomeView.vue';
import { useLoginStore } from '../stores/loginStore';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
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
    },
    // New route for articles with category_slug and article_slug
    {
      path: '/:category_slug/:article_slug',
      name: 'articleView',
      component: () => import('../views/ArticleView.vue'),
      props: true
    },
    {
      path: '/:category_slug', // Dynamic route for category_slug
      name: 'articlesByCategory',
      component: () => import('../views/ArticleByCategoryView.vue'),
      props: true
    }
  ],
  scrollBehavior(to, from, savedPosition) {
    return new Promise((resolve) => {
      setTimeout(() => {
        resolve({ left: 0, top: 0 });
      }, 10);
    });
  }
});

router.beforeEach((to, from, next) => {
  const loginStore = useLoginStore();

  console.log('Navigating to:', to.name); // Check which route you're navigating to
  console.log('Token:', loginStore.token);
  console.log('User is Admin:', loginStore.user_is_admin);

  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!loginStore.token) {
      console.log('Redirecting to adminLogin because no token found.');
      return next({ name: 'adminLogin' });
    }
  }

  if (to.matched.some(record => record.meta.requiresAdminAuth)) {
    if (!loginStore.token && !loginStore.user_is_admin) {
      console.log('Redirecting to adminLogin because user is not an admin.');
      return next({ name: 'adminLogin' });
    }
  }

  next(); // Allow navigation if checks pass
});


export default router;



