<template>
  <div class="main position">
    <nav class="navbar navbar-expand-lg topbar">
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav">
          <router-link class="nav-item rounded-0" :to="{ name: 'rules', params: {} }">
            {{ $t('Game Rules') }}
          </router-link>
          <router-link v-if="isAdmin" class="nav-item rounded-0" :to="{ name: 'dashboard', params: {} }">
            {{ $t('Dashboard') }}
          </router-link>
          <div class="language-trigger">
            <img class="language" :src="'img/flags/' + current_language + '.png'" :alt="current_language">
            <img v-for="language in languages" :key="language" class="language" :src="'img/flags/' + language + '.png'" :alt="language" @click="changeLanguage(language)">
          </div>
        </ul>
        <ul v-if="!loggedIn" class="navbar-nav ml-auto">
          <li class="nav-item rounded-0" @click="showLogin = true">
            {{ $t('Login') }}
          </li>
          <li class="nav-item rounded-0" @click="showRegister = true">
            {{ $t('Register') }}
          </li>
        </ul>
        <ul v-else class="navbar-nav ml-auto">
          <select class="form-control-sm text-white mr-2 border-0 bg-dark align-self-center" @change="currencySelect">
            <option value="£-GBP" selected>
              GBP
            </option>
            <option value="$-USD" disabled>
              USD
            </option>
            <option value="€-EUR" disabled>
              EUR
            </option>
          </select>
          <li class="nav-item rounded-0 p-2 d-flex align-items-center position-relative profile-trigger">
            <div class="d-flex flex-column ml-3">
              <span>{{ user.first_name }}</span>
              <small class="d-block ml-auto">{{ balance | curr_pref(currencySymbol) }}</small>
            </div>
            <img class="rounded-circle avatar ml-auto" :src="user.avatar" alt="Avatar">
            <ul class="profile-dropdown position-absolute">
              <router-link class="profile-link" :to="{ name: 'profile', params: {} }">
                <i class="far fa-user-circle mr-2" />{{ $t('Profile') }}
              </router-link>
              <li @click="showDeposit = true">
                <i class="far fa-credit-card mr-2" />{{ $t('Deposit') }}
              </li>
              <li @click="logout">
                <i class="fas fa-sign-out-alt mr-2" />{{ $t('Logout') }}
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
    <nav class="navbar navbar-primary navbar-expand-lg">
      <div class="navbar-translate">
        <router-link class="navbar-brand" :to="{ name: 'sports', params: {} }">
          <img class="logo mr-3" :src="'img/logo.png'" alt="Bet2Gain logo">Bet<span class="text-accent">2Gain</span>
        </router-link>
      </div>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item search-container pr-2">
            <div class="search-box">
              <form class="search-form">
                <input class="form-control" placeholder="Search events..." type="text">
                <router-link :to="{ name: 'search', params: {} }" class="btn btn-link search-btn">
                  <i class="fa fa-search" />
                </router-link>
              </form>
            </div>
          </li>
        </ul>
      </div>
    </nav>
    <nav class="navbar navbar-expand-lg sticky-top shadow-lg">
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav">
          <router-link class="nav-item" :to="{ name: 'sports', params: {} }">
            {{ $t('Sports') }}
          </router-link>
          <router-link class="nav-item mx-3" :to="{ name: 'in-play', params: {} }">
            {{ $t('In-Play') }}
          </router-link>
          <router-link class="nav-item" :to="{ name: 'contact', params: {} }">
            {{ $t('Contact Us') }}
          </router-link>
        </ul>
        <ul class="navbar-nav ml-auto">
          <a class="nav-item" rel="tooltip" title="" data-placement="bottom" href="https://twitter.com" target="_blank" data-original-title="Follow us on Twitter">
            <i class="fa fa-twitter" />
          </a>
          <a class="nav-item" rel="tooltip" title="" data-placement="bottom" href="https://www.facebook.com" target="_blank" data-original-title="Like us on Facebook">
            <i class="fa fa-facebook-square" />
          </a>
          <a class="nav-item" rel="tooltip" title="" data-placement="bottom" href="https://www.instagram.com" target="_blank" data-original-title="Follow us on Instagram">
            <i class="fa fa-instagram" />
          </a>
        </ul>
      </div>
    </nav>
    <div v-if="tokenize" class="main pages" />
    <transition name="component-fade" mode="out-in">
      <router-view />
    </transition>
    <div class="footer w-100 shadow-lg bg-dark text-light p-3">
      <div class="d-flex justify-content-between align-items-center w-100">
        <p>Bet2Gain © 2021</p>
        <router-link class="ml-auto text-light p-2" :to="{ name: 'sports', params: {} }">
          {{ $t('Sports') }}
        </router-link>
        <router-link class="mx-3 text-light p-2" :to="{ name: 'in-play', params: {} }">
          {{ $t('In-Play') }}
        </router-link>
        <router-link class="text-light p-2" :to="{ name: 'contact', params: {} }">
          {{ $t('Contact Us') }}
        </router-link>
      </div>
    </div>
    <transition name="modal">
      <login v-if="showLogin" @close-login="showLogin = false" />
    </transition>
    <transition name="modal">
      <register v-if="showRegister" @close-register="showRegister = false" />
    </transition>
    <transition name="modal">
      <reset-password v-if="showResetPassword" :password-token="passwordToken" @close-reset-password="showResetPassword = false" />
    </transition>
    <transition name="modal">
      <send-password v-if="showSendPassword" @close-send-password="showSendPassword = false" />
    </transition>
    <transition name="modal">
      <deposit v-if="showDeposit" @close-deposit="showDeposit = false" />
    </transition>
    <transition name="modal">
      <success v-if="successModal" :modal-title="modalTitle" :modal-text="modalText" @close-success="successModal = false" />
    </transition>
    <transition name="modal">
      <error v-if="errorModal" :modal-title="modalTitle" :modal-text="modalText" @close-error="errorModal = false" />
    </transition>
    <transition name="modal">
      <slip :bet-events="betEvents" :preference="odds_preference" :user="user" />
    </transition>
    <div v-show="loading" class="loading" />
  </div>
