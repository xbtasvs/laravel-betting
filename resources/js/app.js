import './bootstrap.js'

import Vue from 'vue'
import VueRouter from 'vue-router'
import VueMoment from 'vue-moment'
import VueI18n from 'vue-i18n'
import VTooltip from 'v-tooltip'

import languageBundle from '@kirschbaum-development/laravel-translations-loader!@kirschbaum-development/laravel-translations-loader'
import movable from 'v-movable'

Vue.use(VTooltip, {
  defaultTrigger: 'hover focus click'
})

Vue.use(movable)
Vue.use(VueI18n)
Vue.use(VueRouter)
Vue.use(VueMoment)

const i18n = new VueI18n({
  locale: 'en',
  messages: languageBundle,
})

Vue.component('app', () => import( /* webpackChunkName: 'main' */ './app.vue'))
Vue.component('slip', () => import( /* webpackChunkName: 'slip' */ './views/partials/slip.vue'))
Vue.component('construction', () => import( /* webpackChunkName: 'construction' */ './views/partials/construction.vue'))
Vue.component('register', () => import( /* webpackChunkName: 'register' */ './views/partials/register.vue'))
Vue.component('login', () => import( /* webpackChunkName: 'login' */ './views/partials/login.vue'))
Vue.component('deposit', () => import( /* webpackChunkName: 'deposit' */ './views/partials/deposit.vue'))
Vue.component('error', () => import( /* webpackChunkName: 'error' */ './views/partials/error.vue'))
Vue.component('success', () => import( /* webpackChunkName: 'success' */ './views/partials/success.vue'))
Vue.component('send-password', () => import( /* webpackChunkName: 'sendpassword' */ './views/partials/passwords/send.vue'))
Vue.component('reset-password', () => import( /* webpackChunkName: 'resetpassword' */ './views/partials/passwords/reset.vue'))

const router = new VueRouter({
  base: '/Bet2Gain',
  mode: 'history',
  routes: [{
      path: '/',
      name: 'sports',
      component: () => import( /* webpackChunkName: 'sports' */ './views/sports.vue')
    },
    {
      path: '/in-play',
      name: 'in-play',
      component: () => import( /* webpackChunkName: 'inplay' */ './views/inplay.vue')
    },
    {
      path: '/verified',
      name: 'verified',
      props: { title: 'Congratulations!', text: 'You have successfully verified your email address.' },
      component: () => import( /* webpackChunkName: 'notice' */ './views/notice.vue')
    },
    {
      path: '/not-verified',
      name: 'not-verified',
      props: { title: 'Ooops!', text: 'Please login first and press that link from email again.' },
      component: () => import( /* webpackChunkName: 'notice' */ './views/notice.vue')
    },
    {
      path: '/markets',
      name: 'markets',
      props: true,
      component: () => import( /* webpackChunkName: 'market' */ './views/market.vue')
    },
    {
      path: '/upcoming',
      name: 'upcoming',
      component: () => import( /* webpackChunkName: 'upcoming' */ './views/upcoming.vue')
    },
    {
      path: '/contact',
      name: 'contact',
      component: () => import( /* webpackChunkName: 'contact' */ './views/contact.vue')
    },
    {
      path: '/rules',
      name: 'rules',
      component: () => import( /* webpackChunkName: 'rules' */ './views/rules.vue')
    },
    {
      path: '/search',
      name: 'search',
      component: () => import( /* webpackChunkName: 'search' */ './views/search.vue')
    },
    {
      path: '/profile',
      name: 'profile',
      component: () => import( /* webpackChunkName: 'profile' */ './views/profile.vue')
    },
    {
      path: '/active',
      name: 'active',
      component: () => import( /* webpackChunkName: 'active' */ './views/active.vue')
    },
    {
      path: '/history',
      name: 'history',
      component: () => import( /* webpackChunkName: 'history' */ './views/history.vue')
    },
    {
      path: '/dashboard',
      name: 'dashboard',
      component: () => import( /* webpackChunkName: 'dashboard' */ './views/admin/dashboard.vue')
    },
    {
      path: '/bans',
      name: 'admin-bans',
      component: () => import( /* webpackChunkName: 'bans' */ './views/admin/bans.vue')
    },
    {
      path: '/bets',
      name: 'admin-bets',
      component: () => import( /* webpackChunkName: 'bets' */ './views/admin/bets.vue')
    },
    {
      path: '/admin-rules',
      name: 'admin-rules',
      component: () => import( /* webpackChunkName: 'rules' */ './views/admin/rules.vue')
    },
  ],
})

const app = new Vue({
  el: '#app',
  i18n,
  router
})
