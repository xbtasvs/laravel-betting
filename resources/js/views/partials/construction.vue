<template>
  <div class="d-flex flex-column">
    <img class="mb-2 construction" :src="img" alt="construction">
    <h3 class="text-center">
      Under construction...
    </h3>
  </div>
</template>

<script>
export default {
  name: 'construction',
  data() {
    return {
      img: 'img/uc.gif'
    }
  }
}
</script>
