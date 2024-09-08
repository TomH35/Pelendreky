<template>
    <div class="container mt-5">
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card">
            <div class="card-header">
              <h3>Categories</h3>
            </div>
            <div class="card-body">
              <div v-if="errorMessage" class="alert alert-danger">
                {{ errorMessage }}
              </div>
              <div v-if="successMessage" class="alert alert-success">
                {{ successMessage }}
              </div>
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th scope="col">Category Name</th>
                    <th scope="col">Slug</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="category in categories" :key="category.category_id">
                    <td>{{ category.category_name }}</td>
                    <td>{{ category.category_slug }}</td>
                    <td>
                      <button @click="openEditModal(category)" class="btn btn-sm btn-primary">Edit</button>
                      <button @click="confirmDeleteCategory(category.category_id)" class="btn btn-sm btn-danger">Delete</button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Delete Confirmation Modal -->
      <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="deleteModalLabel">Delete Category</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              Are you sure you want to delete this category?
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-danger" @click="deleteCategory">Delete</button>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Edit Category Modal -->
      <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="editModalLabel">Edit Category</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form @submit.prevent="updateCategory">
                <div class="mb-3">
                  <label for="editCategoryName" class="form-label">Category Name</label>
                  <input
                    type="text"
                    class="form-control"
                    id="editCategoryName"
                    v-model="editCategoryData.category_name"
                    required
                  />
                </div>
                <div class="mb-3">
                  <label for="editCategorySlug" class="form-label">Category Slug</label>
                  <input
                    type="text"
                    class="form-control"
                    id="editCategorySlug"
                    v-model="editCategoryData.category_slug"
                    required
                  />
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
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
        categoryIdToDelete: null,
        editCategoryData: { category_id: null, category_name: '', category_slug: '' }, // Data for the category being edited
        deleteModalInstance: null, // Store the delete modal instance
        editModalInstance: null, // Store the edit modal instance
        errorMessage: '',
        successMessage: ''
      };
    },
    methods: {
      async fetchCategories() {
        try {
          const response = await fetch('/Backend/category_api/CategoryReadAPI.php');
          const data = await response.json();
  
          if (!response.ok) {
            throw new Error(data.message || 'Failed to fetch categories');
          }
  
          this.categories = data.categories;
        } catch (error) {
          this.errorMessage = error.message;
        }
      },
      openEditModal(category) {
        // Pre-fill the edit form with the current category data
        this.editCategoryData = { ...category };
        // Show the Bootstrap modal
        this.editModalInstance = new window.bootstrap.Modal(document.getElementById('editModal'));
        this.editModalInstance.show();
      },
      async updateCategory() {
        try {
          const response = await fetch('/Backend/category_api/CategoryUpdateAPI.php', {
            method: 'PUT',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify(this.editCategoryData)
          });
  
          const data = await response.json();
  
          if (!response.ok) {
            throw new Error(data.message || 'Failed to update category');
          }
  
          this.successMessage = 'Category updated successfully!';
          this.fetchCategories(); // Refresh the list
          this.editModalInstance.hide(); // Close the edit modal
        } catch (error) {
          this.errorMessage = error.message;
        }
      },
      confirmDeleteCategory(categoryId) {
        this.categoryIdToDelete = categoryId;
        // Show the Bootstrap modal
        this.deleteModalInstance = new window.bootstrap.Modal(document.getElementById('deleteModal'));
        this.deleteModalInstance.show();
      },
      async deleteCategory() {
        if (this.categoryIdToDelete === null) return;
  
        try {
          const response = await fetch(`/Backend/category_api/CategoryDeleteAPI.php`, {
            method: 'DELETE',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({ category_id: this.categoryIdToDelete })
          });
  
          const data = await response.json();
  
          if (!response.ok) {
            throw new Error(data.message || 'Failed to delete category');
          }
  
          this.successMessage = 'Category deleted successfully!';
          this.fetchCategories(); // Refresh the list
          this.categoryIdToDelete = null; // Reset the id
  
          // Close the delete modal after deletion
          if (this.deleteModalInstance) {
            this.deleteModalInstance.hide();
          }
        } catch (error) {
          this.errorMessage = error.message;
        }
      }
    },
    mounted() {
      this.fetchCategories();
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
  
  
  
  
  