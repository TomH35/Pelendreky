<template>
  <div class= "container mt-5 mb-5 d-flex justify-content-center">
  <div class= "row mt-5 mb-5">
    <div class="col-sm-6">
        <!-- Card 1 -->
        <div class="card mb-5">
          <div class="row g-0">
            <div class="col-md-4">
              <img src="/img/add-g273330623_1280.png" class="img-fluid rounded-start" alt="...">
            </div>
            <div class="col-md-8">
              <div class="card-body">
                <h5 class="card-title">Article Manager</h5>
                <router-link :to="{ name: 'adminArticleManager' }" class="nav-link">
                  <span class="btn custom-button-color custom-button-margin">Otvoriť</span>
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6">
        <!-- Card 2 -->
        <div class="card mb-5">
          <div class="row g-0">
            <div class="col-md-4">
              <img src="/img/add-g273330623_1280.png" class="img-fluid rounded-start" alt="...">
            </div>
            <div class="col-md-8">
              <div class="card-body">
                <h5 class="card-title">Category Manager</h5>
                <router-link :to="{ name: 'adminCategoryManager' }" class="nav-link">
                  <span class="btn custom-button-color custom-button-margin">Otvoriť</span>
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="container d-flex justify-content-center mt-3 mb-5">
      <button @click="handleLogout" class="btn custom-button-color">Logout</button>
  </div>
  </template>
  <script>
  import { useLoginStore } from '../stores/loginStore';
  import { useRouter } from 'vue-router';
  
  export default {
    name: 'adminPanel',
    data() {
      return {
        user: useLoginStore(),
        router: useRouter()
      }
    },
    methods: {
        async handleLogout() {
            try {
                const response = await fetch('./Backend/admin_api/AdminLogoutAPI.php', {
                  method: 'POST',
                  headers: {
                    'Authorization': `Bearer ${this.user.getToken()}`, // Include the JWT token in the header
                    'Content-Type': 'application/json'
                  }
            });

              if (!response.ok) {
                throw new Error('Failed to logout');
            } 

            this.user.clearToken(); // Clear the token from the store and local storage
            this.router.push({ name: 'adminLogin' }); // Redirect to the login page

          } catch (error) {
            console.error('Logout error:', error);
          }
      }
    }
  }
  </script>