<template>
    <main>
      <div class="container custom-container">
        <div class="row">
          <ArticleByCategoryDisplayTop />
      </div>
      <div class="row">
        <ArticleByCategoryDisplay />
      </div>

      </div>
    </main>
  </template>
  
  <script>
  import ArticleByCategoryDisplay from '../components/ArticleByCategoryDisplay.vue';
  import ArticleByCategoryDisplayTop from '../components/ArticleByCategoryDisplayTop.vue';
  
  export default {
    components: {
      ArticleByCategoryDisplay,
      ArticleByCategoryDisplayTop
    }
  }
  </script>