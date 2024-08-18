import { defineStore } from 'pinia';

export const useLoginStore = defineStore({
  id: 'login',
  state: () => ({
    token: null,
    user_id: null, // Store user_id
    user_is_admin: false // Store user_is_admin
  }),
  getters: {
    userAuthorised(state) {
      return !!state.token; // Check if the token exists to determine if the user is authorized
    },
  },
  actions: {
    setToken(token) {
      this.token = token;
      localStorage.setItem('token', token);
    },
    setUserInfo(user_id, user_is_admin) {
      this.user_id = user_id;
      this.user_is_admin = user_is_admin;
      localStorage.setItem('user_id', user_id);
      localStorage.setItem('user_is_admin', user_is_admin);
    },
    loadUserInfoFromLocalStorage() {
      const user_id = localStorage.getItem('user_id');
      const user_is_admin = localStorage.getItem('user_is_admin') === 'true';
      if (user_id) {
        this.user_id = user_id;
      }
      if (user_is_admin !== null) {
        this.user_is_admin = user_is_admin;
      }
    },
    clearToken() {
      this.token = null;
      this.user_id = null;
      this.user_is_admin = false;
      localStorage.removeItem('token');
      localStorage.removeItem('user_id');
      localStorage.removeItem('user_is_admin');
    },
    loadTokenFromLocalStorage() {
      const token = localStorage.getItem('token');
      if (token) {
        this.token = token;
      }
    },
    getToken() {
      return this.token;
    },
  },
});

