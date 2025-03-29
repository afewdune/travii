<template>
  <div :class="['fishing-container', { 'caught-background': status === 'caught' }]" @mousedown="startMovingUp" @mouseup="stopMoving" @mouseleave="stopMoving" @keydown="stopMoving" @keyup="startMovingUp" tabindex="0">
    <div v-if="status === 'waiting'" class="waiting">
      <p v-if="countdown > 2">
        <span v-if="countdown % 3 === 0">...</span>
        <span v-if="countdown % 3 === 1">..</span>
        <span v-if="countdown % 3 === 2">.</span>
      </p>
      <p id="fishTrigger" v-else>
        !!! 
      </p>
      <video autoplay loop muted playsinline style="width: 100%; height: 100%; position: absolute; z-index: -1; top: 0; left: 0; object-fit: cover; pointer-events: none; border: none;">
        <source src="/storage/assets/fishwRod.mp4" type="video/mp4">
        Your browser does not support the video tag.
      </video>
    </div>
    <div v-if="status === 'panic'" class="panic">
      <h3>ปลากำลังกินเบ็ด! ขยับแถบสีเขียวให้คลุมตัวปลา</h3>
      <div style="display: flex; align-items: center; justify-content: center; width: fit-content; margin: auto; gap: 4px;">
        <div class="fishing-area">
        <div class="fish" :style="fishStyle"></div>
        <div class="green-bar" :style="greenBarStyle"></div>
      </div>
      <div class="progress-bar">
        <div class="progress" :style="{ height: progress + '%' }"></div>
      </div>
      </div>
    </div>
    <div v-if="status === 'caught'" class="caught">
      <img :src="fishImage" alt="Caught Fish" />
      
      <div style="margin-top: 100px">
        <span class="normalTxt">ยินดีด้วย! คุณได้รับ</span>
      </div>
      <div class="d-flex justify-content-between align-items-center" style="width: fit-content; margin: 0 auto 60px; gap: 20px;">
    <span :class="fishRarity" id="fishRarity">{{ fishRarity }}</span> 
    <span class="headerTxt">{{ fishName }}</span>
    </div>
    <div class="d-flex justify-content-between align-items-center" style="width: fit-content; margin: auto; gap: 10px;">
      <button @click="goHome" id="viismBtn"> <img src="/storage/assets/home.svg"> </button>
      <button @click="startFishing" id="viiBtn">ตกอีกครั้ง</button>
      </div>
    </div>
    <div v-if="status === 'failed'" class="failed">
      <h1>ปลาหนีคุณไปแล้ว!</h1>
      <br><br><br><br> <img src="/storage/assets/fail.png" width="400"> <br>
      <div class="d-flex justify-content-between align-items-center" style="width: fit-content; margin: 10vh auto 0; gap: 10px;">
      <button @click="goHome" id="viismBtn"> <img src="/storage/assets/home.svg"> </button>
      <button @click="startFishing" id="viiBtn">ตกอีกครั้ง</button>
      </div>
    </div>
    <div v-if="status === 'idle'" class="idle">

      <div class="d-flex btn-btm" style="align-items: end;">
      <a href="/inventory"><div class="btn-container"><img src="/storage/assets/btnicon-1.png"> ถังเก็บปลา </div></a>
      <a href="/shop"><div class="btn-container"><img src="/storage/assets/btnicon-2.png"> ร้านค้า </div></a>
      <a href="/leaderboard"><div class="btn-container"><img src="/storage/assets/btnicon-3.png"> ตารางอันดับ </div></a>
    </div>




  <div class="rod">

    <!-- แสดงเบ็ดที่เลือก -->
    <div v-if="selectedRod">
      <h3>เบ็ดตกปลาที่เลือก</h3>
      <div class="rod-item">
        <img :src="selectedRod.image" alt="Rod Image" />
        <span>{{ selectedRod.name }}</span>
        <p>โอกาสจับปลา Common: {{ selectedRod.chance_common }}%</p>
        <p>โอกาสจับปลา Rare: {{ selectedRod.chance_rare }}%</p>
        <p>โอกาสจับปลา SSR: {{ selectedRod.chance_ssr }}%</p>
        <p>โอกาสจับปลา Special: {{ selectedRod.chance_special }}%</p>
      </div>
    </div>
    <div v-else>
      <p>คุณยังไม่ได้เลือกเบ็ดตกปลา</p>
    </div>

    <!-- แสดงเบ็ดทั้งหมดที่ผู้ใช้มี -->
    <div>
      <h3>เบ็ดตกปลาที่คุณมี</h3>
      <div v-if="ownedRods.length > 0">
        <div class="rod-item" v-for="rod in ownedRods" :key="rod.id">
          <img :src="rod.image" alt="Rod Image" />
          <span>{{ rod.name }}</span>
          <p>โอกาสจับปลา Common: {{ rod.chance_common }}%</p>
          <p>โอกาสจับปลา Rare: {{ rod.chance_rare }}%</p>
          <p>โอกาสจับปลา SSR: {{ rod.chance_ssr }}%</p>
          <p>โอกาสจับปลา Special: {{ rod.chance_special }}%</p>
        </div>
      </div>
      <div v-else>
        <p>คุณยังไม่มีเบ็ดตกปลา</p>
        <a href="/shop" class="btn">ไปที่ร้านค้า</a>
      </div>
    </div>
  </div>
    




  <bubble-component></bubble-component>

    <div id="dc1"></div>
    <button @click="startFishing" id="fishingBtn">เริ่ม<b>ตกปลา</b></button>
    </div>
  </div>
