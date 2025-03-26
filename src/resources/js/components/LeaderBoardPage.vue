<template>
  <div class="container mt-5">
    <h1>Leader Board</h1>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Rank</th>
          <th>Player Name</th>
          <th>Total Worth</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(player, index) in leaderboard" :key="index">
          <td>{{ index + 1 }}</td>
          <td>{{ player.name }}</td>
          <td>{{ player.total_worth }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'LeaderBoardPage',
  data() {
    return {
      leaderboard: []
    };
  },
  created() {
    axios.get('/api/leaderboard').then(response => {
      this.leaderboard = response.data;
    });
  }
};
</script>

<style scoped>
.container {
  text-align: center;
}
</style>