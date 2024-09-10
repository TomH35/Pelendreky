<template>
    <div class="container mt-5">
      <h2 v-if="categoryName">{{ categoryName }}</h2>
      <div v-if="loading">Loading articles...</div>
      <div v-if="error" class="alert alert-danger">{{ error }}</div>
  
      <div v-if="articles.length > 0" class="row">
        <div v-for="article in articles" :key="article.article_id" class="col-md-4">
          <div 
            class="card mb-4" 
            @click="goToArticle(article.article_slug)"
            style="cursor: pointer;"
          >
            <img :src="article.image_url" class="card-img-top" alt="Article Image" />
            <div class="card-body">
              <h5 class="card-title">{{ article.article_title }}</h5>
              <p class="card-text" v-html="article.article_text.slice(0, 100)"></p>
            </div>
          </div>
        </div>
      </div>
  
      <div v-else>No articles found in this category.</div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        articles: [],
        categoryName: '',
        category_slug: '', // Store category_slug from route params
        loading: false,
        error: null,
      };
    },
    async mounted() {
      this.category_slug = this.$route.params.category_slug; // Get category_slug from route params
      await this.fetchArticlesByCategory(); // Fetch articles when the component is mounted
    },
    watch: {
      // Watch for changes in the route parameters to fetch the new category articles
      '$route.params.category_slug': {
        immediate: true,
        handler(newCategorySlug) {
          this.category_slug = newCategorySlug; // Update category_slug when the route changes
          this.fetchArticlesByCategory(); // Fetch articles for the new category
        }
      }
    },
    methods: {
      async fetchArticlesByCategory() {
        this.loading = true;
        this.error = null;
  
        try {
          // Fetch articles by category_slug
          const response = await fetch(`/Backend/article_api/ArticleReadByCategoryAPI.php?category_slug=${this.category_slug}`);
          if (!response.ok) {
            throw new Error('Failed to fetch articles');
          }
  
          const data = await response.json();
          this.articles = data.articles || [];
          this.categoryName = data.category_name || 'Unknown Category';
        } catch (error) {
          this.error = error.message;
        } finally {
          this.loading = false;
        }
      },
      goToArticle(article_slug) {
        // Navigate to the article page based on category_slug and article_slug
        this.$router.push(`/${this.category_slug}/${article_slug}`);
      }
    },
  };
  </script>
  
  <style scoped>
  .card {
    transition: transform 0.2s ease-in-out;
  }
  
  .card:hover {
    transform: scale(1.05);
  }
  
  .card-img-top {
    height: 200px;
    object-fit: cover;
  }
  </style>
  
  

  