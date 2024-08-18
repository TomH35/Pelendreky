<template>
    <main>
      <div class="container">
        <div class="row">
      <CreateArticleForm />
    </div>
  </div>
   
    </main>
  </template>
  
  <script>
  import CreateArticleForm from '../components/ArticleCreateForm.vue';

  export default {
    components: {
      CreateArticleForm,
    }
  }
  
  </script>