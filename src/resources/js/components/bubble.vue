<template>
  <div class="bubble-container" @mousemove="handleMouseMove">
    <div
      v-for="(bubble, index) in bubbles"
      :key="index"
      class="bubble"
      :style="{
        left: `${bubble.x + bubble.offset}px`,
        bottom: `${bubble.y}px`,
        width: `${bubble.size}px`,
        height: `${bubble.size}px`,
        opacity: bubble.opacity
      }"
    ></div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      bubbles: [],
      mouseX: window.innerWidth / 2,
    };
  },
  mounted() {
    this.createBubbles();
    this.animateBubbles();
    window.addEventListener("mousemove", this.handleMouseMove);
  },
  beforeUnmount() {
    window.removeEventListener("mousemove", this.handleMouseMove);
  },
  methods: {
    createBubbles() {
      for (let i = 0; i < 20; i++) {
        this.bubbles.push({
          x: Math.random() * window.innerWidth,
          y: Math.random() * window.innerHeight,
          size: Math.random() * 40 + 10,
          speed: Math.random() * 2 + 1,
          opacity: Math.random() * 0.5 + 0.3,
          offset: 0,
        });
      }
    },
    animateBubbles() {
      setInterval(() => {
        this.bubbles.forEach((bubble) => {
          bubble.y += bubble.speed;
          bubble.offset += (this.mouseX - bubble.x) * 0.001; // ให้ฟองสบู่ขยับเล็กน้อยตามเมาส์
          if (bubble.y > window.innerHeight) {
            bubble.y = -bubble.size;
            bubble.x = Math.random() * window.innerWidth;
          }
        });
      }, 50);
    },
    handleMouseMove(event) {
      this.mouseX = event.clientX;
    },
  },
};
</script>

<style scoped>
.bubble-container {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  overflow: hidden;
  pointer-events: none;
}
  
  .bubble {
    position: absolute;
    background: url(https://i.imgur.com/3zWcDjt.png) center/cover;
    border-radius: 50%;
    transition: transform 0.1s ease-out;
  }
  </style>