<template>
  <div>
    <div style="position: relative; z-index: 999;">
      <a href="/" ref="logo" class="logo-container"><img src="/storage/assets/logo.svg" alt="Logo" class="logo" width="130"></a>
      <header class="d-flex justify-content-between align-items-center p-4">
        <div>
          <div class="dropdown ms-2" style="display: flex; gap: 10px;">
            <div class="coin"><button class="dropdown-toggle" type="button" id="userMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
              <img src="/storage/assets/person.svg"> {{ localUser ? localUser.username : 'Guest' }}
            </button>
            <ul class="dropdown-menu" aria-labelledby="userMenuButton">
              <li><div v-if="!localUser" @click="navigateTo('/login')" class="">เข้าสู่ระบบ</div></li>
              <li><div v-if="!localUser" @click="navigateTo('/register')" class="">สมัครสมาชิก</div></li>
              <!-- <li><div v-if="localUser" @click="navigateTo('/inventory')">ถังเก็บปลา</div></li> -->
              <li><div v-if="localUser" @click="logout" class="">ออกจากระบบ</div></li>
              <li><div v-if="localUser && localUser.role === 'admin'" @click="navigateTo('/admin')" class="">เข้าหลังบ้าน</div></li>
              <!-- <li><div v-if="localUser" @click="navigateTo('/leaderboard')">Leader Board</div></li> -->
            </ul></div>
            <div class="coin"><img src="/storage/assets/coin.png" width="24"> {{ user.coin }}</div>
          </div>
        </div>
      </header>

    </div>

    <div class="home container mt-5">
      <div v-if="localUser">
        <fishing-panic-component @status-change="handleStatusChange"></fishing-panic-component>
        <inventory-component v-for="fish in fishList" :key="fish.FishID" :fish="fish" @update-user="updateUser"></inventory-component>
      </div>
      <div v-else>
        <a href="/login"><button id="fishingBtn"><b>เข้าสู่</b>ระบบ</button></a>
        <div style="width: 100%; height: 100vh; position: absolute; top: 0; left: 0; z-index: -1; background: linear-gradient(180deg, #FEFFB2 0%, #FFF 34.94%, #FF4ECA 100%);">
          <div id="dc1"></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { useToast } from 'vue-toastification';
import FishingPanicComponent from './FishingPanicComponent.vue';
import InventoryComponent from './InventoryComponent.vue';

export default {
  name: 'HomeComponent',
  components: {
    FishingPanicComponent,
    InventoryComponent
  },
  props: {
    user: {
      type: Object,
      default: null
    }
  },
  data() {
    return {
      localUser: this.user,
      user: {},
      fishList: []
    };
  },
  watch: {
    user(newUser) {
      this.localUser = newUser;
    }
  },
  created() {
    axios.get('/api/user').then(response => {
      this.user = response.data;
    });

    axios.get('/api/fish').then(response => {
      this.fishList = response.data;
    });
  },
  methods: {
    async logout() {
      const toast = useToast();
      try {
        await axios.post('/logout');
        localStorage.removeItem('user');
        this.localUser = null;
        toast.success('Logged out successfully!');
        window.location.href = '/login';
      } catch (error) {
        toast.error('Failed to log out.');
      }
    },
    navigateTo(path) {
      window.location.href = path;
    },
    handleStatusChange(status) {
      this.$nextTick(() => {
        const logoContainer = this.$refs.logo;
        if (status !== 'idle') {
          logoContainer.classList.add('animate-logo');
        } else {
          logoContainer.classList.remove('animate-logo');
        }
      });
    },
    updateUser(updatedUser) {
      this.user = updatedUser;
    }
  }
};
</script>

<style scoped>
.home {
  text-align: center;
}

@keyframes logoAnimation {
    from {
        width: 70%;
        top: 120%;
    }
    to {
        width: 130px;
        top: 30px;
    }
}

.animate-logo .logo {
  animation: logoAnimation 3s ease-in-out forwards;
}
</style>