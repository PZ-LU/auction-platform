<template>
  <v-card
    outlined
  >
    <v-card-title>
      User Offers
      <v-spacer />
      <v-btn
        icon
        @click="fetchAll()"
      >
        <v-icon>mdi-autorenew</v-icon>
      </v-btn>
    </v-card-title>
    <v-card-subtitle>
      Active Offers
    </v-card-subtitle>
    <v-container
      class="offers-container"
    >
      <v-row
        v-for="offer in activeOffers"
        :key="offer.id"
      >
        <OffersDisplay
          class="offer"
          :pOffer="offer"
          :pCategories="categories"
          :pIsTiled="true"
          :pIsAdmin="true"
          @reloadOffers="fetchAll"
        />
      </v-row>
    </v-container>

    <v-card-subtitle>
      Archived Offers
    </v-card-subtitle>
    <v-container
      class="offers-container"
    >
      <v-row
        v-for="offer in archivedOffers"
        :key="offer.id"
      >
        <OffersDisplay
          class="offer"
          :pOffer="offer"
          :pCategories="categories"
          :pIsTiled="true"
          :pIsAdmin="true"
          @reloadOffers="fetchAll"
        />
      </v-row>
    </v-container>
  </v-card>
</template>

<script>
import axios from 'axios'

export default {
  components: {
    OffersDisplay: () => import('../OffersDisplay')
  },
  data () {
    return {
      activeOffers: [],
      archivedOffers: [],
      categories: []
    }
  },
  created () {
    this.fetchAll()
  },
  methods: {
    fetchAll () {
      this.fetchCategories()
      this.fetchAllOffers()
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
    fetchAllOffers () {
      this.activeOffers = []
      this.archivedOffers = []
      const config = { 
        headers: { 
          'Authorization': 'Bearer '+this.$auth.token(),
        }
      }
      this.$axios
        .get('auth/offers')
        .then(res => {
          const {data:{data}} = res
          data.map(offer => {
            switch (offer.status) {
              case 'active':
                this.activeOffers.push(offer)
                break

              case 'archived':
                this.archivedOffers.push(offer)
                break
            }
          })
        })
        .catch(err => {
          console.log(err);
          this.errorCode = err
          this.responseError()
        })
    },
  }
}
</script>

<style scoped>
  .offers-container {
    max-height: 500px;
    overflow: auto;
  }
  .offer {
    width: 100%;
  }
</style>