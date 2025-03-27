<template>
  <div class="login container mt-5">
    <h2>เข้าสู่ระบบ</h2>
    <form @submit.prevent="login">
      <div class="mb-3">
        <label for="username" class="form-label">ป้อนชื่อผู้ใช้</label>
        <input type="text" v-model="credentials.username" class="form-control" id="username" required />
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">ป้อนรหัสผ่าน</label>
        <input type="password" v-model="credentials.password" class="form-control" id="password" required />
      </div>
      <button type="submit" class="btn btn-primary">เข้าสู่ระบบ</button>
      <div v-if="error" class="error mt-3 text-danger">{{ error }}</div>
    </form>
  </div>
</template>

<script>
import axios from 'axios';
import { useToast } from 'vue-toastification';

export default {
  data() {
    return {
      credentials: {
        username: '',
        password: ''
      },
      error: null
    };
  },
  methods: {
    async login() {
      const toast = useToast();
      this.error = null;
      try {
        const response = await axios.post('/login', this.credentials);
        localStorage.setItem('user', JSON.stringify(response.data.user));
        toast.success('Logged in successfully!');
        window.location.href = '/';
      } catch (err) {
        this.error = 'Invalid credentials. Please try again.';
        toast.error(this.error);
      }
    }
  }
};
</script>

<style scoped>
.login {
  max-width: 400px;
  margin: auto;
}
.error {
  color: red;
}
</style>