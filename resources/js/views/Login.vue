<template>
    <div class="auth-form">
      <h2>Login</h2>
      <form @submit.prevent="login">
        <div>
          <label for="email">Email</label>
          <input v-model="email" type="email" id="email" required />
        </div>
        <div>
          <label for="password">Password</label>
          <input v-model="password" type="password" id="password" required />
        </div>
        <button type="submit">Login</button>
        <div v-if="error" class="error">{{ error }}</div>
      </form>
      <p>Don't have an account? <router-link to="/register">Register here</router-link></p>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  import { ref } from 'vue';
  import { useRouter } from 'vue-router';
  
  export default {
    name: 'Login',
    setup() {
      const email = ref('');
      const password = ref('');
      const error = ref('');
      const router = useRouter(); // Use the Vue Router instance
  
      const login = async () => {
        try {
          const response = await axios.post('/api/login', {
            email: email.value,
            password: password.value
          });
          localStorage.setItem('token', response.data.token); // Save token to local storage
  
          // Get user role_id and redirect accordingly
          const role_id = response.data.user.role_id; // Assuming the API returns the user's role_id
          if (role_id === 1) {
            router.push({ name: 'admin' });
          } else if (role_id === 2) {
            router.push({ name: 'artist' });
          } else {
            router.push({ name: 'home' });
          }
        } catch (err) {
          error.value = err.response?.data?.message || 'Login failed';
        }
      };
  
      return {
        email,
        password,
        error,
        login
      };
    }
  };
  </script>
  
  <style scoped>
  .auth-form {
    max-width: 400px;
    margin: 0 auto;
  }
  .error {
    color: red;
  }
  </style>
  