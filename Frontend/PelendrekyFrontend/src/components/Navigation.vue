<template>
  <nav class="navbar navbar-expand-sm navbar-dark fixed-top custom-background">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">
        <img src="" alt="logo" width="28" height="28" class="d-inline-block align-text-top">
        <span class="custom-navbar-top-color"></span>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item" v-for="(url, page) in headerMenu" :key="page">
            <router-link :to="url" class="nav-link" @click="logoutIfNecessary(page)">
              <span class="custom-navbar-color">{{ page }}</span>
            </router-link>
          </li>
          <!-- Dynamic category links -->
          <li v-for="category in categories" :key="category.category_id" class="nav-item">
            <router-link :to="`/${category.category_slug}`" class="nav-link">
              <span class="custom-navbar-color">{{ category.category_name }}</span>
            </router-link>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
import { useLoginStore } from '../stores/loginStore';
import { useCategoryStore } from '../stores/CategoryStore'; // Import your category store

export default {
  data() {
    return {
      headerMenu: {
        "Logout": "/",
        // Add any other static menu items here...
      },
      categories: [], // To store the fetched categories
    };
  },
  methods: {
    logoutIfNecessary(page) {
      if (page === 'Logout') {
        const loginStore = useLoginStore();
        loginStore.logout();
      }
    },
    async fetchCategories() {
      const categoryStore = useCategoryStore();
      await categoryStore.fetchCategories(); // Fetch categories from the API
      this.categories = categoryStore.getCategories; // Store them locally
    },
  },
  mounted() {
    this.fetchCategories(); // Fetch categories when the component is mounted
  },
};
</script>
