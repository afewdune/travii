<template>
  <div class="register container mt-5">
    <h2>สมัครสมาชิก</h2>
    <form @submit.prevent="registerUser" class="needs-validation" novalidate>
      <div class="mb-3">
        <label for="username" class="form-label">ชื่อผู้ใช้</label>
        <input type="text" v-model="username" class="form-control" id="username" required />
        <div class="invalid-feedback">Please enter a username.</div>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">อีเมล</label>
        <input type="email" v-model="email" class="form-control" id="email" required />
        <div class="invalid-feedback">Please enter a valid email address.</div>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">รหัสผ่าน</label>
        <input type="password" v-model="password" class="form-control" id="password" required />
        <div class="invalid-feedback">Please enter a password.</div>
      </div>
      <div class="mb-3">
        <label for="password_confirmation" class="form-label">ยืนยันรหัสผ่าน</label>
        <input type="password" v-model="password_confirmation" class="form-control" id="password_confirmation" required />
        <div class="invalid-feedback">Please confirm your password.</div>
      </div>
      <button type="submit" class="btn btn-primary">สมัครสมาชิก</button>
      <div v-if="errorMessage" class="error mt-3 text-danger">{{ errorMessage }}</div>
    </form>
  </div>
</template>

<script>
import axios from 'axios';
import { useToast } from 'vue-toastification';

export default {
  data() {
    return {
      username: '',
      email: '',
      password: '',
      password_confirmation: '',
      errorMessage: ''
    };
  },
  methods: {
    async registerUser() {
      const toast = useToast();
      try {
        const response = await axios.post('/api/register', {
          username: this.username,
          email: this.email,
          password: this.password,
          password_confirmation: this.password_confirmation
        });
        if (response.data.success) {
          toast.success('Registration successful!');
          window.location.href = '/'; // เปลี่ยนเส้นทางไปยังหน้าหลัก
        } else {
          this.errorMessage = 'Registration failed.';
          toast.error(this.errorMessage);
        }
      } catch (error) {
        if (error.response && error.response.data && error.response.data.message) {
          this.errorMessage = error.response.data.message;
        } else {
          this.errorMessage = 'Registration failed.';
        }
        toast.error(this.errorMessage);
      }
    }
  }
};
</script>

<style scoped>
.register {
  max-width: 400px;
  margin: auto;
}
.error {
  color: red;
}
</style>