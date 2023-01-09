<!-- Finished auction fullscreen modal -->
<template>
  <v-container
    class="history-container"
  >
    <v-card>
      <v-card-title
        @click="isHistoryExpanded = !isHistoryExpanded"
      >
        <h2>{{ auctionType }} Auctions</h2>
        <v-spacer />
        <v-icon
          v-if="!isHistoryExpanded"
        >
          mdi-chevron-down
        </v-icon>
        <v-icon
          v-else
        >
          mdi-chevron-up
        </v-icon>
      </v-card-title>

      <v-card-text
        v-if="isHistoryExpanded"
      >
        <v-card
          v-for="auction in pAuctionArray"
          :key="auction.id"
          outlined
          class="object-card"
        >
          <AuctionDisplay
            :pAuction="auction"
            :pType="pType"
          />
        </v-card>
      </v-card-text>
    </v-card>
  </v-container>
</template>

<script>
export default {
  components: {
    AuctionDisplay: () => import('./AuctionDisplay')
  },
  props: {
    pAuctionArray: {type: Array, default: null},
    pType: {type: String, default: null},
  },
  data () {
    return {
      isHistoryExpanded: false
    }
  },
  computed: {
    auctionType: function () {
      if (this.pType) {
        switch (this.pType) {
          case 'charity':
            return 'Charity'
            break
        
          case 'commercial':
            return 'Commercial'
            break
        }
      }
      return null
    }
  }
}
</script>

<style scoped>
  .history-container {
    background-color: white;
  }
  .object-card {
    width: 100%;
  }
</style>