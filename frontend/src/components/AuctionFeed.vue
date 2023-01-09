<!-- Home view auction feed -->
<template>
  <div>
    <v-container
      v-if="charityAuctions.length"
      class="feed-wrapper"
    >
      <div
        v-if="charityAuctions.length"
        class="feed-container"
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
    </v-container>
    <v-container
      v-if="commercialAuctions.length"
      class="feed-wrapper"
    >
      <div
        v-if="commercialAuctions.length"
        class="feed-container"
      >
        <v-card
          v-for="auction in commercialAuctions"
          :key="auction.id"
          outlined
          class="object-card"
        >
          <!-- Display minimalistic tiles -->
          <AuctionDisplay
            :pAuction="auction"
            :pType="'commercial'"
            :pMin="true"
          />
        </v-card>
      </div>
    </v-container>
    <v-card
      v-else
    >
      <p>No recent auctions. Check Auction tab</p>
    </v-card>
  </div>
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
      fetchAuctions('latest')
        .then(res => {
          this.charityAuctions = res.charityAuctions
          this.commercialAuctions = res.commercialAuctions
        })
    },
  }
}
</script>

<style scoped>
  .feed-wrapper {
    overflow-x: auto;
  }
  .feed-container {
    display: inline-flex;
    overflow: hidden;
  }
  .object-card {
    display: inline-block;
    margin-right: 50px;
  }
</style>