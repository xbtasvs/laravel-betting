<template>
  <div class="main pages">
    <div v-if="isAdmin" class="d-flex p-5 justify-content-between">
      <router-link class="card shadow-lg" :to="{ name: 'admin-bans', params: {} }">
        <i class="fas fa-users-cog mb-3" />
        <p>User Management</p>
      </router-link>
      <router-link class="card shadow-lg" :to="{ name: 'admin-bets', params: {} }">
        <i class="fas fa-tasks mb-3" />
        <p>Bets Management</p>
      </router-link>
      <router-link class="card shadow-lg" :to="{ name: 'admin-rules', params: {} }">
        <i class="fas fa-dice mb-3" />
        <p>Game Rules</p>
      </router-link>
    </div>
    <div v-show="loading" class="loading" />
  </div>
</template>

<script>
export default {
  name: 'dashboard',
  data() {
    return {
      loading: true,
      isAdmin: false
    }
  },
  created() {
    axios.post('user').then((response) => {
      if (response.status == 200 && response.data.id == 1) {
        this.isAdmin = true
        this.loading = false
      } else {
        this.$router.push({ name: 'sports' })
      }
    })
  }
}
</script>
