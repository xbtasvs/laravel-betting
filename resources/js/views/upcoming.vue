<template>
  <div class="main pages">
    <construction />
    <small class="w-100 text-center mt-5">{{ route }}</small>
    <div v-show="loading" class="loading" />
  </div>
</template>

<script>
export default {
  name: 'upcoming',
  data() {
    return {
      route: this.$route.name,
      loading: true
    }
  },
  mounted() {
    this.loading = false
  }
}
</script>
