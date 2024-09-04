import { defineStore } from 'pinia';

export const useCategoryStore = defineStore({
  id: 'category',
  state: () => ({
    categories: [],
    loading: false,
    error: null
  }),
  getters: {
    getCategories: (state) => state.categories, // Return all categories
    getCategoryById: (state) => (id) => state.categories.find(category => category.category_id === id), // Get a category by ID
  },
  actions: {
    async fetchCategories() {
      this.loading = true;
      this.error = null;

      try {
        const response = await fetch('./Backend/category_api/CategoryReadAPI.php', {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
          },
        });

        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();

        this.categories = data.categories || [];
      } catch (error) {
        this.error = error.message || 'Failed to fetch categories';
      } finally {
        this.loading = false;
      }
    },
  },
});
