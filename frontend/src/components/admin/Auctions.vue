<template>
  <v-card
    outlined
  >
    <v-card-title>Active Auctions</v-card-title>
    <v-divider />
    <v-card-subtitle>
      <h3>Charity</h3>
    </v-card-subtitle>
    <AuctionList
      v-if="activeCharityAuction"
      :pAuctions="[activeCharityAuction]"
      :pIsActive="true"
      @updateAuctions="closeDialog()"
    />
    <v-card-actions
      v-else
    >
      <v-dialog
        v-model="showCharityCreateDialog"
      >
        <template
          #activator="{ on }"
        >
          <v-btn
            depressed
            block
            color="primary"
            v-on="on"
          >
            Add Auction
          </v-btn>
        </template>

        <AddAuction
          :pType="'charity'"
          @closeDialog="showCharityCreateDialog = false"
        />
      </v-dialog>
    </v-card-actions>

    <v-card-subtitle>
      <h3>Commercial</h3>
    </v-card-subtitle>
    <AuctionList
      v-if="activeCommercialAuction"
      :pAuctions="[activeCommercialAuction]"
      :pIsActive="true"
      @updateAuctions="closeDialog()"
    />
    <v-card-actions
      v-else
    >
      <v-dialog
        v-model="showCommericalCreateDialog"
      >
        <template
          #activator="{ on }"
        >
          <v-btn
            depressed
            block
            color="primary"
            v-on="on"
          >
            Add Auction
          </v-btn>
        </template>

        <AddAuction
          :pType="'commercial'"
          @closeDialog="closeDialog()"
        />
      </v-dialog>
    </v-card-actions>

    <v-divider />
    <v-card-title>Dismissed Auctions</v-card-title>
    <v-divider />
    <v-card-subtitle>
      <h3>Charity</h3>
    </v-card-subtitle>
    <AuctionList
      :pAuctions="charityAuctions"
    />
    
    <v-card-subtitle>
      <h3>Commercial</h3>
    </v-card-subtitle>
    <AuctionList
      :pAuctions="commercialAuctions"
    />
  </v-card>
</template>

<script>
import fetchAuctions from '../../plugins/fetchAuctions'

export default {
  components: {
    AddAuction: () => import('./AddAuction'),
    AuctionList: () => import('./AuctionList')
  },
  data () {
    return {
      activeCharityAuction: null,
      activeCommercialAuction: null,

      charityAuctions: [],
      commercialAuctions: [],

      showCharityCreateDialog: false,
      showCommericalCreateDialog: false
    }
  },
  created () {
    this.fetchActiveAuctions()
    this.fetchDismissedAuctions()
  },
  methods: {
    closeDialog() {
      this.showCharityCreateDialog = false
      this.showCommericalCreateDialog = false
      this.fetchActiveAuctions()
      this.fetchDismissedAuctions()
    },
    fetchActiveAuctions() {
      this.activeCharityAuction = null
      this.activeCommercialAuction = null
      fetchAuctions('auctions')
        .then(res => {
          this.activeCharityAuction = res.charityAuctions
          this.activeCommercialAuction = res.commercialAuctions
        })
    },
    fetchDismissedAuctions() {
      fetchAuctions('auctions/dismissed', 'dismissed')
      .then(res => {
        this.charityAuctions = res.charityAuctions
        this.commercialAuctions = res.commercialAuctions
        for (const auction of this.charityAuctions) {
          auction['showDialog'] = false
        }
        for (const auction of this.commercialAuctions) {
          auction['showDialog'] = false
        }
      })
    }
  }
}
</script>

<style scoped>
  .v-card__subtitle {
    padding-top: 20px;
    padding-bottom: 0;
  }
</style>