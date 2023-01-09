// Authentication config for websanova's jwt integration
import bearer from '@websanova/vue-auth/drivers/auth/bearer.js'
import authaxios from '@websanova/vue-auth/drivers/http/axios.1.x'
import authrouter from '@websanova/vue-auth/drivers/router/vue-router.2.x.js'

const URL_PATH = `${process.env.VUE_APP_URL}:${process.env.VUE_APP_PORT}`;

const config = {
  auth: bearer,
  http: authaxios,
  router: authrouter,
  tokenDefaultName: 'laravel-vue-spa',
  tokenStore: ['localStorage'],
  rolesVar: 'role',
  registerData: {url: `${URL_PATH}/api/register`, method: 'POST', redirect: ''},
  loginData: {url: `${URL_PATH}/api/auth/login`, method: 'POST', redirect: '', fetchUser: true},
  logoutData: {url: `${URL_PATH}/api/auth/logout`, method: 'POST', redirect: '/', makeRequest: true},
  fetchData: {url: `${URL_PATH}/api/auth/user`, method: 'GET', enabled: true},
  refreshData: {url: `${URL_PATH}/api/auth/refresh`, method: 'GET', enabled: true, interval: 30}
}

export default config