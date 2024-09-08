<template>
  <main>
    <div class="container custom-container">
      <div class="row">
        <ArticleListFrontpageLatestArticles />
      </div>
    <div class="row">
    <ArticleListFrontpage />
  </div>
</div>
  </main>
</template>

<script>
import ArticleListFrontpage from '../components/ArticleListFrontpage.vue';
import ArticleListFrontpageLatestArticles from '../components/ArticleListFrontpageLatestArticles.vue';

export default {
  components: {
    ArticleListFrontpage,
    ArticleListFrontpageLatestArticles
  }
}
</script>
