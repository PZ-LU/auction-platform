<template>
  <v-container
    class="wrapper"
  >
    <v-row>
      <v-col
        md="6"
      >
        <CharityAuction
          v-if="activeCharityAuction"
          :pAuction="activeCharityAuction"
          @updateAuction="fetchActiveAuctions()"
        />
        <v-container
          v-else
          class="no-active-auction"
        >
          <v-divider />
          <h3
            class="title font-weight-light"
          >
            No active charity auction at the moment
          </h3>
          <v-divider />
        </v-container>
      </v-col>
      <v-col
        md="6"
      >
        <CommercialAuction
          v-if="activeCommercialAuction"
          :pAuction="activeCommercialAuction"
          @updateAuction="fetchActiveAuctions()"
        />
        <v-container
          v-else
          class="no-active-auction"
        >
          <v-divider />
          <h3
            class="title font-weight-light"
          >
            No active commercial auction at the moment
          </h3>
          <v-divider />
        </v-container>
      </v-col>
    </v-row>
    <v-container
      class="history-dialog"
    >
      <v-dialog
        v-model="showHistoryDialog"
        style="width: 100%;"
        fullscreen
      >
        <template
          #activator="{ on }"
        >
          <v-btn
            class="history-btn"
            v-on="on"
            large
          >
            History
          </v-btn>
        </template>

        <v-container
          class="history-container"
        >
          <v-row>
            <div
              class="close-btn-container"
            >
              <v-btn
                class="close-btn"
                @click="showHistoryDialog = false"
                icon
                outlined
              >
                <v-icon>mdi-close</v-icon>
              </v-btn>
            </div>
          </v-row>
          <v-row>
            <AuctionHistory 
              :pAuctionArray="charityAuctions"
              pType="charity"
            />
          </v-row>
          <v-row>
            <AuctionHistory
              :pAuctionArray="commercialAuctions"
              pType="commercial"
            />
          </v-row>
        </v-container>
      </v-dialog>
    </v-container>
  </v-container>
</template>

<script>
import fetchAuctions from '../plugins/fetchAuctions'

export default {
  components: {
    AuctionHistory: () => import('../components/AuctionHistory'),
    CharityAuction: () => import('../components/CharityAuction'),
    CommercialAuction: () => import('../components/CommercialAuction')
  },
  data () {
    return {
      activeCharityAuction: null,
      activeCommercialAuction: null,

      charityAuctions: [],
      commercialAuctions: [],

      showHistoryDialog: false
    }
  },
  watch: {
    charityAuctions: {
      deep: true,
      handler: function () {
        this.fetchActiveAuctions()
      }
    }
  },
  created () {
    this.fetchAuctions()
  },
  methods: {
    async fetchActiveAuctions() {
      fetchAuctions('auctions')
        .then(res => {
          this.activeCharityAuction = res.charityAuctions
          this.activeCommercialAuction = res.commercialAuctions
        })
    },
    async fetchDismissedAuctions() {
      fetchAuctions('auctions/dismissed', 'dismissed')
        .then(res => {
          this.charityAuctions = res.charityAuctions
          this.commercialAuctions = res.commercialAuctions
        })

    },
    fetchAuctions () {
      this.fetchActiveAuctions()
      this.fetchDismissedAuctions()
    }
  }
}
</script>

<style scoped>
  .close-btn {
    float: right;
    margin-right: 20px;
  }
  .close-btn-container {
    width: 100%;
  }
  .history-btn {
    position: fixed;
    margin: 0;
    width: 100%;
    left: 0;
    bottom: 0;
    border: 1px solid;
    border-radius: 25px 25px 0 0;
  }
  .history-container {
    height: 100%;
    min-width: 100%;
    background-color: white;
  }
  .history-dialog {
    text-align-last: end;
    position: absolute;
    top: 0;
    right: 0;
  }
  .no-active-auction {
    height: 70vh;
    vertical-align: middle;
    text-align: center;
    display: table-cell;
    vertical-align: middle;
  }
  .wrapper {
    margin-bottom: 100px;
  }
</style>