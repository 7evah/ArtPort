<template>
    <div class="auth-form">
      <h2>Register</h2>
      <form @submit.prevent="register">
        <div>
          <label for="name">Name</label>
          <input v-model="name" type="text" id="name" required />
        </div>
        <div>
          <label for="email">Email</label>
          <input v-model="email" type="email" id="email" required />
        </div>
        <div>
          <label for="password">Password</label>
          <input v-model="password" type="password" id="password" required />
        </div>
        <div>
          <label for="password_confirmation">Confirm Password</label>
          <input v-model="password_confirmation" type="password" id="password_confirmation" required />
        </div>
        <div>
          <label for="role">Role</label>
          <select v-model="role" id="role" required>
            <option value="admin">Admin</option>
            <option value="artist">Artist</option>
            <option value="visitor">Visitor</option>
          </select>
        </div>
        <button type="submit">Register</button>
        <div v-if="error" class="error">{{ error }}</div>
      </form>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  import { ref } from 'vue';
  
  export default {
    name: 'Register',
    setup() {
      const name = ref('');
      const email = ref('');
      const password = ref('');
      const password_confirmation = ref('');
      const role = ref('artist'); // Default to artist or any other role
      const error = ref('');
  
      const register = async () => {
        if (password.value !== password_confirmation.value) {
          error.value = 'Passwords do not match';
          return;
        }
  
        try {
          const response = await axios.post('/api/register', {
            name: name.value,
            email: email.value,
            password: password.value,
            password_confirmation: password_confirmation.value,
            role: role.value,
          });
          localStorage.setItem('token', response.data.token); // Save token to local storage
          window.location.href = '/'; // Redirect to home page or dashboard
        } catch (err) {
          error.value = err.response.data.message || 'Registration failed';
        }
      };
  
      return {
        name,
        email,
        password,
        password_confirmation,
        role,
        error,
        register,
      };
    },
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
  