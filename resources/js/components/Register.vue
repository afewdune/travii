<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=arrow_back" /><template>
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
  <div class="container mt-5 d-fle register" id="loginBG">
    <div style="width: 400px;">
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
      <button type="submit" class="btn btn-primary w-100">สมัครสมาชิก</button>
      <div v-if="errorMessage" class="error mt-3 text-danger">{{ errorMessage }}</div>
      <br><br>มีบัญชีอยู่แล้ว? <a href="/login"><u>เข้าสู่ระบบ</u></a>
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