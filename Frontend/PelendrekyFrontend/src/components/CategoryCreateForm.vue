<template>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <h3>Create a New Category</h3>
          </div>
          <div class="card-body">
            <form @submit.prevent="createCategory">
              <div class="mb-3">
                <label for="categoryName" class="form-label">Category Name</label>
                <input
                  type="text"
                  class="form-control"
                  id="categoryName"
                  v-model="categoryName"
                  @input="generateSlug"
                  required
                />
              </div>
              <div class="mb-3">
                <label for="categorySlug" class="form-label">Category Slug</label>
                <input
                  type="text"
                  class="form-control"
                  id="categorySlug"
                  v-model="categorySlug"
                  required
                  readonly
                />
              </div>
              <div v-if="errorMessage" class="alert alert-danger">
                {{ errorMessage }}
              </div>
              <div v-if="successMessage" class="alert alert-success">
                {{ successMessage }}
              </div>
              <button type="submit" class="btn btn-primary">Create Category</button>
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
      categoryName: '',
      categorySlug: '',
      errorMessage: '',
      successMessage: ''
    };
  },
  methods: {
    generateSlug() {
    const accentMap = {
      'ó': 'o',
      'š': 's',
      'č': 'c',
      'í': 'i',
      'ý': 'y',
      'ť': 't',
      'ď': 'd',
      'á': 'a',
      'ä': 'a',
      'é': 'e',
      'ľ': 'l',
      'ĺ': 'l',
      'ž': 'z',
      'ň': 'n',
      'ú': 'u',
      'ů': 'u',
      'ô': 'o',
      'ř': 'r',
      'ý': 'y',
      'č': 'c',
      'ř': 'r',
      'ž': 'z'
    };

    // Replace each special character with its corresponding letter
    this.categorySlug = this.categoryName
      .toLowerCase()
      .split('')
      .map(char => accentMap[char] || char)
      .join('')
      .replace(/[^a-z0-9]+/g, '-') // Replace any non-alphanumeric character with a hyphen
      .replace(/^-|-$/g, ''); // Remove leading or trailing hyphens
  },
    async createCategory() {
      this.errorMessage = '';
      this.successMessage = '';

      try {
        const response = await fetch('./Backend/category_api/CategoryCreateAPI.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ categoryName: this.categoryName, categorySlug: this.categorySlug })
        });

        const data = await response.json();

        if (!response.ok) {
          throw new Error(data.message || 'Failed to create category');
        }

        this.successMessage = 'Category created successfully!';
        this.categoryName = '';
        this.categorySlug = '';
      } catch (error) {
        this.errorMessage = error.message;
      }
    }
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
