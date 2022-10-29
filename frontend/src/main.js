import Vue from 'vue'
import VueRouter from 'vue-router'

import App from './App.vue'

import router from './router'
import auth from './auth'

import store from './store'
import vuetify from './plugins/vuetify';

import axios from 'axios'
import VueAxios from 'vue-axios'
import VueAuth from '@websanova/vue-auth'

Vue.config.productionTip = false

Vue.router = router
Vue.use(VueRouter)

// Set Vue authentication
Vue.use(VueAxios, axios)
axios.defaults.baseURL = `${process.env.VUE_APP_URL}:${process.env.VUE_APP_PORT}/api`
Vue.prototype.$axios = axios
Vue.use(VueAuth, auth)


new Vue({
  router,
  store,
  vuetify,
  render: h => h(App)
}).$mount('#app')
