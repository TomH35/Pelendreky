<template>
    <main>
      <div class="container">
        <div class="row">
      <CreateCategoryForm />
    </div>
    <div class="row">
      <CategoryList />
    </div>
  </div>
    </main>
  </template>
  
  <script>
  import CreateCategoryForm from '../components/CategoryCreateForm.vue';
  import CategoryList from '../components/CategoryList.vue';

export default {
  components: {
    CreateCategoryForm,
    CategoryList
  }
}
  
  </script>