</template>
<script>
import axios from 'axios';

export default {
  props: {
    selectedRod: {
      type: Object,
      required: false, // อนุญาตให้เป็น null ได้
      default: null,
    },
    ownedRods: {
      type: Array,
      required: false,
      default: () => [],
    },
  },
  data() {
    return {
      status: 'idle',
      countdown: 0,
      fishName: '',
      fishImage: '',
      fishRarity: '',
      fishY: 0,
      greenBarY: 0,
      progress: 0,
      fishInterval: null,
      moveInterval: null,
      progressInterval: null,
      gravityInterval: null,
      speedChangeInterval: null,
      immortalTime: 5,
      initialGravitySpeed: 0.5,
    };
  },
  watch: {
    status(newStatus) {
      this.$emit('status-change', newStatus);
    }
  },
  computed: {
    fishStyle() {
      return {
        top: `${this.fishY}px`,
        transition: 'top 0.1s ease',
      };
    },
    greenBarStyle() {
      return {
        top: `${this.greenBarY}px`,
      };
    },
    filterRod() {
      const selectedRod = this.rods.find(rod => rod.id === this.user.rod_id) || null;
      this.user.selectedRod = selectedRod;
      return selectedRod;
    }
  },
  methods: {


    async fetchUserRods() {
      try {
        const response = await axios.get('/api/user-rods');
        this.selectedRod = response.data.selectedRod;
        this.ownedRods = response.data.ownedRods;
        console.log('Selected Rod:', this.selectedRod);
        console.log('Owned Rods:', this.ownedRods);
      } catch (error) {
        console.error('Error fetching user rods:', error);
      }
    },



    startFishing() {
      this.status = 'waiting';
      this.countdown = Math.floor(Math.random() * 15) + 10;
      this.fishName = '';
      this.fishImage = '';
      this.fishRarity = '';
      this.waitForFish();
    },
    waitForFish() {
      const interval = setInterval(() => {
        this.countdown--;
        if (this.countdown <= 0) {
          clearInterval(interval);
          this.startPanic();
        }
      }, 1000);
    },
    startPanic() {
      this.status = 'panic';
      this.fishY = Math.random() * 450;
      this.greenBarY = this.fishY; 
      this.progress = 0;
      this.immortalTime = 5;
      this.moveFish();
      this.updateProgress();
      this.applyGravity();
      this.changeFishSpeed();
    },
moveFish() {
      this.fishInterval = setInterval(() => {
      this.fishY += (Math.random() - 0.5) * 100;
      if (this.fishY < 0) this.fishY = 0;
      if (this.fishY > 450) this.fishY = 450;
      }, 250);
    },

startMovingUp() {
  this.currentGravitySpeed = 0;
  clearInterval(this.gravityInterval); // หยุดการตกจากแรงโน้มถ่วงเมื่อเริ่มเคลื่อนที่ขึ้น
  let speed = 3; // ใช้ความเร็วคงที่ในการขึ้น
  this.moveInterval = setInterval(() => {
    this.greenBarY -= speed;
    if (this.greenBarY < 0) this.greenBarY = 0; // หยุดเมื่อถึงขอบบน
  }, 15); // เคลื่อนที่ขึ้นทุก 15ms
},

applyGravity() {
  if (this.gravityInterval) {
    clearInterval(this.gravityInterval); 
  }

  clearInterval(this.moveInterval); // หยุดการเคลื่อนที่จากปุ่ม
  this.gravityInterval = setInterval(() => {
    this.greenBarY += 3; // ใช้ความเร็วการตกลง 2 ทุกๆ 15ms

    if (this.greenBarY >= 400) {
      this.greenBarY = 400; // หยุดที่ตำแหน่งสุดท้ายที่ 400
      clearInterval(this.gravityInterval); // หยุด gravity เมื่อถึงตำแหน่งสุดท้าย
      this.gravityInterval = null; 
    }
  }, 15); // เคลื่อนที่ลงทุก 15ms
},

resetGravitySpeed() {
  clearInterval(this.gravityInterval);
  this.applyGravity(); // รีเซ็ต gravity เมื่อผู้ใช้หยุดการเคลื่อนที่
},

stopMoving() {
  clearInterval(this.moveInterval);
  this.currentGravitySpeed = 0; // รีเซ็ต gravity speed เมื่อผู้ใช้หยุด
  this.applyGravity(); // เริ่ม gravity ใหม่เมื่อหยุดเคลื่อนที่
},

updateProgress() {
  this.progressInterval = setInterval(() => {
    const fishTop = this.fishY;
    const fishBottom = this.fishY + 50; // Assuming the fish height is 50px
    const greenBarTop = this.greenBarY;
    const greenBarBottom = this.greenBarY + 100; // Assuming the green bar height is 200px

    if (fishBottom > greenBarTop && fishTop < greenBarBottom) {
      this.progress += 1;
    } else if (this.immortalTime <= 0) {
      this.progress -= 1;
    }

    if (this.progress >= 100) {
      this.catchFish();
    } else if (this.progress <= 0 && this.immortalTime <= 0) {
      this.failSkillCheck();
    }

    if (this.immortalTime > 0) {
      this.immortalTime -= 0.1;
    }
  }, 80);
},
    // async catchFish() {
    //   clearInterval(this.fishInterval);
    //   clearInterval(this.progressInterval);
    //   clearInterval(this.gravityInterval);
    //   clearInterval(this.speedChangeInterval);
    //   this.stopMoving();

    //   try {
    //     const selectedRod = this.user.selectedRod;
    //     if (!selectedRod) {
    //       console.error('No fishing rod selected.');
    //       this.failSkillCheck();
    //       return;
    //     }

    //     // โอกาสของแต่ละ rarity จาก selectedRod
    //     const rarityChances = {
    //       common: selectedRod.chance_common || 50,
    //       rare: selectedRod.chance_rare || 30,
    //       SSR: selectedRod.chance_ssr || 15,
    //       special: selectedRod.chance_special || 5,
    //     };

    //     // สุ่มหา rarity โดยใช้โอกาส
    //     const rarity = this.getRandomRarity(rarityChances);

    //     // ดึงข้อมูลปลาจาก API โดยระบุ rarity
    //     const response = await axios.get(`/api/fish/random?rarity=${rarity}`);
    //     if (response.data && response.data.FishName && response.data.FishImage && response.data.FishRarity) {
    //       this.fishName = response.data.FishName;
    //       this.fishImage = `/storage/${response.data.FishImage}`;
    //       this.fishRarity = response.data.FishRarity;
    //       this.status = 'caught';

    //       // บันทึกข้อมูลการตกปลา
    //       await axios.post('/api/fish/record', {
    //         fish_id: response.data.FishID,
    //       });
    //       console.log('Fish record saved successfully');
    //     } else {
    //       console.error('Invalid response data:', response.data);
    //       this.failSkillCheck();
    //     }
    //   } catch (error) {
    //     console.error('Error fetching fish data:', error);
    //     this.failSkillCheck();
    //   }
    // },

    catchFish() {
      clearInterval(this.fishInterval);
      clearInterval(this.progressInterval);
      clearInterval(this.gravityInterval);
      clearInterval(this.speedChangeInterval);
      this.stopMoving();
      axios.get('/api/fish/random').then(response => {
        if (response.data && response.data.FishName && response.data.FishImage && response.data.FishRarity) {
          this.fishName = response.data.FishName;
          this.fishImage = `/storage/${response.data.FishImage}`;
          this.fishRarity = response.data.FishRarity;
          this.status = 'caught';
          // บันทึกข้อมูลการตกปลา
          axios.post('/api/fish/record', {
            fish_id: response.data.FishID,
          }).then(() => {
            console.log('Fish record saved successfully');
          }).catch(error => {
            console.error('Error saving fish record:', error);
          });
        } else {
          console.error('Invalid response data:', response.data);
          this.failSkillCheck();
        }
      }).catch(error => {
        console.error('Error fetching fish data:', error);
        this.failSkillCheck();
      });
    },

    getRandomRarity(rarityChances) {
      const totalChance = Object.values(rarityChances).reduce((sum, chance) => sum + chance, 0);
      const random = Math.random() * totalChance;
      let cumulative = 0;

      for (const [rarity, chance] of Object.entries(rarityChances)) {
        cumulative += chance;
        if (random <= cumulative) {
          return rarity;
        }
      }

      return 'common'; // Default fallback
    },
    failSkillCheck() {
      clearInterval(this.fishInterval);
      clearInterval(this.progressInterval);
      clearInterval(this.gravityInterval);
      clearInterval(this.speedChangeInterval);
      this.stopMoving();
      this.status = 'failed';
    },
    goHome() {
      this.status = 'idle';
    },
    handleKeyDown(event) {
      if (event.key === 'ArrowUp') {
        this.startMovingUp();
      }
    },
    handleKeyUp(event) {
      if (event.key === 'ArrowUp') {
        this.stopMoving();
      }
    },
  },
  created() {
    axios.get('/api/user').then(async (response) => {
        this.user = response.data;
        console.log(this.user); 

      if (this.user.rod_id) {
        try {
          // ดึงข้อมูลเบ็ดตกปลาจาก API โดยใช้ rod_id
          const rodResponse = await axios.get(`/api/rods/${this.user.rod_id}`);
          this.user.selectedRod = rodResponse.data;
        } catch (error) {
          console.error('Error fetching rod data:', error);
          this.user.selectedRod = null;
        }
      } else {
        console.warn('No rod_id found for the user.');
        this.user.selectedRod = null;
      }
    });
    this.fetchUserRods();
  }
};
</script>

<style scoped>
.fishing-container {
  text-align: center;
  margin-top: 50px;
}
.fishing-area {
  position: relative;
  width: 50px;
  height: 500px;
  margin: 20px auto;
  background-color: transparent;
  border: 1px solid #ccc;
}
.fish {
  position: absolute;
  width: 50px;
  height: 50px;
  background: url(/storage/assets/fish.svg) no-repeat center/contain;
  transition: top 0.001s ease-in-out; /* Smooth transition for fish movement */
  z-index: 90;
}
.green-bar {
  position: absolute;
  width: 100%;
  height: 100px; /* Increased height */
  background: #bfa;
  opacity: 1;
}
.progress-bar {
  width: 20px;
  height: 500px;
  background-color: transparent;
  margin: 20px auto;
  border: 1px solid #ccc;
  position: relative;
}
.progress {
  width: 100%;
  height: 100%;
  background: linear-gradient(#BFA, #BFA,rgb(255, 233, 134),rgb(255, 193, 170));
  background-attachment: fixed;
  position: absolute;
  bottom: 0;
  border-radius: 0;
}
</style>