</template>

<script>
export default {
  name: 'app',
  filters: {
    curr_pref(balance, currencySymbol) {
      return currencySymbol + ' ' + parseFloat(balance).toFixed(2)
    }
  },
  props: ['identification', 'passwordToken'],
  data() {
    return {
      showLogin: false,
      showRegister: false,
      showResetPassword: false,
      showSendPassword: false,
      showDeposit: false,
      errorModal: false,
      successModal: false,
      loggedIn: false,
      isAdmin: false,
      balance: 1000,
      user: undefined,
      currencySymbol: '£',
      currencyCode: 'USD',
      currentRates: undefined,
      modalTitle: '',
      modalText: '',
      current_language: undefined,
      languages: undefined,
      loading: true,
      tokenize: this.passwordToken,
      betEvents: undefined,
      odds_preference: true
    }
  },
  watch: {
    odds_preference: function(newPreference, oldPreference) {
      if (this.loggedIn) {
        axios.post('oddsPreference', {
          params: {
            pref: newPreference
          }
        })

        this.odds_preference = newPreference
      }
    }
  },
  created() {
    this.getLanguage()
    this.getStoradEvents()
  },
  mounted() {
    if (this.identification !== '' && this.identification !== null && this.identification !== undefined) {
      this.user = JSON.parse(this.identification)
      this.balance = this.user.amount
      if (parseInt(this.user.id) === 1) {
        this.isAdmin = true
      }

      this.loggedIn = true
    }

    if (this.passwordToken !== '' && this.passwordToken !== null && this.passwordToken !== undefined) {
      this.showResetPassword = true
    }

    this.$root.$on('login', (e) => {
      if (parseInt(e.user.id) === 1) {
        this.isAdmin = true
      }

      this.user = e.user
      this.balance = e.user.amount
      this.loggedIn = true
    })

    this.$root.$on('error', (e) => {
      this.modalTitle = e.modalTitle
      this.modalText = e.modalText
      this.errorModal = true
    })

    this.$root.$on('show-deposit', (e) => {
      this.showDeposit = true
    })

    this.$root.$on('remove-token', (e) => {
      this.tokenize = null
    })

    this.$root.$on('success', (e) => {
      this.modalTitle = e.modalTitle
      this.modalText = e.modalText
      this.successModal = true
      axios.post('user').then((response) => {
        this.user = response.data
        this.balance = response.data.amount
        this.odds_preference = response.data.odds_pref
      })
    })

    this.$root.$on('send-password', (e) => {
      this.showSendPassword = true
    })

    this.$root.$on('show-login', (e) => {
      this.showLogin = true
    })

    this.$root.$on('odds-preference', (e) => {
      this.odds_preference = e.odds_pref
    })

    this.$root.$on('update-slip', (e) => {
      this.odds_preference = e.odds_pref
      localStorage.betEvents = JSON.stringify(e.betEvents)

      this.getStoradEvents()
    })

    this.$root.$on('remove-slip', (e) => {
      var localEvents = JSON.parse(localStorage.betEvents)
      for (var j = 0; j < localEvents.length; j++) {
        if (localEvents[j].sportEvent.eventID === e.row.sportEvent.eventID && localEvents[j].betOdd.id === e.row.betOdd.id) {
          localEvents.splice(j, 1)
        }
      }

      for (var l = 0; l < localEvents.length; l++) {
        if (this.betEvents[l].sportEvent.eventID === e.row.sportEvent.eventID && this.betEvents[l].betOdd.id === e.row.betOdd.id) {
          this.betEvents.splice(l, 1)
        }
      }

      if (e.row.elementId) {
        this.$root.$emit('update-bet', { sportEvent: e.row.sportEvent, betOdd: e.row.betOdd, elementId: e.row.elementId })
      }

      localStorage.betEvents = JSON.stringify(localEvents)

      this.getStoradEvents()
    })
  },
  methods: {
    // getPreferences() {
    //   if (this.loggedIn) {
    //     axios.post('user/preferences').then((response) => {
    //       if (parseInt(response.data) === 1) {
    //         this.odds_preference = true
    //       } else {
    //         this.odds_preference = false
    //       }
    //
    //     }).catch((error) => {
    //       this.odds_preference = true
    //     })
    //   }
    // },
    logout() {
      this.user = undefined
      this.balance = 0
      this.loggedIn = false

      axios.post('logout').then((response) => {
        $('meta[name="csrf-token"]').attr('content', response.data)
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = response.data
      })

      if (this.$route.name === 'dashboard' || this.$route.name === 'profile' || this.$route.name === 'admin-bans' || this.$route.name === 'admin-bets' || this.$route.name === 'admin-rules') {
        this.$router.push({
          name: 'sports'
        })
      }

      this.isAdmin = false
    },
    getStoradEvents() {
      if (localStorage.betEvents) {
        this.betEvents = JSON.parse(localStorage.betEvents)
      }
    },
    getLanguage() {
      axios.post('getLanguage').then((response) => {
        this.$root.$i18n.locale = response.data.current
        this.current_language = response.data.current
        this.languages = response.data.languages.filter(language => language !== this.current_language)

        if (this.loading) this.loading = false
      })
    },
    changeLanguage(language) {
      axios.post('setLanguage', { params: {
        language: language
      }}).then((response) => {
        this.getLanguage()
      })
    },
    currencySelect(evt) {
      var newCurrency = evt.target.value.split('-')
      var newCurrencySymbol = newCurrency[0]
      var newCurrencyCode = newCurrency[1]

      this.currencyCode = newCurrencyCode
      this.currencySymbol = newCurrencySymbol

      // TODO: currencies changing
      // var endpoint = 'http://data.fixer.io/api/'
      // var access_key = '**'
      // $.ajax({
      //   url: endpoint + 'latest?access_key=' + access_key,
      //   dataType: 'jsonp',
      //   success: function(json) {
      //     console.log(json.rates)
      //   }
      // })
      // axios.get(endpoint + 'convert?access_key=' + access_key + '&from=' + this.currencyCode + '&to=' + newCurrencyCode + '&amount=' + this.balance).then((response) => {
      //   this.currentRates = response.rates
      //   this.currencySymbol = newCurrencySymbol
      //   this.currencyCode = newCurrencyCode
      //   this.balance = response.result
      //   console.log(result)
      // })
    }
  }
}
</script>
