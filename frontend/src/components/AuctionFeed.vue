<template>
  <v-container>
    <v-card
      v-for="auction in charityAuctions"
      :key="auction.id"
      outlined
      class="object-card"
    >
      <AuctionDisplay
        :pAuction="auction"
        :pType="pType"
        :pMin="true"
      />
    </v-card>
  </v-container>
</template>

<script>
import fetchAuctions from '../plugins/fetchAuctions'

export default {
  components: {
    AuctionDisplay: () => import('./AuctionDisplay')
  },
  data () {
    return {
      charityAuctions: [],
      commercialAuctions: [],
    }
  },
  created () {
    this.fetchLatestAuctions()
  },
  methods: {
    async fetchLatestAuctions() {
      fetchAuctions('http://127.0.0.1:8000/api/auctions', 'latest')
        .then(res => {
          this.charityAuctions = res.charityAuctions
          this.commercialAuctions = res.commercialAuctions
        })
    },
  }
}
</script>

<style>

</style>