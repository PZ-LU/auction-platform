<!-- User dashboard view -->
<template>
  <v-app>
    <v-container>
      <v-row>
        <v-col lg="2">
          <v-card>
            <div
              class="profile-img"
            >
              <!-- Change profile image -->
              <v-img
                class="mx-auto " 
                height="200px" 
                width="200px" 
                :src="$auth.user().avatar_path"
                @click="$refs.avatarInput.$refs.input.click()"
              />
            </div>
            <v-file-input
              @click="handleUpload"
              @click:clear="handleUpload"
              v-model="avatarFile"
              show-size
              ref="avatarInput"
              width="10px"
              accept="image/png, image/gif, image/jpeg, image/webp, image/bmp, image/svg"
            />
            <v-btn
              v-if="showAvatarUpdateButton"
              @click="uploadAvatar()"
              block
              depressed
            >
              Upload
            </v-btn>
            <v-card-title primary-title>
              <div>
                <h3 class="headline mb-0">
                  {{ $auth.user().username }}
                </h3>
                <div>
                  {{ $auth.user().name }} {{ $auth.user().surname }}
                </div>
              </div>
            </v-card-title>
            <v-card-actions>
              <v-dialog
                persistent
                v-model="editDialog"
                max-width="800px"
              >
                <!-- Change user info -->
                <template #activator="{on}">                                     
                  <v-btn 
                    text 
                    color="primary"
                    v-on="on" 
                  >
                    Edit
                  </v-btn>
                </template>

                <v-card>
                  <v-card-title>
                    Edit profile
                    <v-spacer />
                    <v-btn
                      icon
                      @click="editDialog = false"
                    >
                      <v-icon>mdi-close</v-icon>
                    </v-btn>
                  </v-card-title>
                  <v-card-text>
                    <v-form ref="form">
                      <v-container>
                        <v-row class="align-center">
                          <v-col lg="2">
                            <v-subheader
                              color="black"
                              class="align-center title"
                            >
                              Name
                            </v-subheader>
                          </v-col>
                          <v-col lg="4">
                            <v-text-field
                              v-model="name"
                              :counter="24"
                              :rules="[rules.textOnly, rules.length.name]"
                            />
                          </v-col>
                          <v-col lg="2">
                            <v-subheader
                              color="black"
                              class="title"
                            >
                              Surname
                            </v-subheader>
                          </v-col>
                          <v-col lg="4">
                            <v-text-field
                              v-model="surname"
                              :counter="24"
                              :rules="[rules.textOnly, rules.length.surname]"
                            />
                          </v-col>
                        </v-row>

                        <v-row class="align-center">
                          <v-col lg="2">
                            <v-subheader
                              color="black"
                              class="title"
                            >
                              Username
                            </v-subheader>
                          </v-col>
                          <v-col lg="4">
                            <v-text-field
                              v-model="username"
                              :counter="16"
                              :rules="[rules.length.newUsername]"
                            />
                          </v-col>
                        </v-row>

                        <v-row>
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
                        </v-row>

                        <v-row class="align-center">
                          <v-col lg="2">
                            <v-subheader
                              color="black"
                              class="title"
                            >
                              Email
                            </v-subheader>
                          </v-col>
                          <v-col lg="4">
                            <v-text-field
                              v-model="email"
                              :rules="[rules.newEmail]"
                            />
                          </v-col>

                          <v-col>
                            <v-radio-group
                              v-model="gender"
                              :label="'Gender'"
                              class="pl-4 pt-0 mt-0"
                            >
                              <v-radio
                                color="primary"
                                :label="'Male'"
                                :value="'male'"
                              />
                              <v-radio
                                color="primary"
                                :label="'Female'"
                                :value="'female'"
                              />
                            </v-radio-group>
                          </v-col>
                        </v-row>

                        <v-row>
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
                        </v-row>

                        <v-row class="justify-center">
                          <v-btn
                            text
                            color="primary"
                            @click="changePass = !changePass"
                          >
                            Change Password
                          </v-btn>
                        </v-row>

                        <v-expand-transition>
                          <v-container>
                            <v-text-field
                              v-show="changePass"
                              v-model="password"
                              prepend-icon="mdi-lock"
                              type="password"
                              label="New Password"
                              :counter="16"
                              :rules="[rules.length.newPassword]"
                            />
                            <v-text-field
                              v-show="changePass"
                              v-model="password_confirmation"
                              prepend-icon="mdi-lock"
                              label="Confirm Password"
                              type="password"
                              :rules="[scoped_rules.passwordConfirmation]"
                            />
                          </v-container>
                        </v-expand-transition>
                                                
                        <v-card-actions>
                          <v-btn 
                            :disabled="isLoading"
                            :loading="isLoading"
                            @click="submit()"
                          >
                            Submit
                          </v-btn>
                        </v-card-actions>
                      </v-container>
                    </v-form>
                  </v-card-text>
                </v-card>
              </v-dialog>

              <!-- Delete profile -->
              <v-dialog
                persistent
                v-model="deleteDialog"
                max-width="800px"
              >
                <template #activator="{on}">     
                  <v-btn 
                    text 
                    color="error"
                    v-on="on" 
                  >
                    Delete
                  </v-btn>                                
                </template>

                <v-card>
                  <v-card-title>Delete Account</v-card-title>
                  <v-card-text>
                    <p>Attention: You are deleting Your account. This cannot be undone. Would You like to proceed?</p> 
                  </v-card-text>
                  <v-card-actions>
                    <v-btn
                      color="error"
                      @click="deleteAcc()"
                    >
                      Delete
                    </v-btn>
                    <v-btn
                      color="primary"
                      @click="deleteDialog = !deleteDialog"
                    >
                      Cancel
                    </v-btn>
                  </v-card-actions>
                </v-card>
              </v-dialog>
            </v-card-actions>
          </v-card>
        </v-col>

        <v-col>
          <!-- Display placed and favorite offers -->
          <v-card>
            <v-card-title primary-title>
              <v-container
                class="offers-title"
              >
                <v-row>
                  <h3 class="headline mb-0">
                    Your Offers
                  </h3>
                  <v-spacer />
                  <v-btn
                    icon
                    @click="fetchProfileOffers(userOffersPath)"
                  >
                    <v-icon>mdi-autorenew</v-icon>
                  </v-btn>
                </v-row>
              </v-container>
            </v-card-title>
            <v-divider />
            <v-card-text>
              <v-tabs
                fixed-tabs
                v-model="offerTabs"
                class="offers-tabs"
              >
                <v-tab>
                  Placed offers
                </v-tab>
                <v-tab>
                  Bookmarked offers
                </v-tab>

                <v-tabs-items
                  class="user-offers"
                  v-model="offerTabs"
                >
                  <v-tab-item>
                    <v-card
                      v-if="offers"
                    >
                      <ConnectionError
                        v-if="errorCode"
                        :errorCode="errorCode"
                      />
                      <div
                        v-for="offer in offers"
                        :key="offer.id"
                      >
                        <OffersDisplay
                          :pOffer="offer"
                          :pCategories="categories"
                          :pIsTiled="true"
                        />
                      </div>
                    </v-card>
                  </v-tab-item>
                  <v-tab-item>
                    <v-card
                      v-if="bookmarkedOffers"
                    >
                      <ConnectionError
                        v-if="errorCode"
                        :errorCode="errorCode"
                      />
                      <div
                        v-for="offer in bookmarkedOffers"
                        :key="offer.id"
                      >
                        <OffersDisplay
                          :pOffer="offer"
                          :pCategories="categories"
                          :pIsTiled="true"
                        />
                      </div>
                    </v-card>
                  </v-tab-item>
                </v-tabs-items>
              </v-tabs>
            </v-card-text>
          </v-card>

          <!-- Display participation in auctions -->
          <v-card
            class="auction-card"
          >
            <v-card-title>
              <v-container
                class="offers-title"
              >
                <v-row>
                  <h3 class="headline mb-0">
                    Participated Auctions
                  </h3>
                </v-row>
              </v-container>
            </v-card-title>
            <v-divider />
            <v-card-subtitle>
              Active
            </v-card-subtitle>
            <div
              v-if="activeCharityAuction"
            >
              <AuctionHistory
                :pAuctionArray="[activeCharityAuction]"
                :pType="'charity'"
              />
            </div>

            <div
              v-if="activeCommercialAuction"
            >
              <AuctionHistory
                :pAuctionArray="[activeCommercialAuction]"
                :pType="'commercial'"
              />
            </div>

            <v-card-subtitle>
              Dismissed
            </v-card-subtitle>
            <AuctionHistory
              v-if="charityAuctions.length"
              :pAuctionArray="charityAuctions"
              :pType="'charity'"
            />

            <AuctionHistory
              v-if="commercialAuctions.length"
              :pAuctionArray="commercialAuctions"
              :pType="'commercial'"
            />
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </v-app>
</template>

