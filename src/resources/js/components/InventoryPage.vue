<template>
    <div>
    <div class="d-flex btn-btm">
      <div class="btn-container"><img src="/storage/assets/btnicon-1.png" v-if="localUser" @click="navigateTo('/inventory')"> ถังเก็บปลา </div>
      <div class="btn-container"><img src="/storage/assets/btnicon-2.png" v-if="localUser" @click="navigateTo('/shop')"> ร้านค้า </div>
      <div class="btn-container"><img src="/storage/assets/btnicon-3.png" v-if="localUser" @click="navigateTo('/leaderboard')"> ตารางอันดับ </div>
    </div>
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
    <div class="title">ถังเก็บปลา</div>
        <div class="container mt-5" id="traviiStorageScroll">
            
            <!-- {{ localUser.coin }} -->
            <div class="row">
                <inventory-component v-for="fish in groupedFish" :key="fish.FishID" :fish="fish"></inventory-component>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { useToast } from 'vue-toastification';
import InventoryComponent from './InventoryComponent.vue';

export default {
    name: 'InventoryPage',
    components: {
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
            fishRecords: []
        };
    },
    watch: {
        user(newUser) {
            this.localUser = newUser;
        }
    },
    computed: {
        groupedFish() {
            const fishMap = new Map();
            this.fishRecords.forEach(record => {
                const fish = record.fish;
                if (fish && fish.FishID) {
                    if (fishMap.has(fish.FishID)) {
                        fishMap.get(fish.FishID).count += 1;
                    } else {
                        fishMap.set(fish.FishID, { ...fish, count: 1 });
                    }
                }
            });
            return Array.from(fishMap.values());
        }
    },
    created() {
        this.fetchFishRecords();
    },
    methods: {
        async fetchFishRecords() {
            try {
                console.log('Fetching fish records...');
                const response = await axios.get('/api/fish/records', {
                    withCredentials: true // เพิ่มบรรทัดนี้เพื่อให้ axios ส่งคุกกี้ไปด้วย
                });
                console.log('Fish records fetched:', response.data);
                this.fishRecords = response.data;
            } catch (error) {
                console.error('Error fetching fish records:', error);
            }
        },
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
        updateUser(updatedUser) {
            this.localUser = updatedUser;
        }
    }
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