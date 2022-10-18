import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    loginButtonClicked: false,
    isLogged: false,
    addOfferDialog: false,

    charityTotal: 0,
    commercialTotal: 0,

    offersFilters: {
      title: null,
      description: null,
      tags: null,
      category: null,
      date: null
    },

    favoriteOffers: null
  },
  mutations: {
    fadeLogin: state => {
      state.loginButtonClicked = !state.loginButtonClicked
    },
    changeAuthState: state => {
      state.isLogged = !state.isLogged
    },
    changeAppendOfferState: state => {
      state.addOfferDialog = !state.addOfferDialog
    },
    setCharityTotal (state, total) {
      state.charityTotal = total
    },
    setCommercialTotal (state, total) {
      state.commercialTotal = total
    },
    setOffersFilters (state, filters) {
      state.offersFilters = filters
    },
    setFavoriteOffers (state, offers) {
      state.favoriteOffers = offers
    }
  },
  getters: {
    getLoginButton: state => {
      return state.loginButtonClicked
    },
    getAuthState: state => {
      return state.isLogged
    },
    getAppendingOfferState: state => {
      return state.addOfferDialog
    },
    charityTotal: state => state.charityTotal,
    commercialTotal: state => state.commercialTotal,
    offersFilters: state => state.offersFilters,
    favoriteOffers: state => state.favoriteOffers
  },
  actions: {

  }
})
