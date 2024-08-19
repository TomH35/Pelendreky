<template>
    <div class="container mt-5">
      <div v-if="errorMessage" class="alert alert-danger">
        {{ errorMessage }}
      </div>
      <div v-if="successMessage" class="alert alert-success">
        {{ successMessage }}
      </div>
      <div v-for="category in categorizedArticles" :key="category.category_id" class="mb-5">
        <h3>{{ category.category_name }}</h3>
        <ul class="list-group">
          <li
            v-for="article in category.articles"
            :key="article.article_id"
            class="list-group-item d-flex justify-content-between align-items-center"
          >
            <span>{{ article.article_title }}</span>
            <div>
              <button
                class="btn btn-sm btn-primary me-2"
                @click="openEditModal(article)"
              >
                Edit
              </button>
              <button
                class="btn btn-sm btn-danger"
                @click="confirmDeleteArticle(article.article_id)"
              >
                Delete
              </button>
            </div>
          </li>
        </ul>
      </div>
  
      <!-- Delete Confirmation Modal -->
      <div
        class="modal fade"
        id="deleteModal"
        tabindex="-1"
        aria-labelledby="deleteModalLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="deleteModalLabel">Delete Article</h5>
              <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
              ></button>
            </div>
            <div class="modal-body">
              Are you sure you want to delete this article?
            </div>
            <div class="modal-footer">
              <button
                type="button"
                class="btn btn-secondary"
                data-bs-dismiss="modal"
              >
                Cancel
              </button>
              <button type="button" class="btn btn-danger" @click="deleteArticle">
                Delete
              </button>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Edit Article Modal -->
      <div
        class="modal fade"
        id="editModal"
        tabindex="-1"
        aria-labelledby="editModalLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="editModalLabel">Edit Article</h5>
              <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
              ></button>
            </div>
            <div class="modal-body">
              <form @submit.prevent="updateArticle" enctype="multipart/form-data">
                <div class="mb-3">
                  <label for="editArticleTitle" class="form-label">Title</label>
                  <input
                    type="text"
                    class="form-control"
                    id="editArticleTitle"
                    v-model="editArticleData.article_title"
                    required
                  />
                </div>
                <div class="mb-3">
                  <label for="editArticleSlug" class="form-label">Slug</label>
                  <input
                    type="text"
                    class="form-control"
                    id="editArticleSlug"
                    v-model="editArticleData.article_slug"
                    required
                  />
                </div>
                <div class="mb-3">
                  <label for="editArticleText" class="form-label">Text</label>
                  <textarea
                    class="form-control"
                    id="editArticleText"
                    v-model="editArticleData.article_text"
                    rows="5"
                    required
                  ></textarea>
                </div>
                <div class="mb-3">
                  <label for="editCategoryId" class="form-label">Category</label>
                  <select
                    class="form-select"
                    id="editCategoryId"
                    v-model="editArticleData.category_id"
                  >
                    <option
                      v-for="category in categories"
                      :key="category.category_id"
                      :value="category.category_id"
                    >
                      {{ category.category_name }}
                    </option>
                  </select>
                </div>
                <div class="mb-3">
                  <label for="editTags" class="form-label">Tags</label>
                  <input
                    type="text"
                    class="form-control"
                    id="editTags"
                    v-model="editArticleData.tags"
                  />
                </div>
                <div class="mb-3">
                  <label for="editStatus" class="form-label">Status</label>
                  <select
                    class="form-select"
                    id="editStatus"
                    v-model="editArticleData.status"
                    required
                  >
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                    <option value="archived">Archived</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label for="editImageUrl" class="form-label">Image</label>
                  <input
                    type="file"
                    class="form-control"
                    id="editImageUrl"
                    @change="handleImageUpload"
                  />
                </div>
                <button type="submit" class="btn btn-primary">Save changes</button>
              </form>
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
        categorizedArticles: [],
        deleteModalInstance: null,
        editModalInstance: null,
        articleIdToDelete: null,
        editArticleData: {
          article_id: null,
          article_title: '',
          article_slug: '',
          article_text: '',
          category_id: null,
          tags: '',
          status: 'draft',
          imageFile: null // Store the selected image file
        },
        errorMessage: '',
        successMessage: ''
      };
    },
    methods: {
      async fetchArticles() {
        try {
          const response = await fetch('./Backend/article_api/ArticleReadAPI.php');
          const data = await response.json();
  
          if (!response.ok) {
            throw new Error(data.message || 'Failed to fetch articles');
          }
  
          this.articles = data.articles;
          this.categorizeArticles();
        } catch (error) {
          this.errorMessage = error.message;
        }
      },
      categorizeArticles() {
        const categoriesMap = {};
  
        this.articles.forEach((article) => {
          if (!categoriesMap[article.category_id]) {
            categoriesMap[article.category_id] = {
              category_id: article.category_id,
              category_name: article.category_name,
              articles: []
            };
          }
          categoriesMap[article.category_id].articles.push(article);
        });
  
        this.categorizedArticles = Object.values(categoriesMap);
      },
      async fetchCategories() {
        try {
          const response = await fetch('./Backend/category_api/CategoryReadAPI.php');
          const data = await response.json();
  
          if (!response.ok) {
            throw new Error(data.message || 'Failed to fetch categories');
          }
  
          this.categories = data.categories;
        } catch (error) {
          this.errorMessage = error.message;
        }
      },
      handleImageUpload(event) {
        this.editArticleData.imageFile = event.target.files[0];
      },
      confirmDeleteArticle(articleId) {
        this.articleIdToDelete = articleId;
        this.deleteModalInstance = new window.bootstrap.Modal(document.getElementById('deleteModal'));
        this.deleteModalInstance.show();
      },
      async deleteArticle() {
        if (!this.articleIdToDelete) return;
  
        try {
          const response = await fetch(`./Backend/article_api/ArticleDeleteAPI.php`, {
            method: 'DELETE',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({ article_id: this.articleIdToDelete })
          });
  
          const data = await response.json();
  
          if (!response.ok) {
            throw new Error(data.message || 'Failed to delete article');
          }
  
          this.successMessage = 'Article deleted successfully!';
          this.fetchArticles(); // Refresh the list
          this.deleteModalInstance.hide();
        } catch (error) {
          this.errorMessage = error.message;
        }
      },
      openEditModal(article) {
        this.editArticleData = { ...article, imageFile: null }; // Reset the image file input
        this.editModalInstance = new window.bootstrap.Modal(document.getElementById('editModal'));
        this.editModalInstance.show();
      },
      async updateArticle() {
        try {
          const formData = new FormData();
          formData.append('article_id', this.editArticleData.article_id);
          formData.append('article_title', this.editArticleData.article_title);
          formData.append('article_slug', this.editArticleData.article_slug);
          formData.append('article_text', this.editArticleData.article_text);
          formData.append('category_id', this.editArticleData.category_id);
          formData.append('tags', this.editArticleData.tags);
          formData.append('status', this.editArticleData.status);
  
          if (this.editArticleData.imageFile) {
            formData.append('image', this.editArticleData.imageFile);
          }
  
          const response = await fetch('./Backend/article_api/ArticleUpdateAPI.php', {
            method: 'POST',
            body: formData
          });
  
          const data = await response.json();
  
          if (!response.ok) {
            throw new Error(data.message || 'Failed to update article');
          }
  
          this.successMessage = 'Article updated successfully!';
          this.fetchArticles(); // Refresh the list
          this.editModalInstance.hide();
        } catch (error) {
          this.errorMessage = error.message;
        }
      }
    },
    mounted() {
      this.fetchArticles();
      this.fetchCategories();
    }
  };
  </script>
  
  <style scoped>
  .container {
    margin-top: 20px;
  }
  </style>
  