<template>
    <main>
      <div class="container">
        <div class="row">
      <CreateArticleForm />
    </div>
    <div class="row">
      <ArticleList />
    </div>
  </div>
   
    </main>
  </template>
  
  <script>
  import CreateArticleForm from '../components/ArticleCreateForm.vue';
  import ArticleList from '../components/ArticleList.vue';

  export default {
    components: {
      CreateArticleForm,
      ArticleList
    }
  }
  
  </script>