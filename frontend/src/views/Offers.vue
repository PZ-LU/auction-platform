<template>
  <v-app>
    <v-container>
      <v-row>
        <v-col 
          cols="3"
        >
          <OffersFilters
            :pCategories="categories"
            @updateFilters="update_page_filters()"
          />
        </v-col>
        <v-col
          cols="9"
        >
          <v-card
            outlined
          >
            <v-row>
              <v-card-title class="offers-title display-1">
                Offer list
              </v-card-title>
              <v-spacer />
              <v-card-actions class="pr-6">
                <v-btn
                  icon
                  class="fetchOffersBtn"
                  @click="offers_update()"
                >
                  <v-icon>mdi-autorenew</v-icon>
                </v-btn>
                <v-dialog
                  persistent
                  v-model="addOfferDialog"
                  max-width="1500px"
                >
                  <template #activator="{on}">     
                    <v-btn
                      depressed
                      v-if="getAuthState()"
                      v-on="on"
                    >
                      Add Offer
                    </v-btn>                                
                  </template>
                  <AddOffer
                    :pCategories="categories"
                    @closeAddOfferDialog="closeAddOfferDialog()" 
                  />
                </v-dialog>
              </v-card-actions>
            </v-row>
                        
            <v-divider />                        
            <v-container
              id="offers"
              pa-0
            >
              <v-card v-if="isError">
                <ConnectionError :error-code="errorCode" />
              </v-card>
                            
              <v-container
                v-if="isLoading"
                id="loading"
                class="loading"
              >
                <Loading />
              </v-container>
                            
              <v-container
                v-if="!isLoading"
                class="offers-wrapper"
              >
                <v-row
                  v-if="isEmptySet"
                  style="margin-top: 25%;"
                  align="center"
                  justify="center"
                >
                  <v-col lg="3">
                    <v-divider />    
                  </v-col>
                  <v-col lg="3">
                    <p
                      style="text-align: center;"
                      align-content-center
                    >
                      Nothing to show
                    </p>
                  </v-col>
                  <v-col lg="3">
                    <v-divider />    
                  </v-col>
                </v-row>
                <v-container
                  v-for="item in offers"
                  :key="item.id"
                  justify-space-around
                >
                  <OffersDisplay 
                    :pOffer="item"
                    :pCategories="categories"
                    @updateOffers="offers_update()"
                    @reloadOffers="offers_update()"
                  />
                </v-container>
              </v-container>    
            </v-container>
            <v-divider />
            <v-pagination 
              v-model="page_number"
              style="margin: 15px;"
              :length="page_count"
              :total-visible="7"
              :disabled="isPaginationDisabled"
              @input="offers_update"
              @next="offers_update"
              @previous="offers_update"
            />
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </v-app>
</template>

<script>
import AddOffer from '@/components/AddOffer.vue'
import OffersDisplay from '@/components/OffersDisplay.vue'
import OffersFilters from '@/components/OffersFilters.vue'
import Loading from '@/components/helpers/Loading.vue'
import ConnectionError from '@/components/helpers/ConnectionError.vue'
import axios from 'axios'
import { mapGetters } from 'vuex'

export default {
  name: 'Offers',
  components: {
    AddOffer,
    OffersDisplay,
    OffersFilters,
    Loading,
    ConnectionError
  },
  data() {
    return {
      offers: [],
      categories: [],
      page_count: null,
      page_number: 1,
      offersFilters: {},

      isPaginationDisabled: false,
      isLoading: true,
      isError: false,
      isEmptySet: false,

      addOfferDialog: false,

      errorCode: null
    }
  },
  computed: {
    favoriteOffers: function () {
      return this.$store.getters['favoriteOffers']
    }
  },
  created() {
    this.fetchOffers('offers')
    this.fetchCategories()
  },
  methods: {
    ...mapGetters ([
      'getAuthState',
    ]),
    closeShowDialog (offerId) {     
      const buffer = this.offers
      buffer.map(offer => {
        if (offer.id === offerId) {
          offer.showOfferDialog = false
        }
      })
      this.offers = buffer
    },
    fetchCategories () {
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
          this.responseError()
        })
    },
    fetchOffers (path) {
      this.$axios
        .get(path)
        .then(res => {
          const {data:{data}} = res
          if (!data.length)
            this.isEmptySet = true
          else
            this.isEmptySet = false
          this.setOffers(data)
          this.isLoading = false
          const {data:{meta}} = res
          this.isPaginationDisabled = false
          this.setPageCount(meta.last_page)
        })
        .catch(err => {
          console.log(err);
          this.errorCode = err
          this.responseError()
        })
    },
    responseError () {
      this.isLoading = false;
      this.isError = true;
      return true;
    },
    setOffers (data) {
      this.offers = data

      if (this.$auth.user().id) {
        this.offers.map(offer => {
          offer.showOfferDialog = false
          if (offer.author_id !== this.$auth.user().id) {
            offer.canBeFavorited = true
          } else {
            offer.canBeFavorited = false
          }

          if (this.favoriteOffers.find(record => record.offer_id === offer.id && record.user_id === this.$auth.user().id)) {
            offer.isFavorite = true
          } else {
            offer.isFavorite = false
          }
        })
      }
    },
    setPageCount (data) {
      this.page_count = data;
    },
    getPageNumber () {
      return this.page_number;
    },
    getOffers () {
      return this.offers;
    },
    getPageCount () {
      return this.page_count;
    },
    update_page_filters() {
      this.page_number = 1;
      this.page_count = 1;
      this.offers_update();
    },
    offers_update () {
      this.offersFilters = this.$store.getters['offersFilters']
      this.isPaginationDisabled = true;
      this.isLoading = true;
      this.isError = false;
      const pagePath = 
        `offers?page=${this.page_number}&title=${this.offersFilters.title || ''}&description=${this.offersFilters.description || ''}&tags=${this.offersFilters.tags || ''}&category=${this.offersFilters.category || ''}&date=${this.offersFilters.date || ''}`
      this.fetchOffers(pagePath);
    },
    closeAddOfferDialog () {
      this.offers_update()
      this.addOfferDialog = false
    },
    showMore (itemID) {
      return this.offers[itemID];
    }
  },
}
</script>

<style scoped>
  #offers {
    margin: 0 auto;
    display: block;
    height: 690px;
    /* max-width: 50%; */
    overflow: auto;
  }
  .loading {
    margin: 0 auto;
    padding-top: 25%;
    padding-left: 49%;
  }
  .err {
    padding-top: 12em;
    line-height: 2em;
  }
  #offers h2 {
    text-align: left;
  }
  img {
    padding-left: 12px;
    max-width: 300px;
  }
  .offers {
    overflow: hidden;
    /* border: solid 4px; */
  }
  .offers h2 {
    position: relative;
    /* border: solid 1px red; */
  }
  .offers p {
    position: relative;
    width: 50%;
    float: left;
    /* border: solid 1px red; */
    margin-bottom: 20px;
    text-align: justify;
  }
  .offers button {
    margin-top: 20px;
    margin-right: 50px;
    padding: 20px;
    float: right;
  }
  .offers-title {
    margin-left: 10px;
  }
  .fetchOffersBtn {
    margin-right: 15px;
  }
</style>