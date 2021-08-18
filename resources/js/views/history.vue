<template>
  <div class="main pages justify-content-start align-items-start w-100">
    <div class="bets-headers p-3 text-center bg-primary text-white h3 w-100 mb-0">
      Bets History
    </div>
    <div v-if="slips" class="scroller">
      <div v-for="slip in slips" :key="slip.id" class="bets-rows">
        {{ slip }}
      </div>
    </div>
    <div v-else class="scroller text-center w-100 bg-dark d-flex flex-column justify-content-center align-items-center">
      <h4 class="my-3">
        No bet slips found in history.
      </h4>
      <router-link class="btn btn-primary mt-3" :to="{ name: 'active', params: {} }">
        Active Bets
      </router-link>
    </div>
    <div v-show="loading" class="loading" />
  </div>
</template>

<script>
export default {
  name: 'history',
  data() {
    return {
      loading: true,
      slips: undefined
    }
  },
  mounted() {
    this.getBets('history')
  },
  created() {
    axios.post('user').then((response) => {
      if (Object.keys(response.data).length !== 0) {
        this.loading = false
      } else {
        this.$router.push({ name: 'sports' })
      }
    })
  },
  methods: {
    getBets(param) {
      axios.post('get-bets', {
        params: {
          param: param
        }
      }).then((response) => {
        console.log(response)
        if (Object.keys(response.data).length !== 0) {
          this.slips = response.data
        }

        this.loading = false
      }).catch((error) => {
        console.error(error)
      })
    }
  }
}
  </script>
