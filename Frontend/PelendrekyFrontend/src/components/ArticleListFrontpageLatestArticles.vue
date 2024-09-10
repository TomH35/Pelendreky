<template>
  <div class="container mt-5">
    <div class="row">
      <!-- Latest Article Cards -->
      <div class="col-md-8">
        <div class="row">
          <div 
            v-for="(article, index) in visibleArticles" 
            :key="article.article_id" 
            class="col-md-4"
          >
            <div class="card mb-4" @click="goToArticle(article)">
              <img :src="article.image_url" class="card-img-top" alt="Article Image" />
              <div class="card-body">
                <h5 class="card-title">{{ article.article_title }}</h5>
                <p class="card-text" v-html="article.article_text.slice(0, 100)"></p>
              </div>
            </div>
          </div>
        </div>

        <!-- Advertisement Card -->
        <div class="row mt-4">
          <div class="col-12">
            <div class="card ad-card">
              <div class="card-body text-center">
                <h5 class="card-title">Advertisement</h5>
                <p>Ad content goes here.</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Panel Card -->
      <div class="col-md-4">
        <div class="card panel-card h-100">
          <div class="card-body">
            <h5 class="card-title">Panel Title</h5>
            <p>Panel content goes here.</p>
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
      articles: [], // All articles
      visibleArticles: [], // Articles currently visible in the cards
      currentIndex: 0, // Index for rotating the articles
      intervalId: null, // Interval ID for the auto-rotation
    };
  },
  methods: {
    async fetchLatestArticles() {
      try {
        const response = await fetch('/Backend/article_api/ArticleReadLatestAPI.php');
        const data = await response.json();

        if (!response.ok) {
          throw new Error('Failed to fetch latest articles');
        }

        this.articles = data.articles || [];
        this.updateVisibleArticles();
      } catch (error) {
        console.error(error);
      }
    },
    updateVisibleArticles() {
      // Show 3 articles at a time, rotating the list
      const articlesToShow = 3;
      this.visibleArticles = this.articles.slice(this.currentIndex, this.currentIndex + articlesToShow);

      if (this.visibleArticles.length < articlesToShow && this.articles.length > 0) {
        this.visibleArticles = this.visibleArticles.concat(
          this.articles.slice(0, articlesToShow - this.visibleArticles.length)
        );
      }

      // Update the current index
      this.currentIndex = (this.currentIndex + articlesToShow) % this.articles.length;
    },
    startArticleRotation() {
      this.intervalId = setInterval(() => {
        this.updateVisibleArticles();
      }, 10000); // Rotate every 10 seconds
    },
    stopArticleRotation() {
      if (this.intervalId) {
        clearInterval(this.intervalId);
      }
    },
    goToArticle(article) {
      this.$router.push(`/${article.category_slug}/${article.article_slug}`);
    }
  },
  mounted() {
    this.fetchLatestArticles();
    this.startArticleRotation();
  },
  beforeUnmount() {
    this.stopArticleRotation();
  }
};
</script>

<style scoped>
.container {
  margin-top: 20px;
}

.card {
  transition: transform 0.2s ease-in-out, opacity 1s ease-in-out;
}

.card:hover {
  transform: scale(1.05);
}

.card-img-top {
  height: 150px;
  object-fit: cover;
}

.ad-card {
  height: 100px;
}

/* Make the panel card align to the height of the entire article and ad section */
.panel-card {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100%; /* Make the panel card fill the entire height */
}

.col-md-4 .panel-card {
  min-height: calc(100% - 100px); /* Adjust the height to account for the ad card's height */
}
</style>


  
  