// Router paths
import VueRouter from 'vue-router'

import Home from './views/Home.vue'
import Offers from './views/Offers.vue'
import Auction from './views/Auction.vue'
import Registration from './views/Registration.vue'
import Dashboard from './views/Dashboard.vue'
import AdminDashboard from './views/admin/ControlPanel.vue'
import Forum from './views/Forum.vue'

const routes = [
  {
    path: '/',
    name: 'home',
    component: Home
  },
  {
    path: '/offers',
    name: 'offers',
    component: Offers
  },
  {
    path: '/auction',
    name: 'auction',
    component: Auction
  },
  {
    path: '/forum',
    name: 'forum',
    component: Forum
  },
  
  //auth forms
  {
    path: '/signup',
    name: 'signup',
    component: Registration,
    meta: {
      auth: false
    }
  },

  //user routes
  {
    path: '/dashboard',
    name: 'dashboard',
    component: Dashboard,
    meta: {
      auth: true
    },
  },

  //admin routes
  {
    path: '/admin',
    name: 'admin.dashboard',
    component: AdminDashboard,
    meta: {
      auth: {roles: ['super_admin', 'admin'], redirect: {name: 'offers'}, forbiddenRedirect: '/403'}
    },
  },

  // everything else
  {
    path: '*',
    name: 'any',
    component: Home
  },
]
const router = new VueRouter({
  history: true,
  mode: 'history',
  routes,
})

export default router
