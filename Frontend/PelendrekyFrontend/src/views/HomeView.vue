<template>
  <main>
    <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
      <div class="card w-50">
        <div class="card-body">
          <h5 class="card-title text-center">Prihlásiť sa</h5>
          <LoginForm @login="loginAsGuest" />
        </div>
      </div>
    </div>
  </main>
</template>

<script>
import { useLoginStore } from '../stores/loginStore';
import LoginForm from '../components/LoginForm.vue';
import { useRouter } from 'vue-router';

export default {
  components: {
    LoginForm,
  },
  setup() {
    const store = useLoginStore();
    const router = useRouter();

    const loginAsGuest = async () => {
      await store.loginAsGuest();
      router.push('/BeehiveSelection');
    };

    return {
      loginAsGuest,
    };
  },
};
</script>
