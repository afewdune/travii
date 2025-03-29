<template>
  <div style="display: flex; height: 100vh">
    <div style="flex: 0.7; background: linear-gradient(rgb(252, 243, 255), rgb(188, 222, 251) 61.34%, rgb(97, 213, 202));">  
      <bubble-component></bubble-component>
      <a href="/">
        <div style="position: absolute; padding: 10px; top: 20px; left: 20px; cursor: pointer; gap: 5px; display: flex; justify-content: center;">
          <span class="material-symbols-outlined">
arrow_back
</span> ย้อนกลับ
      </div>
      </a>
    </div>
  <div class="container mt-5 d-fle login" id="loginBG">
    <div style="width: 400px;">
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
      <button type="submit" class="btn btn-primary w-100">เข้าสู่ระบบ</button>
      <div v-if="error" class="error mt-3 text-danger">{{ error }}</div>
      <br><br>ยังไม่มีบัญชี? <a href="/register"><u>สมัครสมาชิก</u></a>
    </form>
    </div>
  </div>
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