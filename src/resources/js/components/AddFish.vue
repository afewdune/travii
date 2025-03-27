<template>
  <div class="add-fish container mt-5">
    <h2>Add Fish</h2>
    <form @submit.prevent="addFish">
      <div class="mb-3">
        <label for="fishName" class="form-label">Fish Name:</label>
        <input type="text" v-model="fish.FishName" class="form-control" id="fishName" required />
      </div>
      <div class="mb-3">
        <label for="fishRarity" class="form-label">Fish Rarity:</label>
        <select v-model="fish.FishRarity" class="form-control" id="fishRarity" required>
          <option value="COMMON">COMMON</option>
          <option value="RARE">RARE</option>
          <option value="SR">SR</option>
          <option value="SSR">SSR</option>
          <option value="NFT">NFT</option>
          <option value="SPECIAL">SPECIAL</option>
        </select>
      </div>
      <div class="mb-3">
        <label for="fishWorth" class="form-label">Fish Worth:</label>
        <input type="number" v-model="fish.FishWorth" class="form-control" id="fishWorth" required />
      </div>
      <div class="mb-3">
        <label for="fishTokenWorth" class="form-label">Fish Token Worth:</label>
        <input type="number" v-model="fish.FishTokenWorth" class="form-control" id="fishTokenWorth" required />
      </div>
      <button type="submit" class="btn btn-primary">Add Fish</button>
    </form>
  </div>
</template>

<script>
import axios from 'axios';
import { useToast } from 'vue-toastification';

export default {
  data() {
    return {
      fish: {
        FishName: '',
        FishRarity: 'COMMON',
        FishWorth: 0,
        FishTokenWorth: 0
      }
    };
  },
  methods: {
    async addFish() {
      const toast = useToast();
      try {
        await axios.post('/api/fish', this.fish);
        toast.success('Fish added successfully!');
        this.$router.push('/admin');
      } catch (error) {
        toast.error('Failed to add fish.');
        console.error("There was an error adding the fish:", error);
      }
    }
  }
};
</script>

<style scoped>
.add-fish {
  max-width: 600px;
  margin: auto;
}
</style>