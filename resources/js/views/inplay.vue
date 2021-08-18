<template>
  <div class="main pages sports">
    <div class="sports-container">
      <div v-for="sport in sports" :key="sport.id" :class="parseInt(sport.id) === 1 ? 'sport active' : 'sport'" @click="(e) => { changeSport(e, sport.id, sport.name) }">
        <img class="sport-icon" :src="'img/sports/' + sport.name + '.png'" :alt="sport.name" :title="sport.name">
        <p class="sport-name">
          {{ sport.name }}
        </p>
      </div>
    </div>
    <div class="main-area pb-3">
      <div class="w-100 d-flex flex-column position-relative">
        <img :src="'img/frame.jpg'" class="bets-banner shadow-lg w-100">
        <div class="sports-header p-3">
          <div class="d-flex">
            <h4 class="section-title">
              In-Play
            </h4>
            <div v-if="inplayEvents.length > 0" class="ml-auto d-flex align-items-center">
              <select v-model="odds_preference" class="form-control-sm bg-dark text-white border-0">
                <option :value="true">
                  Fractional
                </option>
                <option :value="false">
                  Decimal
                </option>
              </select>
            </div>
          </div>
          <div class="d-flex mt-3">
            <div class="sport-name w-50">
              <img class="sport-icon small mr-3 ml-1" :src="'img/sports/' + current_sport_name + '.png'" :alt="current_sport_name" :title="current_sport_name">
              <p>{{ current_sport_name }}</p>
            </div>
            <div v-if="current_sport_id === 1 && inplayEvents.length > 0" class="common-bets w-50 d-flex">
              <div>
                1
              </div>
              <div>
                X
              </div>
              <div>
                2
              </div>
            </div>
            <div v-else-if="current_sport_id === 2 && inplayEvents.length > 0" class="common-bets w-50 d-flex justify-content-around">
              <div>
                1
              </div>
              <div>
                2
              </div>
            </div>
            <div v-else-if="current_sport_id === 3 && inplayEvents.length > 0" class="common-bets w-50 d-flex justify-content-around">
              <div>
                Spread
              </div>
              <div>
                Total
              </div>
              <div>
                Money Line
              </div>
            </div>
            <div v-else-if="inplayEvents.length > 0" class="common-bets w-50 d-flex justify-content-around">
              <div>
                XX
              </div>
              <div>
                YY
              </div>
              <div>
                ZZ
              </div>
            </div>
          </div>
        </div>
        <div v-if="inplayEvents.length > 0">
          <div v-for="(sportEvent, y) in inplayEvents" :key="y + incrementor" class="d-flex w-100 px-3 border-bottom">
            <div class="w-50 py-3">
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
                  <p class="team-name">
                    {{ sportEvent.teams[0].name }} <span class="text-accent mx-2">vs</span> {{ sportEvent.teams[1].name }}
                  </p>
                </div>
                <div v-for="(scores, index) in sportEvent.scores" :key="index" class="d-flex flex-column ml-auto">
                  <p class="scores">
                    {{ scores.homeScore }}
                  </p>
                  <p class="scores">
                    {{ scores.awayScore }}
                  </p>
                </div>
              </div>
              <small class="text-white font-italic league-name">{{ sportEvent.league.name }}</small>
            </div>
            <template v-if="current_sport_id === 1">
              <div v-for="market in sportEvent.markets" :key="market.id" class="common-bets w-50 d-flex">
                <div v-for="bet in market.bets" :key="bet.id">
                  <span :id="bet.id" class="p-2 odd-span my-1 rounded" :class="bet.suspended ? 'suspended' : null" @click="placeBet($event, sportEvent, bet)">{{ odds_preference ? bet.odd : fractionToFloat(bet.odd) }}</span>
                </div>
              </div>
            </template>
            <template v-else-if="current_sport_id === 2">
              <div v-for="market in sportEvent.markets" :key="market.id" class="common-bets w-50 d-flex justify-content-around">
                <div v-for="bet in market.bets" :key="bet.id" :class="bet.suspended ? 'suspended' : null">
                  <span :id="bet.id" class="p-2 odd-span my-1 rounded" :class="bet.suspended ? 'suspended' : null" @click="placeBet($event, sportEvent, bet)">{{ odds_preference ? bet.odd : fractionToFloat(bet.odd) }}</span>
                </div>
              </div>
            </template>
            <template v-else-if="current_sport_id === 3">
              <div v-for="market in sportEvent.markets" :key="market.id" class="common-bets w-50 d-flex justify-content-start">
                <template v-if="market.bets.length === 6">
                  <div class="basketball-odd">
                    <span :id="market.bets[0].id" class="p-2 odd-span my-1 rounded" :class="market.bets[0].suspended ? 'suspended' : null" @click="placeBet($event, sportEvent, market.bets[0])">{{ odds_preference ? market.bets[0].odd : fractionToFloat(market.bets[0].odd) }}</span>
                    <span class="p-0" :class="market.bets[0].suspended ? 'suspended' : null">{{ market.bets[0].handicap }}</span>
                  </div>
                  <div class="basketball-odd">
                    <span :id="market.bets[3].id" class="p-2 odd-span my-1 rounded" :class="market.bets[3].suspended ? 'suspended' : null" @click="placeBet($event, sportEvent, market.bets[3])">{{ odds_preference ? market.bets[3].odd : fractionToFloat(market.bets[3].odd) }}</span>
                    <span class="p-0" :class="market.bets[3].suspended ? 'suspended' : null">{{ market.bets[3].handicap }}</span>
                  </div>
                  <div class="basketball-odd">
                    <span :id="market.bets[1].id" class="p-2 odd-span my-1 rounded" :class="market.bets[1].suspended ? 'suspended' : null" @click="placeBet($event, sportEvent, market.bets[1])">{{ odds_preference ? market.bets[1].odd : fractionToFloat(market.bets[1].odd) }}</span>
                    <span class="p-0" :class="market.bets[1].suspended ? 'suspended' : null">{{ market.bets[1].handicap }}</span>
                  </div>
                  <div class="basketball-odd">
                    <span :id="market.bets[4].id" class="p-2 odd-span my-1 rounded" :class="market.bets[4].suspended ? 'suspended' : null" @click="placeBet($event, sportEvent, market.bets[4])">{{ odds_preference ? market.bets[4].odd : fractionToFloat(market.bets[4].odd) }}</span>
                    <span class="p-0" :class="market.bets[4].suspended ? 'suspended' : null">{{ market.bets[4].handicap }}</span>
                  </div>
                  <div class="basketball-odd">
                    <span :id="market.bets[2].id" class="p-2 odd-span my-1 rounded" :class="market.bets[2].suspended ? 'suspended' : null" @click="placeBet($event, sportEvent, market.bets[2])">{{ odds_preference ? market.bets[2].odd : fractionToFloat(market.bets[2].odd) }}</span>
                    <span class="p-0" :class="market.bets[2].suspended ? 'suspended' : null">{{ market.bets[2].handicap }}</span>
                  </div>
                  <div class="basketball-odd">
                    <span :id="market.bets[5].id" class="p-2 odd-span my-1 rounded" :class="market.bets[5].suspended ? 'suspended' : null" @click="placeBet($event, sportEvent, market.bets[5])">{{ odds_preference ? market.bets[5].odd : fractionToFloat(market.bets[5].odd) }}</span>
                    <span class="p-0" :class="market.bets[5].suspended ? 'suspended' : null">{{ market.bets[5].handicap }}</span>
                  </div>
                </template>
                <template v-else-if="market.bets.length === 4">
                  <div class="basketball-odd">
                    <span :id="market.bets[0].id" class="p-2 odd-span my-1 rounded" :class="market.bets[0].suspended ? 'suspended' : null" @click="placeBet($event, sportEvent, market.bets[0])">{{ odds_preference ? market.bets[0].odd : fractionToFloat(market.bets[0].odd) }}</span>
                    <span class="p-0" :class="market.bets[0].suspended ? 'suspended' : null">{{ market.bets[0].handicap }}</span>
                  </div>
                  <div class="basketball-odd">
                    <span :id="market.bets[2].id" class="p-2 odd-span my-1 rounded" :class="market.bets[2].suspended ? 'suspended' : null" @click="placeBet($event, sportEvent, market.bets[2])">{{ odds_preference ? market.bets[2].odd : fractionToFloat(market.bets[2].odd) }}</span>
                    <span class="p-0" :class="market.bets[2].suspended ? 'suspended' : null">{{ market.bets[2].handicap }}</span>
                  </div>
                  <div class="basketball-odd">
                    <span :id="market.bets[1].id" class="p-2 odd-span my-1 rounded" :class="market.bets[1].suspended ? 'suspended' : null" @click="placeBet($event, sportEvent, market.bets[1])">{{ odds_preference ? market.bets[1].odd : fractionToFloat(market.bets[1].odd) }}</span>
                    <span class="p-0" :class="market.bets[1].suspended ? 'suspended' : null">{{ market.bets[1].handicap }}</span>
                  </div>
                  <div class="basketball-odd">
                    <span :id="market.bets[3].id" class="p-2 odd-span my-1 rounded" :class="market.bets[3].suspended ? 'suspended' : null" @click="placeBet($event, sportEvent, market.bets[3])">{{ odds_preference ? market.bets[3].odd : fractionToFloat(market.bets[3].odd) }}</span>
                    <span class="p-0" :class="market.bets[3].suspended ? 'suspended' : null">{{ market.bets[3].handicap }}</span>
                  </div>
                </template>
                <template v-else>
                  <div v-for="bet in market.bets" :key="bet.id" class="basketball-odd">
                    <span :id="bet.id" class="p-2 odd-span my-1 rounded" :class="bet.suspended ? 'suspended' : null" @click="placeBet($event, sportEvent, bet)">{{ odds_preference ? bet.odd : fractionToFloat(bet.odd) }}</span>
                    <span class="p-0" :class="bet.suspended ? 'suspended' : null">{{ bet.handicap }}</span>
                  </div>
                </template>
              </div>
            </template>
            <template v-else>
              <div v-for="market in sportEvent.markets" :key="market.id" class="common-bets w-50 d-flex justify-content-around">
                <div v-for="bet in market.bets" :key="bet.id" :class="bet.suspended ? 'suspended' : null">
                  <span :id="bet.id" class="p-2 odd-span my-1 rounded" :class="bet.suspended ? 'suspended' : null" @click="placeBet($event, sportEvent, bet)">{{ odds_preference ? bet.odd : fractionToFloat(bet.odd) }}</span>
                </div>
              </div>
            </template>
          </div>
        </div>
        <div v-else class="d-flex w-100 justify-content-center align-items-center">
          <h3 class="w-50">
            There is no available {{ current_sport_name }} events at the moment, please try again later.
          </h3>
        </div>
      </div>
      <div v-show="loading" class="loading" />
    </div>
  </div>
