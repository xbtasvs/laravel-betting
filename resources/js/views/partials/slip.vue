<template>
  <div v-if="finalEvents" ref="slipMain" class="slip shadow-lg">
    <movable target="slipMain" pos-top="0" pos-left="0" class="slip-header d-flex w-100 p-3 border-bottom text-white" :class="miniSlip ? 'mini' : null">
      <h4>
        Bet Slip
      </h4>
    </movable>
    <div class="d-flex position-relative float-right mover" @click="restartSlip">
      <i class="fas fa-2x fa-times" />
    </div>
    <div class="d-flex position-relative float-right mover" @click="toggleSlip">
      <i class="fas fa-2x" :class="!miniSlip ? 'fa-minus' : 'fa-plus'" />
    </div>
    <div class="slip-container w-100 d-flex flex-column" :class="miniSlip ? 'mini' : null">
      <div class="limiter shadow-lg">
        <div v-for="(game, index) in finalEvents" :key="index" class="d-flex border-bottom border-dark game-container px-3 py-2 w-100">
          <div class="d-flex flex-column w-75">
            <p v-for="team in game.sportEvent.teams" :key="team.id" class="text-white">
              {{ team.name }}
            </p>
          </div>
          <div v-if="game.betOdd.tagOrExtraData" v-tooltip="{ content:
            `<div class='d-flex flex-column'>
              <small>` + game.sportEvent.markets[0].name + `</small>
              <small class='my-2'>` + game.betOdd.alternativeName + `</small>
              <p class='text-accent font-weight-bold'>` + game.betOdd.name + `</p>
              <small class='mt-3'>` + game.betOdd.handicap + `</small>
            </div>` }" class="d-flex justify-content-center align-items-center px-4"
          >
            <i class="fas fa-info" />
          </div>
          <div v-else class="d-flex justify-content-center align-items-center ml-3">
            <!-- <p v-if="game.betOdd.tagOrExtraData" class="text-right">
              {{ game.betOdd.tagOrExtraData }}
            </p> -->
            <p v-if="game.betOdd.alternativeName" class="text-right">
              {{ game.betOdd.alternativeName }}
            </p>
            <p v-else class="text-right">
              {{ game.betOdd.name }}
            </p>
          </div>
          <div class="d-flex justify-content-center align-items-center ml-auto">
            <p class="text-right">
              {{ odds_preference ? game.betOdd.odd : fractionToFloat(game.betOdd.odd) }}
            </p>
          </div>
          <div class="slip-close" @click="removeBet(game)">
            <i class="fas fa-times" />
          </div>
        </div>
      </div>
      <div class="p-3">
        <div class="justify-content-between align-items-center w-100 mt-2 px-1" :class="oddsChanged ? 'd-none' : 'd-flex'">
          <p class="text-left text-danger font-weight-bold">
            Odds changed. Do you accept changes?
          </p>
          <a class="ml-auto mr-3 text-white link" @click="placeBet">Yes</a>
          <a class="text-white link" @click="oddsChanged = true">No</a>
        </div>
        <div class="d-flex justify-content-between w-100 mt-2 px-1">
          <p class="text-left">
            Total Odd:
          </p>
          <p class="text-right">
            {{ odds_preference ? floatToFraction(totalOdds) : totalOdds.toFixed(2) }}
          </p>
        </div>
        <div class="d-flex justify-content-between w-100 my-2 px-1">
          <p class="text-left">
            Total Win:
          </p>
          <p class="text-right font-weight-bold win" :class="betAmount > 10 ? 'text-success' : null">
            £ {{ totalWin }}
          </p>
        </div>
        <input v-model.number="betAmount" type="number" class="form-control my-2">
        <button class="btn btn-primary btn-block my-2" :disabled="betAmount <= 0" @click.prevent="placeBet">
          Bet
        </button>
      </div>
    </div>
    <div v-show="loading" class="loading" />
  </div>
</template>

<script>
export default {
  name: 'slip',
  props: ['betEvents', 'preference', 'user'],
  data() {
    return {
      finalEvents: undefined,
      odds_preference: true,
      loading: true,
      totalOdds: 0,
      betAmount: 1,
      oddsElements: [],
      miniSlip: false,
      oddsChanged: true,
      iterator: 0,
      tooltipOptions: {
        template: ''
      }
    }
  },
  computed: {
    totalWin() {
      return (parseFloat(this.totalOdds) * parseFloat(this.betAmount)).toFixed(2)
    }
  },
  watch: {
    betEvents: function(newEvents, oldEvents) {
      this.oddsElements = []
      this.finalEvents = newEvents

      this.finalEvents.forEach((row, i) => {
        this.oddsElements[i] = this.fractionToFloat(row.betOdd.odd)
      })

      this.totalOdds = this.oddsElements.reduce((a, b) => parseFloat(a) + parseFloat(b), 0)

      if (this.finalEvents.length < 1) {
        this.restartSlip()
      }
    },
    preference: function(newPreference, oldPreference) {
      this.odds_preference = newPreference
    }
  },
  mounted() {
    this.loading = false
  },
  methods: {
    restartSlip() {
      this.finalEvents = undefined
      this.totalOdds = 0
      this.betAmount = 1
    },
    validateOdds() {
      var jsonResponse

      if (this.iterator === 0) {
        jsonResponse = { success: true }
        this.iterator = this.iterator + 1
      } else {
        this.iterator = this.iterator - 1
        jsonResponse = { success: false }
      }

      return jsonResponse
    },
    toggleSlip(evt) {
      this.miniSlip = !this.miniSlip
    },
    removeBet(row) {
      this.$root.$emit('remove-slip', { row: row })
    },
    placeBet() {
      if (this.user) {
        if (parseFloat(this.user.amount).toFixed(2) > this.betAmount) {
          var result = this.validateOdds()
          if (result.success) {
            this.oddsChanged = true
            axios.post('new-slip', {
              params: {
                events: this.finalEvents,
                amount: this.betAmount
              }
            }).then((response) => {
              this.$root.$emit('success', {
                modalTitle: 'Slip created',
                modalText: 'Your bet of £' + this.betAmount + ' is successfuly placed!'
              })

              localStorage.removeItem('betEvents')
              this.restartSlip()

              $('.odd-span').each((i, el) => {
                if ($(el).hasClass('active')) {
                  $(el).removeClass('active')
                }
              })
            }).catch((error) => {
              console.error(error)
            })
          } else {
            this.oddsChanged = false
          }
        } else {
          this.$root.$emit('show-deposit')
          this.miniSlip = true
        }
      } else {
        this.$root.$emit('show-login')
        this.miniSlip = true
      }
    },
    fractionToFloat(odd) {
      var odds = odd.split('/')
      return parseFloat(parseInt(odds[0]) / parseInt(odds[1])).toFixed(2)
    },
    floatToFraction(fraction) {
      var gcd = function(a, b) {
        if (b < 0.01) return a                // Since there is a limited precision we need to limit the value.
        return gcd(b, Math.floor(a % b))           // Discard any fractions due to limitations in precision.
      }

      var len = fraction.toString().length - 2
      var denominator = Math.pow(2, len)              // Precision limit
      var numerator = fraction * denominator
      var divisor = gcd(numerator, denominator)

      numerator /= divisor
      denominator /= divisor

      var odd = Math.floor(numerator) + '/' + Math.floor(denominator)

      return odd
    }
  }
}
</script>
