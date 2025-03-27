<template>
  <div class="edit-fish container mt-5">
    <h2>Edit Fish</h2>
    <form @submit.prevent="updateFish">
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
      <button type="submit" class="btn btn-primary">Update Fish</button>
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
  created() {
    this.fetchFishData();
  },
  methods: {
    fetchFishData() {
      const fishID = this.$route.params.id;
      axios.get(`/api/fish/${fishID}`)
        .then(response => {
          this.fish = response.data;
        })
        .catch(error => {
          console.error("There was an error fetching the fish data:", error);
        });
    },
    updateFish() {
      const toast = useToast();
      const fishID = this.$route.params.id;
      axios.put(`/api/fish/${fishID}`, this.fish)
        .then(() => {
          toast.success('Fish updated successfully!');
          this.$router.push('/admin');
        })
        .catch(error => {
          toast.error('Failed to update fish.');
          console.error("There was an error updating the fish:", error);
        });
    }
  }
};
</script>

<style scoped>
.edit-fish {
  max-width: 600px;
  margin: auto;
}
</style>