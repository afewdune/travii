<template>
  <div>
    <a href="/"><img src="/storage/assets/logo.svg" alt="Logo" class="logo" width="130"></a>
    <header class="d-flex justify-content-between align-items-center p-4">
      <div>
        <div class="dropdown ms-2">
          <button class="dropdown-toggle" type="button" id="userMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="/storage/assets/person.svg"> {{ localUser ? localUser.username : 'Guest' }}
          </button>
          <ul class="dropdown-menu" aria-labelledby="userMenuButton">
            <li><a v-if="!localUser" href="/login"><div>เข้าสู่ระบบ</div></a></li>
            <li><a v-if="!localUser" href="/register"><div>สมัครสมาชิก</div></a></li>
            <li><a v-if="localUser" href="/inventory"><div>ถังเก็บปลา</div></a></li>
            <li><a v-if="localUser"><div @click="logout">ออกจากระบบ</div></a></li>
            <li><div v-if="localUser && localUser.role === 'admin'" @click="navigateTo('/admin')" class="">เข้าหลังบ้าน</div></li>
          </ul>
        </div>
      </div>
    </header>

    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <div>
                <div class="d-flex justify-content-between align-items-center ">
                  <h3>จัดการปลา</h3>
                  <button @click="navigateToAddFish" class="btn btn-primary mb-3">เพิ่มปลาใหม่</button>
                </div>
                <table class="table">
                  <thead>
                    <tr>
                      <th>ไอดีปลา</th>
                      <th>ชื่อปลา</th>
                      <th>ระดับ</th>
                      <th>มูลค่า</th>
                      <th>มูลค่า Token</th>
                      <th>รูปภาพ</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="fish in fishList" :key="fish.FishID">
                      <td>{{ fish.FishID }}</td>
                      <td>{{ fish.FishName }}</td>
                      <td>{{ fish.FishRarity }}</td>
                      <td>{{ fish.FishWorth }}</td>
                      <td>{{ fish.FishTokenWorth }}</td>
                      <td>
                        <img v-if="fish.FishImage" :src="`/storage/${fish.FishImage}`" :alt="fish.FishName" width="100">
                        <span v-else>No Image</span>
                      </td>
                      <td>
                        <button @click="editFish(fish.FishID)" class="btn btn-warning btn-sm">แก้ไขปลา</button>
                        <button @click="deleteFish(fish.FishID)" class="btn btn-danger btn-sm">ลบปลา</button>
                      </td>
                    </tr>
                  </tbody>
                </table>
                {{ $fishList->links() }}
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
import { useToast } from 'vue-toastification';
import Pagination from './____Pagination.vue'; // Assuming you have a Pagination component

export default {
  components: {
    Pagination
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
      loading: true,
      adminUser: {},
      fishList: [],
      totalFish: 0,
      currentPage: 1,
      toast: useToast()
    };
  },
  watch: {
    user(newUser) {
      this.localUser = newUser;
    }
  },
  created() {
    this.fetchAdminData();
    this.fetchFishData(this.currentPage);
  },
  methods: {
    fetchAdminData() {
      axios.get('/api/admin', {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('token')}`
        }
      })
      .then(response => {
        this.adminUser = response.data.adminData;
        this.fishList = response.data.fishList.data;
        this.totalFish = response.data.fishList.total;
        this.loading = false;
      })
      .catch(error => {
        console.error("There was an error fetching the admin data:", error);
        this.loading = false;
        this.toast.error('Failed to fetch admin data. Please try again later.');
      });
    },
    fetchFishData(page = 1) {
      this.loading = true;
      axios.get(`/api/fish?page=${page}`, {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('token')}`
        }
      })
      .then(response => {
        console.log('Fish data fetched:', response.data);
        this.fishList = response.data.data;
        this.totalFish = response.data.total;
        this.loading = false;
      })
      .catch(error => {
        console.error('There was an error fetching the fish data:', error);
        this.loading = false;
        this.toast.error('Failed to fetch fish data. Please try again later.');
      });
    },
    async logout() {
      const toast = useToast();
      try {
        await axios.post('/api/logout', {}, {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          }
        });
        localStorage.removeItem('user');
        this.localUser = null;
        toast.success('Logged out successfully!');
        window.location.href = '/';
      } catch (error) {
        toast.error('Failed to log out.');
      }
    },
    navigateToAddFish() {
      this.$router.push('/admin/add-fish');
    },
    editFish(fishID) {
      this.$router.push(`/admin/edit-fish/${fishID}`);
    },
    deleteFish(fishID) {
      axios.delete(`/api/fish/${fishID}`, {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('token')}`
        }
      })
      .then(() => {
        this.fetchFishData(this.currentPage);
      })
      .catch(error => {
        console.error("There was an error deleting the fish:", error);
      });
    }
  }
};
</script>

<style scoped>
.admin-panel {
  padding: 20px;
}
.admin-functions {
  margin-top: 20px;
}
.admin-functions button {
  margin-right: 10px;
}
.fish-management {
  margin-top: 20px;
}
</style>