<template>
  <v-container>
    <div
      v-if="charityAuctions.length"
    >
      <v-card
        v-for="auction in charityAuctions"
        :key="auction.id"
        outlined
        class="object-card"
      >
        <AuctionDisplay
          :pAuction="auction"
          :pType="'charity'"
          :pMin="true"
        />
      </v-card>
    </div>
    <v-card
      v-else
    >
      <p>No recent auctions. Check Auction tab</p>
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
      fetchAuctions('auctions', 'latest')
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