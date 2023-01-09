<!-- Login form -->
<template>
  <v-container fill-height>
    <v-form ref="lForm">
      <v-text-field
        v-model="loginData"
        label="Username or Email"
        prepend-icon="mdi-account"
        :rules="[rules.required]"
        @keypress.enter.native="login"
      />
      <v-text-field
        v-model="password"
        label="Password"
        prepend-icon="mdi-lock"
        type="password"
        :rules="[rules.required]"
        @keypress.enter.native="login"
      />
      <v-expand-transition>
        <p 
          v-show="isError"
          style="color: red;"
          class="subtitle-2 pl-4"
        >
          <v-icon
            color="red"
            size="17px"
            class="pr-0 pb-1"
          >
            mdi-alert
          </v-icon> <span v-if="!isUserSuspended">Wrong username/email or password</span>
          <span v-else>Your account has been suspended</span>
        </p>
      </v-expand-transition>
      <v-card-actions>
        <v-btn
          class="mx-auto"
          text
          :disabled="isLoading"
          :loading="isLoading"
          @click="login"
        >
          Sign In
        </v-btn>
      </v-card-actions>
    </v-form>
  </v-container>
</template>

<script>
import { mapMutations } from 'vuex'
import external_rules from  '@/plugins/rules/rules.js'
import fetchFavoriteOffers from '@/plugins/fetchFavoriteOffers.js'

export default {
  data() {
    return {
      loginData: null,
      password: null,
      info: 'null',

      isLoading: false,

      rules: external_rules,

      isUserSuspended: true,
      isError: false
    }
  },
  methods: {
    ...mapMutations([
      'fadeLogin',
      'changeAuthState'
    ]),
    login() {
      this.isUserSuspended = false
      if (this.$refs.lForm.validate()){
        this.isError = false
        this.isLoading = true
        let that = this
        this.$auth.login({
          data: {
            email: that.loginData,
            username: that.loginData,
            password: that.password
          },
          success: function() {
            this.isLoading = false
            this.fadeLogin()
            this.changeAuthState()
            const redirectTo = this.redirect ? this.redirect.from.name : this.$auth.user().role === 'super_admin' ? 'admin.dashboard' : this.$auth.user().role === 'admin' ? 'admin.dashboard' : 'dashboard'
            this.$router.push({name: redirectTo})
            this.fetchFavoriteOffers()
          },
          error : function(err) {
            if (err.response.data.error === 'account_suspended') {
              this.isUserSuspended = true
            }
            this.isLoading = false
            this.isError = true
          },
          rememberMe: true,
          fetchUser: true,
        })
      }
    },
    async fetchFavoriteOffers () {
      const result = await fetchFavoriteOffers(this.$auth.user().id)
      if (result) {
        this.$store.commit('setFavoriteOffers', result)
      }
    }
  }
}
</script>

<style scoped>
  .form {
    z-index: 1;
  }
</style>