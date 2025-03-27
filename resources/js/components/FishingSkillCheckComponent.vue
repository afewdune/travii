<template>
  <div class="fishing-container">
    <div v-if="status === 'waiting'" class="waiting">
      <p>Waiting for fish... {{ countdown }} seconds</p>
    </div>
    <div v-if="status === 'panic'" class="panic">
      <p>Fish is biting! Perform the skill checks!</p>
      <div class="skill-check-container">
        <div class="skill-check-bar">
          <div class="skill-check-target" :style="targetStyle"></div>
          <div class="skill-check-indicator" :style="indicatorStyle"></div>
        </div>
        <button @click="performSkillCheck">Skill Check</button>
      </div>
    </div>
    <div v-if="status === 'caught'" class="caught">
      <p>You caught a fish!</p>
      <img :src="fishImage" alt="Caught Fish" />
      <p>{{ fishName }}</p>
      <button @click="startFishing">Fish Again</button>
      <button @click="goHome">Go Home</button>
    </div>
    <div v-if="status === 'failed'" class="failed">
      <p>You failed to catch the fish.</p>
      <button @click="startFishing">Try Again</button>
    </div>
    <button v-if="status === 'idle'" @click="startFishing">เริ่มตกปลา</button>
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
      skillCheckCount: 0,
      targetPosition: 0,
      indicatorPosition: 0,
      skillCheckSize: 20, // Initial size of the skill check target
      indicatorInterval: null, // Store the interval ID for the indicator
    };
  },
  computed: {
    targetStyle() {
      return {
        left: `${this.targetPosition}%`,
        width: `${this.skillCheckSize}%`,
      };
    },
    indicatorStyle() {
      return {
        left: `${this.indicatorPosition}%`,
      };
    },
  },
  methods: {
    startFishing() {
      this.status = 'waiting';
      this.countdown = 0;//Math.floor(Math.random() * 11) + 5; // Random number between 5 and 15
      this.fishName = '';
      this.fishImage = '';
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
      this.skillCheckCount = 0;
      this.startSkillCheck();
    },
    startSkillCheck() {
      this.targetPosition = Math.random() * 80; // Random position between 0% and 80%
      this.indicatorPosition = 0;
      this.skillCheckSize = 20 - this.skillCheckCount * 5; // Decrease size of the skill check target
      this.moveIndicator();
    },
    moveIndicator() {
      this.indicatorInterval = setInterval(() => {
        this.indicatorPosition += 1;
        if (this.indicatorPosition >= 100) {
          clearInterval(this.indicatorInterval);
          this.failSkillCheck();
        }
      }, 50); // Move indicator every 50ms
    },
    performSkillCheck() {
      const targetStart = this.targetPosition;
      const targetEnd = this.targetPosition + this.skillCheckSize;
      if (this.indicatorPosition >= targetStart && this.indicatorPosition <= targetEnd) {
        this.skillCheckCount++;
        clearInterval(this.indicatorInterval); // Clear the interval when skill check is performed
        if (this.skillCheckCount >= 3) {
          this.catchFish();
        } else {
          this.startSkillCheck();
        }
      } else {
        this.failSkillCheck();
      }
    },
    catchFish() {
      axios.get('/api/fish/random').then(response => {
        this.fishName = response.data.FishName;
        this.fishImage = `/storage/${response.data.FishImage}`;
        this.status = 'caught';
        clearInterval(this.indicatorInterval); // Clear the interval when fish is caught
      });
    },
    failSkillCheck() {
      this.status = 'failed';
      clearInterval(this.indicatorInterval); // Clear the interval when skill check fails
    },
    goHome() {
      this.status = 'idle';
    },
  },
};
</script>

<style scoped>
.fishing-container {
  text-align: center;
  margin-top: 50px;
}
.skill-check-container {
  position: relative;
  width: 300px;
  height: 50px;
  margin: 20px auto;
}
.skill-check-bar {
  position: relative;
  width: 100%;
  height: 100%;
  background-color: #ccc;
}
.skill-check-target {
  position: absolute;
  height: 100%;
  background-color: #f00;
}
.skill-check-indicator {
  position: absolute;
  height: 100%;
  width: 2px;
  background-color: #000;
}
</style>