<template>
  <div :class="['fishing-container', { 'caught-background': status === 'caught' }]" @mousedown="startMovingUp" @mouseup="stopMoving" @mouseleave="stopMoving" @keydown="handleKeyDown" @keyup="handleKeyUp" tabindex="0">
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
      <!-- <div class="rod">
        <img src="/storage/assets/rod.png" alt="">
      </div> -->
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
      <p>ปลาหนีคุณไปแล้ว!</p>
      <button @click="startFishing">ตกอีกครั้ง</button>
    </div>
    <div v-if="status === 'idle'" class="idle">

      <div class="d-flex btn-btm" style="align-items: end;">
      <a href="/inventory"><div class="btn-container"><img src="/storage/assets/btnicon-1.png"> ถังเก็บปลา </div></a>
      <a href="/inventory"><div class="btn-container"><img src="/storage/assets/btnicon-2.png"> ร้านค้า </div></a>
      <a href="/leaderboard"><div class="btn-container"><img src="/storage/assets/btnicon-3.png"> ตารางอันดับ </div></a>
    </div>
    <div class="rod"> <img src="/storage/assets/rod01.png" alt=""> เปลี่ยน</div>

      <div id="dc1"></div>
    <button @click="startFishing" id="fishingBtn">เริ่ม<b>ตกปลา</b></button>
    </div>
  </div>
</template>
<script>
import axios from 'axios';

export default {
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
      currentGravitySpeed: 0.5,
      user: {}
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
  },
  methods: {
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
      this.greenBarY = this.fishY; // Align green bar with fish initially
      this.progress = 0;
      this.immortalTime = 5;
      this.moveFish();
      this.updateProgress();
      this.applyGravity();
      this.changeFishSpeed();
    },
    moveFish() {
      this.fishInterval = setInterval(() => {
      this.fishY += (Math.random() - 0.5) * 200;
      if (this.fishY < 0) this.fishY = 0;
      if (this.fishY > 450) this.fishY = 450;
      }, 50); // Reduced interval time for faster movement
    },
    changeFishSpeed() {
      this.speedChangeInterval = setInterval(() => {
        clearInterval(this.fishInterval);
        const newSpeed = Math.random() * 100;
        const fastMove = Math.random() < 0.2;
        if (fastMove) {
          this.fishInterval = setInterval(() => {
            this.fishY = Math.random() * 400;
          }, 400);
        } else {
          this.fishInterval = setInterval(() => {
            this.fishY += (Math.random() - 0.5) * 20;
            if (this.fishY < 0) this.fishY = 0;
            if (this.fishY > 450) this.fishY = 450;
          }, newSpeed);
        }
      }, 500);
    },
    startMovingUp() {
      clearInterval(this.gravityInterval); // Stop gravity when user starts moving up
      this.currentGravitySpeed = this.initialGravitySpeed; // Reset gravity speed
      let speed = 1.5;
      this.moveInterval = setInterval(() => {
      this.greenBarY -= speed;
      if (this.greenBarY < 0) this.greenBarY = 0;
      speed += 0.1; // Increase speed over time
      }, 15);
    },
    applyGravity() {
      clearInterval(this.moveInterval);
      this.gravityInterval = setInterval(() => {
      this.greenBarY += this.currentGravitySpeed;
      if (this.greenBarY > 400) this.greenBarY = 400;
      this.currentGravitySpeed *= 1.01; // Increase speed exponentially
      }, 15);
    },
    resetGravitySpeed() {
      clearInterval(this.gravityInterval);
      this.applyGravity();
    },
    stopMoving() {
      clearInterval(this.moveInterval);
      this.currentGravitySpeed = this.initialGravitySpeed; // Reset gravity speed when user stops moving
      this.applyGravity(); // Reapply gravity when user stops moving
    },
    updateProgress() {
    this.progressInterval = setInterval(() => {
      const fishTop = this.fishY;
      const fishBottom = this.fishY + 20; // Assuming the fish height is 50px
      const greenBarTop = this.greenBarY;
      const greenBarBottom = this.greenBarY + 200; // Assuming the green bar height is 200px

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
    axios.get('/api/user').then(response => {
      this.user = response.data;
    });
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
  transition: top 0.1s ease; /* Smooth transition for fish movement */
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
  background:  #BFA;
  position: absolute;
  bottom: 0;
  border-radius: 0;
}
</style>