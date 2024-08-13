import { defineStore } from 'pinia';

export const useLoginStore = defineStore({
  id: 'login',
  state: () => ({
    token: null,
  }),
  getters: {
    getToken(state) {
      return state.token;
    },
    userAuthorised(state) {
      return !!state.token; // Check if the token exists to determine if the user is authorized
    },
  },
  actions: {
    setToken(token) {
      this.token = token;
      localStorage.setItem('token', token);
    },
    clearToken() {
      this.token = null;
      localStorage.removeItem('token');
    },
    loadTokenFromLocalStorage() {
      const token = localStorage.getItem('token');
      if (token) {
        this.token = token;
      }
    },
  },
});