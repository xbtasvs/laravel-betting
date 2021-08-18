<template>
  <div class="main pages justify-content-start align-items-start">
    <div class="bets-headers p-3 text-center bg-primary text-white d-flex justify-content-between align-items-center w-100 mb-0">
      <h3 class="ml-3">
        Active Bets
      </h3>
      <p class="ml-auto">
        (needing API to get game data based on bet_odd_id)
      </p>
      <router-link class="btn btn-primary ml-3" :to="{ name: 'history', params: {} }">
        History Bets<i class="fas fa-history ml-2" />
      </router-link>
    </div>
    <div class="scroller w-100">
      <div v-for="slip in slips" :key="slip.id" class="bets-rows">
        <p>slip_id: {{ slip.id }}</p>
        <p>bet_amount: {{ slip.bet_amount }}</p>
        <p>total_win: {{ calculateWin(slip.bet_amount, slip.bets) }}</p>
        <div v-for="bet in slip.bets" :key="bet.id" class="d-flex justify-content-between align-items-center w-100">
          <p>bet_odd_id: {{ bet.bet_odd_id }}</p>
          <p>bet_odd_value: {{ bet.bet_odd_value }}</p>
          <p>bet_market_id: {{ bet.bet_market_id }}</p>
          <p>bet_event_id: {{ bet.bet_event_id }}</p>
        </div>
      </div>
    </div>
    <div v-show="loading" class="loading" />
  </div>
</template>

<script>
// import inplayData from '../data/inplay-sport-1.json'

export default {
  name: 'active',
  data() {
    return {
      loading: true,
      slips: undefined
    }
  },
  // computed: {
  //   eventsData() {
  //     var eventsArr = []
  //     var eventIdsArr = []
  //
  //     if (this.slips) {
  //       this.slips.forEach((item, i) => {
  //         item.bets.forEach((bet, i) => {
  //           eventIdsArr.push(bet.bet_event_id)
  //         })
  //       })
  //
  //       eventIdsArr.forEach((item, i) => {
  //         eventsArr.push(inplayData.sportEvents.filter(i => i.eventID === item))
  //       })
  //     }
  //
  //     return eventsArr
  //   }
  // },
  mounted() {
    this.getBets('active')
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
        this.slips = response.data
        this.loading = false
      }).catch((error) => {
        console.error(error)
      })
    },
    calculateWin(amount, bets) {
      var betOdds = []

      bets.forEach((item, i) => {
        betOdds.push(item.bet_odd_value)
      })

      var oddsSum = betOdds.reduce((a, b) => parseFloat(a) + parseFloat(b), 0)

      return amount * oddsSum
    }
  }
}
</script>
