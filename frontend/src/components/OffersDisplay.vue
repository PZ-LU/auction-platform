<template>
  <div>
    <div
      v-if="pIsTiled"
    >
      <v-card
        outlined
      >
        <v-card-title>
          <div
            class="profile-pic"
          >
            <v-img
              width="32"
              height="32"
              :src="pOffer.preview_image"
            />
          </div>
          {{ pOffer.title }}

          <v-spacer />
          <v-dialog
            v-model="showOfferDialog"
            max-width="1500px"
          >
            <template #activator="{on}">     
              <v-btn
                text
                v-on="on"
              >
                Expand
              </v-btn>                           
            </template>
            <ShowOffer
              :pCategories="pCategories"
              :pOffer="pOffer"
              @closeShowDialog="closeShowOfferDialog"
            />
          </v-dialog>
        </v-card-title>
      </v-card>
    </div>
    
    <div
      v-if="!pIsTiled"
    >
      <v-card style="border-radius: 5px 5px 0 5px;">
        <v-row
          ml-5
          class="title"
        >
          <v-card-title
            pl-5
            class="headline"
          >
            <div>
              {{ pOffer.title }}
            </div>
            <v-spacer />
            <v-btn
              v-if="pOffer.canBeFavorited"
              icon
              @click="favoriteOffer()"
            >
              <v-icon
                v-if="pOffer.isFavorite"
              >
                mdi-heart
              </v-icon>
              <v-icon
                v-else
              >
                mdi-heart-outline
              </v-icon>
            </v-btn>
          </v-card-title>
        </v-row>
        <v-row>
          <v-col lg="4">
            <image-lightbox
              :pMaxHeight="'250px'"
              :src="pOffer.preview_image"
            />
          </v-col>

          <v-col>
            <v-card-text>{{ pOffer.body }}</v-card-text>
          </v-col>
        </v-row>
      </v-card>
      <v-row
        class="justify-end"
        mr-5
      >
        <v-dialog
          v-model="showOfferDialog"
          max-width="1500px"
        >
          <template #activator="{on}">     
            <v-btn
              x-large
              style="border-radius: 0 0 5px 5px;margin-right: 11px"
              color="primary"
              v-on="on"
            >
              More
            </v-btn>                           
          </template>
          <ShowOffer
            :pCategories="pCategories"
            :pOffer="pOffer"
            @closeShowDialog="closeShowOfferDialog"
          />
        </v-dialog>                                      
      </v-row>
    </div>
  </div>
</template>

<script>
import ImageLightbox from '@/components/helpers/ImageLightbox.vue'
import ShowOffer from '@/components/ShowOffer.vue'
import fetchFavoriteOffers from '@/plugins/fetchFavoriteOffers.js'

export default {
  components: {
    ImageLightbox,
    ShowOffer
  },
  props: {
    pCategories: { type: Array, default: null },
    pOffer: {type: Object, default: null},
    pIsTiled: {type: Boolean, default: false},
    pIsAdmin: {type: Boolean, default: false},
  },
  data () {
    return {
      showOfferDialog: false
    }
  },
  methods: {
    closeShowOfferDialog () {
      this.showOfferDialog = false
      this.$emit('reloadOffers')
    },
    favoriteOffer () {
      const config = { 
        headers: { 
          'Authorization': 'Bearer '+this.$auth.token(),
          'Content-Type': 'multipart/form-data' 
        }
      }

      const favoritedOfferData = new FormData()
      favoritedOfferData.append('offer_id', this.pOffer.id)
      favoritedOfferData.append('user_id', this.$auth.user().id)
      if (!this.pOffer.isFavorite) {
        favoritedOfferData.append('action', 'set')
      } else {
        favoritedOfferData.append('action', 'delete')
      }
      this.$axios
        .post('auth/offers/setFavorite', favoritedOfferData, config)
        .then(async () => {
          const result = await fetchFavoriteOffers(this.$auth.user().id)
          if (result) {
            this.$store.commit('setFavoriteOffers', result)
          }
          this.$emit('updateOffers')
        })
        .catch(err => {
          console.log(err)          
        })
    }
  }
}
</script>

<style scoped>
  .title {
    display: contents;
  }
  .profile-pic {
    margin-right: 30px;
  }
</style>