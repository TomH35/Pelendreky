<template>
    <div class="container mt-5">
      <!-- Loading and error messages -->
      <div v-if="loading">Loading articles...</div>
      <div v-if="error" class="alert alert-danger">{{ error }}</div>
  
      <!-- Iterate over the categories and display articles under each category -->
      <div v-else v-for="(articles, categoryId) in articlesByCategory" :key="categoryId" class="category-section">
        <h2>{{ getCategoryName(categoryId) }}</h2>
        <div class="row">
          <!-- Iterate over the articles within the category -->
          <div v-for="article in articles" :key="article.article_id" class="col-md-4">
            <div class="card mb-4" @click="goToArticle(article.article_slug, getCategorySlug(categoryId))">
              <img 
                :src="getFullImageUrl(article.image_url)" 
                class="card-img-top" 
                alt="Article Image" 
              />
              <div class="card-body">
                <h5 class="card-title">{{ article.article_title }}</h5>
                <p class="card-text" v-html="article.article_text.slice(0, 100)"></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        categories: [],
        articles: [],
        loading: false,
        error: null,
      };
    },
    computed: {
      // Group articles by category
      articlesByCategory() {
        return this.articles.reduce((grouped, article) => {
          const categoryId = article.category_id;
          if (!grouped[categoryId]) {
            grouped[categoryId] = [];
          }
          grouped[categoryId].push(article);
          return grouped;
        }, {});
      },
    },
    methods: {
      // Fetch categories and articles from the API
      async fetchCategories() {
        this.loading = true;
        try {
          const response = await fetch('./Backend/category_api/CategoryReadAPI.php');
          if (!response.ok) throw new Error('Failed to fetch categories');
          const data = await response.json();
          this.categories = data.categories;
        } catch (error) {
          this.error = error.message;
        } finally {
          this.loading = false;
        }
      },
      async fetchArticles() {
        this.loading = true;
        try {
          const response = await fetch('./Backend/article_api/ArticleReadAPI.php');
          if (!response.ok) throw new Error('Failed to fetch articles');
          const data = await response.json();
          this.articles = data.articles;
        } catch (error) {
          this.error = error.message;
        } finally {
          this.loading = false;
        }
      },
      // Get category name by ID
      getCategoryName(categoryId) {
        const category = this.categories.find((cat) => Number(cat.category_id) === Number(categoryId));
        return category ? category.category_name : 'Unknown Category';
      },
      // Get category slug by ID
      getCategorySlug(categoryId) {
        const category = this.categories.find((cat) => Number(cat.category_id) === Number(categoryId));
        return category ? category.category_slug : 'unknown-category';
      },
      // Navigate to the article's detailed page
      goToArticle(articleSlug, categorySlug) {
        this.$router.push(`/${categorySlug}/${articleSlug}`);
      },
      // Construct relative image URL
      getFullImageUrl(imageUrl) {
        console.log('image_url:', imageUrl)
        if (!imageUrl) return ''; // No image available
        // Remove './Backend' to make the path relative
        return imageUrl.replace('./', '');
      }
    },
    async mounted() {
      await this.fetchCategories();
      await this.fetchArticles();
    },
  };
  </script>
  
  <style scoped>
  .card {
    cursor: pointer;
    transition: transform 0.2s ease-in-out;
  }
  
  .card:hover {
    transform: scale(1.05);
  }
  
  .category-section {
    margin-bottom: 2rem;
  }
  
  .card-img-top {
    height: 200px;
    object-fit: cover;
  }
  </style>
  
  
  
  
  
  
