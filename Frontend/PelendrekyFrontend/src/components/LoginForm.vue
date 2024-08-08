<template>
    <form>
      <div class="mb-3">
        <label for="loginInput" class="form-label">Login</label>
        <input type="text" class="form-control" id="loginInput" placeholder="Zadajte login">
      </div>
      <div class="mb-3">
        <label for="passwordInput" class="form-label">Heslo</label>
        <input type="password" class="form-control" id="passwordInput" placeholder="Zadajte heslo">
      </div>
      <button @click.prevent="emitLogin" class="btn custom-button-color">Guest</button>
    </form>
  </template>
  
  <script>
  export default {
    methods: {
      emitLogin() {
        this.$emit('login');
      },
    },
  };
  </script>