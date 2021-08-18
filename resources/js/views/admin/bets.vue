<template>
  <div class="main pages">
    <construction />
    <small class="w-100 text-center mt-5">{{ route }}</small>
    <div v-show="loading" class="loading" />
  </div>
</template>

<script>
export default {
  name: 'admin-bets',
  data() {
    return {
      route: this.$route.name,
      loading: true
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
