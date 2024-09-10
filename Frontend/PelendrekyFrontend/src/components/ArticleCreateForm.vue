<template>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            <h3>Create a New Article</h3>
          </div>
          <div class="card-body">
            <form @submit.prevent="createArticle" enctype="multipart/form-data">
              <div class="mb-3">
                <label for="articleTitle" class="form-label">Article Title</label>
                <input
                  type="text"
                  class="form-control"
                  id="articleTitle"
                  v-model="articleTitle"
                  @input="generateSlug"
                  required
                />
              </div>

              <div class="mb-3">
                <label for="articleSlug" class="form-label">Article Slug</label>
                <input
                  type="text"
                  class="form-control"
                  id="articleSlug"
                  v-model="articleSlug"
                  required
                />
              </div>

              <!-- Quill Editor -->
              <div class="mb-3">
                <label for="articleText" class="form-label">Article Text</label>
                <div ref="quillEditor" id="articleEditor"></div>
              </div>

              <div class="mb-3">
                <label for="categoryId" class="form-label">Category</label>
                <select
                  class="form-select"
                  id="categoryId"
                  v-model="categoryId"
                >
                  <option v-for="category in categories" :key="category.category_id" :value="category.category_id">
                    {{ category.category_name }}
                  </option>
                </select>
              </div>

              <div class="mb-3">
                <label for="imageUrl" class="form-label">Image</label>
                <input
                  type="file"
                  class="form-control"
                  id="imageUrl"
                  @change="handleImageUpload"
                />
              </div>

              <div class="mb-3">
                <label for="tags" class="form-label">Tags (comma-separated)</label>
                <input
                  type="text"
                  class="form-control"
                  id="tags"
                  v-model="tags"
                />
              </div>

              <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select
                  class="form-select"
                  id="status"
                  v-model="status"
                  required
                >
                  <option value="draft">Draft</option>
                  <option value="published">Published</option>
                  <option value="archived">Archived</option>
                </select>
              </div>

              <div v-if="errorMessage" class="alert alert-danger">
                {{ errorMessage }}
              </div>
              <div v-if="successMessage" class="alert alert-success">
                {{ successMessage }}
              </div>

              <button type="submit" class="btn btn-primary">Create Article</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Quill from 'quill'; // Import Quill
import { useLoginStore } from '../stores/loginStore';

export default {
  data() {
    return {
      articleTitle: '',
      articleSlug: '',
      articleText: '', // This will store the HTML content from Quill
      categoryId: null,
      imageFile: null, // Store the selected image file
      tags: '',
      status: 'draft',
      categories: [],
      errorMessage: '',
      successMessage: ''
    };
  },
  methods: {
    generateSlug() {
      const accentMap = {
        'ó': 'o', 'š': 's', 'č': 'c', 'í': 'i', 'ý': 'y',
        'ť': 't', 'ď': 'd', 'á': 'a', 'ä': 'a', 'é': 'e',
        'ľ': 'l', 'ĺ': 'l', 'ž': 'z', 'ň': 'n', 'ú': 'u',
        'ů': 'u', 'ô': 'o', 'ř': 'r', 'ý': 'y', 'č': 'c', 'ř': 'r', 'ž': 'z'
      };

      this.articleSlug = this.articleTitle
        .toLowerCase()
        .split('')
        .map(char => accentMap[char] || char)
        .join('')
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-|-$/g, ''); 
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
      this.imageFile = event.target.files[0];
    },
    async createArticle() {
      try {
        const loginStore = useLoginStore();

        // Get HTML content from Quill
        this.articleText = this.quillEditor.root.innerHTML;

        const formData = new FormData();
        formData.append('article_title', this.articleTitle);
        formData.append('article_slug', this.articleSlug);
        formData.append('article_text', this.articleText); // Save HTML content
        formData.append('category_id', this.categoryId);
        formData.append('tags', this.tags);
        formData.append('status', this.status);
        formData.append('user_id', loginStore.user_id); // Add user_id from the store

        if (this.imageFile) {
          formData.append('image', this.imageFile);
        }

        const response = await fetch('./Backend/article_api/ArticleCreateAPI.php', {
          method: 'POST',
          body: formData
        });

        const data = await response.json();

        if (!response.ok) {
          throw new Error(data.message || 'Failed to create article');
        }

        this.successMessage = 'Article created successfully!';
        this.clearForm();
      } catch (error) {
        this.errorMessage = error.message;
      }
    },
    clearForm() {
      this.articleTitle = '';
      this.articleSlug = '';
      this.articleText = '';
      this.categoryId = null;
      this.imageFile = null;
      this.tags = '';
      this.status = 'draft';
      this.quillEditor.root.innerHTML = ''; // Clear the Quill editor
    }
  },
  mounted() {
    this.fetchCategories();

    // Initialize Quill editor
    this.quillEditor = new Quill(this.$refs.quillEditor, {
      theme: 'snow',
      placeholder: 'Write your article here...',
      modules: {
        toolbar: [
          ['bold', 'italic', 'underline', 'strike'], // Formatting buttons
          ['blockquote', 'code-block'],
          [{ 'header': 1 }, { 'header': 2 }],
          [{ 'list': 'ordered' }, { 'list': 'bullet' }],
          [{ 'indent': '-1' }, { 'indent': '+1' }],
          [{ 'align': [] }],
          ['link', 'image'],
          ['clean'] // Remove formatting button
        ]
      }
    });
  }
};
</script>

<style scoped>
.container {
  margin-top: 20px;
}

.card-header {
  background-color: #343a40;
  color: #fff;
}
</style>

  
  