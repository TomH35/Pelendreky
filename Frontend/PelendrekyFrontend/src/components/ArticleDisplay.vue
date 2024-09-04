<template>
    <div class="container mt-5">
      <div v-if="loading" class="text-center">Loading article...</div>
      <div v-if="error" class="alert alert-danger">{{ error }}</div>
  
      <div v-if="article" class="article-content">
        <h1>{{ article.article_title }}</h1>
        <img v-if="article.image_url" :src="article.image_url" class="img-fluid mb-3" alt="Article Image" />
        <p class="text-muted">Published on {{ formattedDate(article.published_at) }}</p>
        <div class="article-text" v-html="article.article_text"></div>
        <div v-if="article.tags" class="tags mt-3">
          <strong>Tags: </strong>{{ article.tags.split(',').map(tag => tag.trim()).join(', ') }}
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        article: null,
        loading: false,
        error: null,
      };
    },
    methods: {
      // Fetch the article based on the slug
      async fetchArticle() {
        const category_slug = this.$route.params.category_slug;
        const article_slug = this.$route.params.article_slug;
  
        this.loading = true;
        try {
          const response = await fetch(`./Backend/article_api/ArticleReadBySlugAPI.php?category_slug=${category_slug}&article_slug=${article_slug}`);
          if (!response.ok) throw new Error('Failed to fetch article');
          const data = await response.json();
          this.article = data.article;
        } catch (error) {
          this.error = error.message;
        } finally {
          this.loading = false;
        }
      },
      // Format date for display
      formattedDate(dateString) {
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return new Date(dateString).toLocaleDateString(undefined, options);
      }
    },
    mounted() {
      this.fetchArticle(); // Fetch the article when the component is mounted
    },
    watch: {
      // Re-fetch the article if the route parameters change
      $route(to, from) {
        if (to.params.article_slug !== from.params.article_slug) {
          this.fetchArticle();
        }
      }
    }
  };
  </script>
  
  <style scoped>
  .article-content {
    max-width: 800px;
    margin: 0 auto;
  }
  .article-text {
    font-size: 18px;
    line-height: 1.6;
  }
  .tags {
    font-style: italic;
  }
  </style>
  