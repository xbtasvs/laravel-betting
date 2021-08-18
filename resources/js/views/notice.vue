<template>
  <div class="main pages">
    <h1 class="display-2 text-center notice">
      {{ title }}
    </h1>
    <h3 class="text-center">
      {{ text }}
    </h3>
    <div v-show="loading" class="loading" />
  </div>
</template>

<script>
export default {
  name: 'notice',
  props: ['title', 'text'],
  data() {
    return {
      loading: true
    }
  },
  mounted() {
    this.loading = false
  }
}
</script>
