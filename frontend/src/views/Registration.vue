<!-- Main view for signing up -->
<template>
  <v-app>
    <v-container
      class="mx-auto"
      fill-height
    >
      <ConnectionError
        v-if="isError"
        width="30px"
        :error-code="errorCode"
      />
      <v-card
        v-if="!isError"
        width="500px"
        class="mx-auto"
      >
        <!-- Input fields -->
        <v-card-title>Register new account</v-card-title>
        <v-card-text>
          <v-form ref="form">
            <v-text-field
              id="Registration_name"
              v-model="name"
              prepend-icon="mdi-account-circle-outline"
              label="Name"
              :counter="24"
              :rules="[rules.textOnly, rules.length.name]"
            />
            <v-text-field
              id="Registration_surname"
              v-model="surname"
              prepend-icon="mdi-account-circle-outline"
              label="Surname"
              :counter="24"
              :rules="[rules.textOnly, rules.length.surname]"
            />
            <v-text-field
              id="Registration_username"
              v-model="username"
              prepend-icon="mdi-account-circle"
              label="Username"
              :counter="16"
              :rules="[rules.required, rules.length.username]"
            />
            <v-expand-transition>
              <p 
                v-show="errors.username"
                style="color: red;"
                class="subtitle-2 pl-5"
              >
                <v-icon
                  color="red"
                  size="17px"
                  class="pr-1"
                >
                  mdi-alert
                </v-icon>{{ errors.username ? errors.username[0] : '' }}
              </p>
            </v-expand-transition>
            <v-text-field
              id="Registration_email"
              v-model="email"
              prepend-icon="mdi-email"
              label="Email"
              :rules="[rules.required, rules.email]"
            />
            <v-expand-transition>
              <p 
                v-show="errors.email"
                style="color: red;"
                class="subtitle-2 pl-5"
              >
                <v-icon
                  color="red"
                  size="17px"
                  class="pr-1"
                >
                  mdi-alert
                </v-icon>{{ errors.email ? errors.email[0] : '' }}
              </p>
            </v-expand-transition>
            <v-text-field
              id="Registration_password"
              v-model="password"
              prepend-icon="mdi-lock"
              type="password"
              label="Password"
              :counter="16"
              :rules="[rules.required, rules.length.password]"
            />
            <v-text-field
              id="Registration_password_confirm"
              v-model="password_confirmation"
              prepend-icon="mdi-lock"
              label="Confirm Password"
              type="password"
              :rules="[rules.required, scoped_rules.passwordConfirmation]"
            />
            <v-radio-group
              v-model="gender"
              :label="'Gender'"
              :rules="[rules.requiredItem]"
              class="pl-4 pt-0 mt-0"
            >
              <v-radio
                name="Registration_gender_m"
                color="primary"
                :label="'Male'"
                :value="'male'"
              />
              <v-radio
                name="Registration_gender_f"
                color="primary"
                :label="'Female'"
                :value="'female'"
              />
            </v-radio-group>
            <v-card-actions>
              <v-btn
                v-if="!isLoading"
                @click="submit()"
              >
                Submit
              </v-btn>
              <v-spacer />
            </v-card-actions>
            <v-card-text v-if="isLoading">
              <v-container class="mx-auto loading">
                <Loading />
              </v-container>
            </v-card-text> 
          </v-form>
        </v-card-text>
      </v-card>
    </v-container>
  </v-app>
</template>

<script>
import Loading from '@/components/helpers/Loading.vue'
import ConnectionError from '@/components/helpers/ConnectionError.vue'

import external_rules from '@/plugins/rules/rules.js'

export default {
  name: 'Signup',
  components: {
    ConnectionError,
    Loading
  },
  data() {
    return {
      name: null,
      surname: null,
      gender: null,
      username: null,
      email: null,
      password: null,
      password_confirmation: null,
      rules: external_rules,
      scoped_rules: {
        passwordConfirmation: v => (v && v == this.password) || 'Fields do not match'
      },
        
      errors: {},
      errorCode: null,

      isLoading: false,
      isError: false
    }
  },
  methods: {
    autofill() {
      this.name = 'gdjhghjkgf',
      this.surname = 'gdfgdfg',
      this.username = 'pdgdsfgdsg',
      this.email = 'gfhgfh@fdggh.com',
      this.password = '123456789',
      this.password_confirmation = '123456789'
    },
    submit() {
      if (this.$refs.form.validate()) {
        this.register();
      }
    },
    register() {
      this.errors = {}
      this.isError = false
      this.isLoading = true

      const that = this
      this.$auth.register({
        data: {
          name: that.name,
          surname: that.surname,
          username: that.username,
          gender: that.gender,
          email: that.email,
          password: that.password,
          password_confirmation: that.password_confirmation
        },
        success: function () {
          this.$router.push({name: 'offers', params: {successRegistrationRedirect: true}})
        },
        error: function (res) {
          console.log(res)          
          if (res.response.status != 422){
            that.isError = true
            that.errorCode = res.response.status
          }
          that.isLoading = false
          that.error = res.response.data.error
          that.errors = res.response.data.errors || {}
        }
      })
    }
  }
}
</script>

<style scoped>
  .loading {
    margin: 0 auto;
    height: 30px; 
    width: 30px;
  }
</style>