<script>
import axios from 'axios'
import external_rules from '@/plugins/rules/rules.js'

export default {
  components: {
    AuctionHistory: () => import('../components/AuctionHistory'),
    ConnectionError: () => import('../components/helpers/ConnectionError'),
    OffersDisplay: () => import('../components/OffersDisplay')
  },
  data() {
    return {
      email: this.$auth.user().email,
      username: this.$auth.user().username,
      name: this.$auth.user().name,
      surname: this.$auth.user().surname,
      gender: this.$auth.user().gender,
      password: null,
      password_confirmation: null,
      scoped_rules: {
        passwordConfirmation: v => ((!v && !this.password) || (v && v == this.password) ) || 'Fields do not match',
      },

      rules: external_rules,

      editDialog: false,
      deleteDialog: false,
      changePass: false,

      userObject: {},

      errors: {},
      errorCode: null,

      isLoading: false,
      isError: false,

      offerTabs: null,

      offers: [],
      categories: [],
      isEmptySet: false,

      userOffersPath: 'auth/offer/get',

      avatarFile: null,
      showAvatarUpdateButton: false,

      bookmarkedOffers: null,

      activeCharityAuction: null,
      activeCommercialAuction: null,

      charityAuctions: [],
      commercialAuctions: []
    }
  },
  computed: {
    favoriteOffers: function () {
      return this.$store.getters['favoriteOffers']
    }
  },
  watch: {
    avatarFile: {
      handler: function () {
        if (this.avatarFile) {
          let fileSize = this.avatarFile.size / 1024 / 1024
          if (fileSize > 1.5) {
            this.avatarFile = null
            alert("Image size cannot exceed 1.5 MiB!")
          } else
            this.showAvatarUpdateButton = true
        } else {
          this.showAvatarUpdateButton = false
        }
      }
    }
  },
  created() {
    this.fetchProfileOffers(this.userOffersPath)
    this.fetchOffers()
    this.fetchCategories()
    this.fetchUserAuctions()
  },
  methods: {
    async fetchUserAuctions() {
      const userData = new FormData()
      userData.append('user_id', this.$auth.user().id)
      const config = { 
        headers: { 
          'Authorization': 'Bearer '+this.$auth.token(),
          'Content-Type': 'multipart/form-data' 
        }
      }
      this.$axios
        .post(`auth/auctions/`, userData, config)
        .then(res => {
          const {data} = res
          const activeAutcions = data[0]
          const dismissedAuctions = data[1]
          if (activeAutcions.length) {
            for (const auction of activeAutcions) {
              switch (auction.type) {
                case 'charity':
                  this.activeCharityAuction = auction
                  break
              
                case 'commercial':
                  this.activeCommercialAuction = auction
                  break
              }
            }
          }
          if (dismissedAuctions.length) {
            for (const auction of dismissedAuctions) {
              switch (auction.type) {
                case 'charity':
                  this.charityAuctions.push(auction)
                  break
              
                case 'commercial':
                  this.commercialAuctions.push(auction)
                  break
              }
            }
          }
        })
        .catch(err => {
            console.log(`Error fetching user auctions: ${err}`)  
        });
    },
    submit () {
      if (this.$refs.form.validate()) {
        this.update();
      }
    },
    update () {
      // Update user info
      this.errors = {}
      this.isError = false
      this.isLoading = true
      this.userObject = {
        id: this.$auth.user().id,
        email: this.email,
        username: this.username,
        name: this.name,
        surname: this.surname,
        gender: this.gender,
        password: this.password,
        avatar_path: this.$auth.user().avatar_path
      }

      this.$axios
      .post('auth/userUpdate',
        { 
          user_id: this.$auth.user().id,
          email: this.email,
          username: this.username,
          name: this.name,
          surname: this.surname,
          gender: this.gender,
          password: this.password
        },
          { 
            headers: {
              'Authorization': 'Bearer '+this.$auth.token(),
            }
        }).then(() => {
          this.$auth.user(this.userObject)
          this.editDialog = false
          this.isLoading = false
        }).catch( err => { 
          if (err.response.status != 422){
            this.isError = true
            this.errorCode = err.response.status
          }
          this.isLoading = false
          this.error = err.response.data.error
          this.errors = err.response.data.errors || {}
        });
    },
    deleteAcc () {
      this.$auth.logout({
        success: function () {
          this.$axios
            .delete('auth/userDelete', this.$auth.user().id)
            .catch( err => {
              console.log('error: ', err)
            })
          this.changeAuthState()
        },
        error: function () {
          console.log("Something went wrong with Your logout!")
        },
      })
    },
    fetchCategories () {
      // Fetch categories for offer info
      this.$axios
        .get('tag_categories')
        .then(res => {
          const {data:{data}} = res
          if (data && !data.length){
            this.categories = [data]
          } else {
            this.categories = data
          }
        })
        .catch(err => {
          this.errorCode = err.response.status
        })
    },
    fetchOffers () {
      this.$axios
        .get('offers/getAll')
        .then(res => {
          const {data:{data}} = res
          let newBookmarks = []
          this.favoriteOffers.map(favoriteOffer => {
            data.map(offer => {
              if (favoriteOffer.offer_id === offer.id && favoriteOffer.user_id === this.$auth.user().id) {
                newBookmarks.push(offer)
              }
            })
          })
          this.bookmarkedOffers = newBookmarks
        })
        .catch(err => {
          this.errorCode = err.response
        })
    },
    fetchProfileOffers (path) {
      const offerData = new FormData()
      offerData.append('user_id', this.$auth.user().id)
      const config = {
        headers: { 
          'Authorization': 'Bearer '+this.$auth.token(),
          'Content-Type': 'multipart/form-data' 
        }
      }
      this.$axios
        .post(path, offerData, config)
        .then(res => {
          const {data:{data}} = res
          if (!data)
            this.isEmptySet = true
          else
            this.isEmptySet = false
          this.offers = data
        })
        .catch(err => {
          this.errorCode = err.response
        })
    },
    handleUpload () {
      console.log("Click")
      if (this.avatarFile) {
        this.showAvatarUpdateButton = true
      }
    },
    uploadAvatar () {
      this.showAvatarUpdateButton = false
      const avatarInfo = new FormData
      avatarInfo.append('user_id', this.$auth.user().id)
      avatarInfo.append('avatar', this.avatarFile)
      const config = { 
        headers: { 
          'Authorization': 'Bearer '+this.$auth.token(),
          'Content-Type': 'multipart/form-data' 
        }
      }
      this.$axios
        .post('auth/userUpdateAvatar', avatarInfo, config)
        .then(res => {
          this.$auth.fetch()
        })
        .catch(err => {
          console.log(err)
        })
    },
  }
}
</script>

<style scoped>
  .auction-card {
    margin-top: 30px;
  }
  .profile-img {
    border: 1px solid lightgrey;
  }
  .profile-img:hover {
    cursor: pointer;
    background-color: lightgray;
  }
  .offers-tabs {
    min-width: 100%;
  }
  .offers-title {
    padding-bottom: 0;
    padding-top: 0;
  }
  .user-offers {
    max-height: 250px;
    overflow: auto;
  }
</style>