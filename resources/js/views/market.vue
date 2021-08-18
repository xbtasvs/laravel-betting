<template>
  <div class="main pages justify-content-start align-items-start">
    <div class="w-100 p-4 market-header shadow-lg">
      <div class="d-flex">
        <div class="d-flex flex-column">
          <div v-if="sportEvent.matchLiveTime" class="d-flex mb-2">
            <p class="team-name mr-3">
              Kick Off: {{ getDate(sportEvent.matchLiveTime.kickOffTime) | moment("hh:mm:ss dddd, MMMM Do YYYY") }}
            </p>
            <p class="team-name">
              Live Time: {{ sportEvent.matchLiveTime.passedMinutes == '0' ? '00' : sportEvent.matchLiveTime.passedMinutes }}:{{ sportEvent.matchLiveTime.passedSeconds == '0' ? '00' : sportEvent.matchLiveTime.passedSeconds }}
            </p>
          </div>
          <h4 class="team-name">
            {{ sportEvent.teams[0].name }} <span class="text-accent mx-2">vs</span> {{ sportEvent.teams[1].name }}
          </h4>
        </div>
        <div v-for="(scores, index) in sportEvent.scores" :key="index" class="d-flex flex-column ml-auto">
          <p class="scores big">
            {{ scores.homeScore }}
          </p>
          <p class="scores big">
            {{ scores.awayScore }}
          </p>
        </div>
      </div>
      <div class="text-white font-italic d-flex w-100 justify-content-start align-items-center mt-2">
        <router-link class="text-white" :to="{ name: 'sports', params: {} }">
          Soccer
        </router-link>
        <i class="fas fa-chevron-right mx-2" />
        <p>{{ sportEvent.league.name }}</p>
        <!-- <small class="ml-auto">
          eventID for the API call: {{ eventID }}
        </small>
        <small class="ml-5">
          eventID used here: {{ sportEvent.eventID }}
        </small> -->
        <select v-model="odds_preference" class="form-control-sm bg-dark text-white border-0 ml-auto align-self-end mr-2 mt-2 hoverable">
          <option :value="true">
            Fractional
          </option>
          <option :value="false">
            Decimal
          </option>
        </select>
      </div>
    </div>
    <div class="w-100 p-4 d-flex flex-column scroller">
      <div v-for="market in sportEvent.markets" :key="market.id" class="d-flex flex-column w-100 mb-3">
        <h5 class="text-center w-100 bg-primary text-white p-3">
          {{ market.name }}
        </h5>
        <div class="d-flex justify-content-between bg-dark transparency">
          <div v-for="bet in market.bets" :key="bet.id" class="d-flex w-100 flex-column py-3 text-center align-items-center justify-content-center border-left border-right border-coloring">
            <div v-if="bet.name">
              {{ bet.name }}
            </div>
            <div v-else>
              {{ bet.alternativeName }}
            </div>
            <span :id="bet.id" class="p-1 odd-span mt-2 rounded-circle" :class="bet.suspended ? 'suspended' : null" @click="placeBet($event, sportEvent, bet)">{{ odds_preference ? bet.odd : fractionToFloat(bet.odd) }}</span>
            <div v-if="bet.handicap" class="text-accent small mt-2">
              {{ bet.handicap }}
            </div>
          </div>
        </div>
      </div>
    </div>
    <div v-show="loading" class="loading" />
  </div>
</template>

<script>
import eventDataSoccer from '../data/104033670.json'
import eventDataTennis from '../data/104450465.json'

export default {
  name: 'markets',
  props: ['eventID'],
  data() {
    return {
      sportEvent: eventDataSoccer,
      odds_preference: true,
      loading: true
    }
  },
  watch: {
    odds_preference: function(newPreference, oldPreference) {
      this.$root.$emit('odds-preference', { odds_pref: newPreference })
      this.odds_preference = newPreference
    }
  },
  mounted() {
    this.getPreferences()
    console.log(eventDataSoccer)
    // TODO: this.getMarkets()
    this.loading = false

    if (!this.eventID) {
      this.$router.push({ name: 'sports' })
    }

    if (this.eventID === '104450465') {
      this.sportEvent = eventDataTennis
    }

    if (localStorage.betEvents) {
      var storedEvents = JSON.parse(localStorage.betEvents)
      for (var i = 0; i < storedEvents.length; i++) {
        $('#' + storedEvents[i].betOdd.id).addClass('active')
      }
    }

    this.$root.$on('update-bet', (e) => {
      if ($('#' + e.elementId).hasClass('active')) {
        $('#' + e.elementId).removeClass('active')
      }
    })
  },
  methods: {
    fractionToFloat(odd) {
      var odds = odd.split('/')
      return parseFloat(parseInt(odds[0]) / parseInt(odds[1])).toFixed(2)
    },
    placeBet(evt, sport, bet) {
      if ($(evt.target).hasClass('active')) {
        $(evt.target).removeClass('active')

        this.$root.$emit('remove-slip', { row: { sportEvent: sport, betOdd: bet } })
      } else {
        $(evt.target).addClass('active')
        var storedEvents

        if (localStorage.betEvents) {
          storedEvents = JSON.parse(localStorage.betEvents)
        } else {
          storedEvents = []
        }

        if (storedEvents.length > 0) {
          storedEvents.push({ sportEvent: sport, betOdd: bet, elementId: evt.target.id })
        } else {
          storedEvents[0] = { sportEvent: sport, betOdd: bet, elementId: evt.target.id }
        }

        this.$root.$emit('update-slip', { betEvents: storedEvents, odds_pref: this.odds_preference })
      }
    },
    getMarkets() {
      axios.get('api/v1/markets', {
        params: {
          event_id: this.eventID,
          inplay: 1
        }
      }).then((response) => {
        // console.log(response.data)
        this.loading = false
      }).catch((error) => {
        // console.error(error)
        this.loading = false
      })
    },
    getPreferences() {
      axios.post('user').then((response) => {
        if (Object.keys(response.data).length !== 0) {
          axios.post('user/preferences').then((response) => {
            if (parseInt(response.data) === 1) {
              this.odds_preference = true
            } else {
              this.odds_preference = false
            }

          }).catch((error) => {
            this.odds_preference = true
          })
        }
      })
    },
    getDate(unix_timestamp) {
      var date = new Date(unix_timestamp * 1000)
      return date
      // var hours = date.getHours()
      // var minutes = '0' + date.getMinutes()
      // var seconds = '0' + date.getSeconds()
      // return hours + ':' + minutes.substr(-2) + ':' + seconds.substr(-2)
    }
  }
}
</script>
