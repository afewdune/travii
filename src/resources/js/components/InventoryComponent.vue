<template>
  <div class="col-6 col-md-4 col-lg-3 mb-4">
    <div class="card h-100 shadow-sm" @click="showDetails = true">
      <img :src="`/storage/${fish.FishImage}`" class="card-img-top" :alt="fish.FishName">
      <div class="card-body">
        <h5 class="card-title">{{ fish.FishName }}</h5>
        <p class="card-text">
          <strong>ความแรร์:</strong> <span :class="`${fish.FishRarity}`" id="fishRarity">{{ fish.FishRarity }}</span><br>
          <strong>Worth:</strong> {{ fish.FishWorth }}<br>
          <strong>Token Worth:</strong> {{ fish.FishTokenWorth }}<br>
          <strong>Count:</strong> {{ fish.count }}
        </p>
      </div>
    </div>
    <div v-if="showDetails" class="modal" @click.self="showDetails = false">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ fish.FishName }}</h5>
            <button type="button" class="close" @click="showDetails = false">
              <span>&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <img :src="`/storage/${fish.FishImage}`" class="img-fluid mb-3" :alt="fish.FishName">
            <p>
              <strong>ความแรร์:</strong> <span :class="`${fish.FishRarity}`" id="fishRarity">{{ fish.FishRarity }}</span><br>
              <strong>Worth:</strong> {{ fish.FishWorth }}<br>
              <strong>Token Worth:</strong> {{ fish.FishTokenWorth }}<br>
              <strong>Count:</strong> {{ fish.count }}
            </p>
            <button class="btn btn-success" @click="showSellModal = true">Sell Fish</button>
          </div>
        </div>
      </div>
    </div>
    <div v-if="showSellModal" class="modal" @click.self="showSellModal = false">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Sell Fish</h5>
            <button type="button" class="close" @click="showSellModal = false">
              <span>&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p>How many fish do you want to sell?</p>
            <input type="number" v-model="sellCount" :max="fish.count" min="1" class="form-control mb-3">
            <button class="btn btn-primary" @click="confirmSell">Confirm</button>
          </div>
        </div>
      </div>
    </div>
    <div v-if="showConfirmModal" class="modal" @click.self="showConfirmModal = false">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Confirm Sell</h5>
            <button type="button" class="close" @click="showConfirmModal = false">
              <span>&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p>Are you sure you want to sell {{ sellCount }} fish?</p>
            <button class="btn btn-danger" @click="sellFish">Yes, Sell</button>
            <button class="btn btn-secondary" @click="showConfirmModal = false">Cancel</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'InventoryComponent',
  props: {
    fish: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      showDetails: false,
      showSellModal: false,
      showConfirmModal: false,
      sellCount: 1
    };
  },
  methods: {
    confirmSell() {
      this.showSellModal = false;
      this.showConfirmModal = true;
    },
    sellFish() {
      axios.post('/api/sell-fish', { fishId: this.fish.FishID, count: this.sellCount })
        .then(response => {
          this.$emit('update-user', response.data.user);
          this.showConfirmModal = false;
          this.showDetails = false;
          location.reload();
        })
        .catch(error => {
          console.error('Error selling fish:', error);
        });
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
.modal {
  display: flex;
  justify-content: center;
  align-items: center;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
}
.modal-content {
  background-color: white;
  padding: 20px;
  border-radius: 5px;
  max-width: 500px;
  width: 100%;
}
.close {
  position: absolute;
  top: 10px;
  right: 10px;
  font-size: 24px;
  cursor: pointer;
}
</style>
