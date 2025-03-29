<template> 
<div>
    <div class="d-flex btn-btm" style="align-items: end;">
        <a href="/inventory"><div class="btn-container"><img src="/storage/assets/btnicon-1.png"> ถังเก็บปลา </div></a>
      <a href="/shop"><div class="btn-container"><img src="/storage/assets/btnicon-2.png"> ร้านค้า </div></a>
      <a href="/leaderboard"><div class="btn-container"><img src="/storage/assets/btnicon-3.png"> ตารางอันดับ </div></a>
    </div>
    <div style="position: relative; z-index: 999;">
      <a href="/" ref="logo" class="logo-container"><img src="/storage/assets/logo.svg" alt="Logo" class="logo" width="130"></a>
      <header class="d-flex justify-content-between align-items-center p-4">
        <div>
          <div class="dropdown ms-2" style="display: flex; gap: 10px;">
            <div class="coin"><button class="dropdown-toggle" type="button" id="userMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
              <img src="/storage/assets/person.svg"> {{ user ? user.username : 'Guest' }}
            </button>
            <ul class="dropdown-menu" aria-labelledby="userMenuButton">
              <li><div v-if="!user" @click="navigateTo('/login')" class="">เข้าสู่ระบบ</div></li>
              <li><div v-if="!user" @click="navigateTo('/register')" class="">สมัครสมาชิก</div></li>
              <!-- <li><div v-if="user" @click="navigateTo('/inventory')">ถังเก็บปลา</div></li> -->
              <li><div v-if="user" @click="logout" class="">ออกจากระบบ</div></li>
              <li><div v-if="user && user.role === 'admin'" @click="navigateTo('/admin')" class="">เข้าหลังบ้าน</div></li>
              <!-- <li><div v-if="user" @click="navigateTo('/leaderboard')">Leader Board</div></li> -->
            </ul></div>
            <div class="coin"><img src="/storage/assets/coin.png" width="24"> {{ user.coin }}</div>
          </div>
        </div>
      </header>

  <div class="container mt-5">
    <div class="title">ร้านค้า</div>
    <div class="row">
      <div class="col-md-3 mb-4" v-for="rod in rods.data" :key="rod.id">
        <div class="card">
          <img :src="`/storage/${rod.image}`" class="card-img-top" :alt="rod.name">
          <div class="card-body">
            <h5 class="card-title">{{ rod.name }}</h5>
            <p class="card-text"><img src="/storage/assets/coin.png" width="24"> {{ rod.price }}</p>
            <button 
              class="btn btn-primary w-100" 
              @click="buyRod(rod)" 
              :disabled="user.coin < rod.price || ownedRodIds.includes(rod.id)"> 
              <span v-if="ownedRodIds.includes(rod.id)">คุณมีเบ็ดนี้อยู่แล้ว</span>
              <span v-else-if="user.coin >= rod.price">ซื้อ</span>
              <span v-else>คริสตัลไม่เพียงพอ</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
</div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'ShopComponent',
  props: {
    user: {
      type: Object,
      required: true,
    },
    rods: {
      type: Array,
      required: true,
    },
  },
  data() {
    return {
      ownedRodIds: [],
    };
  },
  methods: {
    async fetchUserOwnedRods() {
      try {
        const response = await axios.get('/api/user-owned-rods');
        this.ownedRodIds = response.data.ownedRods;
        console.log('Owned Rod IDs:', this.ownedRodIds);
      } catch (error) {
        console.error('Error fetching owned rods:', error);
      }
    },
    buyRod(rod) {
      if (this.ownedRodIds.includes(rod.id)) {
        alert('คุณมีเบ็ดนี้อยู่แล้ว!');
        return;
      }

      axios.post('/api/rods/buy', { rod_id: rod.id })
        .then(response => {
          alert(response.data.message);
          this.$emit('update-user', response.data.user);
          this.fetchUserOwnedRods(); // อัปเดตข้อมูลเบ็ดที่ผู้ใช้มี
        })
        .catch(error => {
          alert(error.response.data.error || 'An error occurred');
        });
    },
  },
  created() {
    this.fetchUserOwnedRods(); // เรียก API เมื่อคอมโพเนนต์ถูกสร้าง
  },
};
</script>

<style scoped>
.card {
  transition: transform 0.2s;
}
.card:hover {
  transform: scale(1.05);
}
</style>