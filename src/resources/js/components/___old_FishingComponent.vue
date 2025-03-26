<template>
    <div class="fishing-container">
      <div v-if="status === 'waiting'" class="waiting">
        <p>Waiting for fish... {{ countdown }} seconds</p>
      </div>
      <div v-if="status === 'panic'" class="panic">
        <p>Fish is biting! Click the circles!</p>
        <div v-for="(circle, index) in visibleCircles" :key="index" :style="circle.style" class="circle" @click="clickCircle(index)"></div>
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
      <button v-if="status === 'idle'" @click="startFishing">Start Fishing</button>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        status: 'idle',
        countdown: 0,
        circles: [],
        visibleCircles: [],
        fishName: '',
        fishImage: '',
        maxCircles: 3,
        totalCircles: 0,
        gradients: [
          'radial-gradient(50% 50% at 50% 50%, rgba(61, 203, 255, 0.00) 46.5%, #3DCBFF 50%, rgba(152, 255, 184, 0.00) 78.5%)',
          'radial-gradient(50% 50% at 50% 50%, rgba(61, 255, 155, 0.00) 46.5%, #3DFF9B 50%, rgba(255, 252, 152, 0.00) 78.5%)',
          'radial-gradient(50% 50% at 50% 50%, rgba(47, 0, 255, 0.00) 46.5%, #2F00FF 50%, rgba(102, 219, 255, 0.00) 78.5%)',
        ], // กำหนด array ของ radial-gradient ที่ต้องการสุ่ม
      };
    },
    methods: {
      startFishing() {
        this.status = 'waiting';
        this.countdown = Math.floor(Math.random() * 11) + 5; // Random number between 5 and 15
        this.circles = [];
        this.visibleCircles = [];
        this.fishName = '';
        this.fishImage = '';
        this.totalCircles = Math.floor(Math.random() * 21) + 20; // Random number between 20 and 40
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
        this.addCircle();
      },
      addCircle() {
        if (this.visibleCircles.length >= this.maxCircles || this.circles.length >= this.totalCircles) return;
  
        const size = 300;
        let x, y, gradient, overlapping;
        do {
          x = Math.random() * (window.innerWidth - size);
          y = Math.random() * (window.innerHeight - size);
          gradient = this.gradients[Math.floor(Math.random() * this.gradients.length)]; // สุ่ม gradient จาก array
          overlapping = this.isOverlapping(x, y, size);
        } while (overlapping);
  
        const circle = {
          size,
          style: {
            width: `${size}px`,
            height: `${size}px`,
            top: `${y}px`,
            left: `${x}px`,
            position: 'absolute',
            background: gradient,
            borderRadius: '50%',
            transform: 'scale(1)',
            transition: 'transform 5s ease-out',
          },
        };
  
        this.visibleCircles.push(circle);
        this.shrinkCircle(circle, 5000); // ใช้เวลา 5 วินาที
  
        if (this.circles.length < this.totalCircles) {
          setTimeout(() => this.addCircle(), 500); // เพิ่มวงกลมใหม่ทุก 0.5 วินาที
        }
      },
      isOverlapping(x, y, size) {
        const minDistance = 300; // ระยะห่างขั้นต่ำ 300px
        for (let circle of this.visibleCircles) {
          const circleX = parseFloat(circle.style.left);
          const circleY = parseFloat(circle.style.top);
          const circleSize = parseFloat(circle.style.width);
          const distance = Math.sqrt(Math.pow(x - circleX, 2) + Math.pow(y - circleY, 2));
          if (distance < (size + circleSize) / 2 + minDistance) {
            return true;
          }
        }
        return false;
      },
      shrinkCircle(circle, duration) {
        setTimeout(() => {
          circle.style.transform = 'scale(0)';
          setTimeout(() => {
            this.visibleCircles = this.visibleCircles.filter(c => c !== circle);
            if (this.visibleCircles.length === 0 && this.circles.length === 0) {
              this.status = 'failed';
              this.resetGame();
            }
          }, duration);
        }, 0);
      },
      clickCircle(index) {
        this.visibleCircles.splice(index, 1);
        if (this.visibleCircles.length === 0 && this.circles.length === 0) {
          this.catchFish();
        }
      },
      catchFish() {
        axios.get('/api/fish/random').then(response => {
          this.fishName = response.data.FishName;
          this.fishImage = `/storage/${response.data.FishImage}`;
          this.status = 'caught';
          this.clearCircles();
          this.resetGame();
        });
      },
      goHome() {
        this.status = 'idle';
        this.clearCircles();
        this.resetGame();
      },
      clearCircles() {
        this.circles = [];
        this.visibleCircles = [];
      },
      resetGame() {
        this.status = 'idle';
        this.countdown = 0;
        this.circles = [];
        this.visibleCircles = [];
        this.fishName = '';
        this.fishImage = '';
      },
    },
  };
  </script>
  
  <style scoped>
  .fishing-container {
    text-align: center;
    margin-top: 50px;
  }
  .circle {
    cursor: pointer;
  }
  </style>