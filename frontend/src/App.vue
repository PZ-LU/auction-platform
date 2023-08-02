<!-- Vue App entry point and routing -->
<template>
  <v-app v-if="$auth.ready()">
    <v-tabs
      v-model="activeTab"
      fixed-tabs
    >
      <v-tab to="/">
        Home
      </v-tab>
      <v-tab to="/offers">
        Offers
      </v-tab>
      <v-tab to="/auction">
        Auction
      </v-tab>
      <v-tab
        v-if="!getAuthState()"
        to="/signup"
      >
        Sign Up
      </v-tab>
      <v-tab to="/forum">
        Forum
      </v-tab>
      <v-tab
        v-if="$auth.check()"
        to="/dashboard"
      >
        Dashboard
      </v-tab>
      <v-tab
        v-if="authAdmin"
        to="/admin"
      >
        Control Panel
      </v-tab>
      <v-divider :vertical="true" />
      <v-menu
        v-if="!getAuthState()"
        :close-on-click="true"
        :close-on-content-click="getLoginButton()"
        :offset-x="true"
        :offset-y="true"
      >
        <template #activator="{ on }">
          <v-btn 
            style="height: 100%; width: 100%; border-radius: 0"
            max-width="10%"
            fill-height
            text
            v-on="on"
            id="login_button"
          >
            Log In
          </v-btn>
        </template>

        <v-list>
          <v-list-item>
            <Login />
          </v-list-item>
        </v-list>
      </v-menu>

      <v-tooltip
        bottom
        v-if="getAuthState()"
      >
        <template
          #activator="{ on }"
        >
          <v-btn 
            style="height: 100%; width: 100%; border-radius: 0"
            max-width="10%"
            fill-height
            text
            :loading="isLoading"
            :disabled="isLoading"
            @click="logout"
            v-on="on"
          >
            Log Out
          </v-btn>
        </template>

        <span>{{ $auth.user().username }}</span>
      </v-tooltip>

      <!-- Route tabs to views -->
      <v-tab-item id="/">
        <v-divider />
        <router-view v-if="activeTab === '/'" />
      </v-tab-item>
      <v-tab-item id="/offers">
        <v-divider />
        <router-view v-if="activeTab === '/offers'" />
      </v-tab-item>
      <v-tab-item id="/auction">
        <v-divider />
        <router-view v-if="activeTab === '/auction'" />
      </v-tab-item>
      <v-tab-item id="/signup">
        <v-divider />
        <router-view v-if="activeTab === '/signup'" />
      </v-tab-item>
      <v-tab-item id="/forum">
        <v-divider />
        <router-view v-if="activeTab === '/forum'" />
      </v-tab-item>
      <v-tab-item id="/dashboard">
        <v-divider />
        <router-view v-if="activeTab === '/dashboard'" />
      </v-tab-item>
      <v-tab-item id="/admin">
        <v-divider />
        <router-view v-if="activeTab === '/admin'" />
      </v-tab-item>
    </v-tabs>
  </v-app>
</template>

<script>
import Login from '@/components/Login'
import { mapGetters, mapMutations } from 'vuex'
import fetchFavoriteOffers from './plugins/fetchFavoriteOffers'

export default {
  name: 'App',
  components: {
    Login
  },
  data() {
    return {
      activeTab: null,

      isLoading: false
    }
  },
  computed: {
    authAdmin: function () {
      if (this.$auth.user().role === 'admin' || this.$auth.user().role === 'super_admin') {
        return true
      }

      return false
    }
  },
  created() {
    this.$auth.ready(function () {
        if (this.$auth.token()) {
          this.fadeLogin()
          this.changeAuthState()
          this.fetchFavoriteOffers()
        }
    });
  },
  methods: {
    // Global state handlers
    ...mapGetters([
      'getLoginButton',
      'getAuthState'
    ]),
    ...mapMutations([
      'changeAuthState',
      'fadeLogin'
    ]),
    logout() {
      this.isLoading = true
      this.$auth.logout({
        success: function () {
          this.isLoading = false
          this.fadeLogin()
          this.changeAuthState()
        },
        error: function () {
          console.log("Something went wrong with Your logout!")
        },
      })
    },
    async fetchFavoriteOffers () {
      const result = await fetchFavoriteOffers(this.$auth.user().id)
      if (result) {
        this.$store.commit('setFavoriteOffers', result)
      }
    },
  },
}
</script>
