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
    </div>

    <div class="title">ตารางอันดับ</div>

    <div class="container mt-5" id="traviiStorageScroll">
      <div style="height: 70vh;
                  overflow-y: auto;
                  position: relative;">
      
          <div style="display: flex; flex-direction: column; gap: 80px;">
            <div v-for="(player, index) in leaderboard" :key="index">
                <div class="top-player" :class="'rank-' + (index + 1)">
                  <span style="opacity: 0.7;">อันดับ {{ index + 1 }}</span>
                  <h1>{{ player.name }}</h1>
                  <p><img src="/storage/assets/coin.png" width="24"> {{ player.total_worth }}</p>
                  <div id="fishTank">
                  <div v-for="fish in player.top_fish" :key="fish.FishID" id="fishBox">
                    <div style=""><img :src="`/storage/${fish.FishImage}`" width="200"></div>
                    <!-- <h4>{{ fish.FishName }}</h4>
                    <p><img src="/storage/assets/coin.png" width="24"> {{ fish.FishWorth }}</p> -->
                  </div>
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
  name: 'LeaderBoardComponent',
  props: {
    user: {
      type: Object,
      required: true
    },
    leaderboard: {
      type: Array,
      required: true
    }
  },
  methods: {
    navigateTo(path) {
      window.location.href = path;
    },
    logout() {
      axios.post('/logout').then(() => {
        window.location.href = '/login';
      });
    }
  }
};
</script>

<style scoped>
.container {
  text-align: center;
}
</style>