</template>

<script>
import sportsData from '../data/sports.json'
import inplayData from '../data/inplay-sport-1.json'

export default {
  name: 'in-play',
  data() {
    return {
      loading: true,
      sports: sportsData.sports,
      // TODO: inplayEvents: [],
      events: inplayData.sportEvents,
      current_sport_id: 1,
      current_sport_name: 'Soccer',
      odds_preference: true,
      incrementor: 1
    }
  },
  computed: {
    inplayEvents() {
      if (this.current_sport_id === 1) {
        return this.events.filter(evs => evs.markets && evs.markets[0].name === 'Fulltime Results')
      } else if (this.current_sport_id === 2) {
        return this.events.filter(evs => evs.markets && evs.markets[0].name === 'To Win')
      } else if (this.current_sport_id === 3) {
        return this.events.filter(evs => evs.markets && evs.markets[0].name === 'Game Lines')
      } else {
        return []
      }
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
    // TODO: this.getLatest(this.current_sport_id)
    // setInterval(() => {
    //   this.getLatest(this.current_sport_id)
    // }, 5000) // API call each 5 seconds
    this.loading = false

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
    changeSport(evt, id, name) {
      $('.sport').each((i, el) => {
        $(el).removeClass('active')
      })

      if ($(evt.target).hasClass('sport')) {
        $(evt.target).addClass('active')
      } else {
        $(evt.target.parentNode).addClass('active')
      }

      this.current_sport_id = parseInt(id)
      this.current_sport_name = name
      // TODO: this.getLatest(this.current_sport_id)
    },
    getLatest(sport_id) {
      axios.get('api/v1/inplay', {
        params: {
          sport_id: sport_id
        }
      }).then((response) => {
        if (this.inplayEvents !== JSON.parse(response.data).sportEvents) {
          this.inplayEvents = JSON.parse(response.data).sportEvents
          this.incrementor = this.incrementor + 1
          this.loading = false
        }
      }).catch((error) => {
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
