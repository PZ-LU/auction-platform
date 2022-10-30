<template>
  <div>
    <v-list
      flat
    >
      <v-list-item-group
        v-if="pAuctions"
      >
        <v-list-item
          v-for="auction in pAuctions"
          :key="auction.id"
          @click="auction.showDialog = true"
        >
          <v-list-item-content>
            <v-list-item-title
              class="item-title"
            >
              <div>
                {{ auction.auction_object.name }}
              </div>
              <div
                class="finished-caption"
                v-if="isFinished() && activeAuction"
              >
                <h3>Finished</h3>
              </div>
              <v-spacer />
              <v-dialog
                v-model="auction.showDialog"
              >
                <template #activator="{ on }">
                  <v-btn
                    v-on="on"
                    small
                    text
                    @click="auction.showDialog = true"
                  >
                    EXPAND
                  </v-btn>
                </template>

                <div
                  v-if="!showFinishDialog"
                  style="background-color: white;"
                >
                  <AuctionDisplay
                    :pAuction="auction"
                    :pType="auction.type"
                  />
                  <div
                    v-if="isFinished()"
                  >
                    <v-btn
                      text
                      block
                      color="primary"
                      @click="finishAuction(auction)"
                    >
                      Finish auction
                    </v-btn>
                  </div>
                </div>
                <v-card
                  v-if="showFinishDialog"
                >
                  <v-card-title>
                    Auction finished!
                  </v-card-title>
                  <v-card-actions>
                    <v-btn
                      text
                      @click="closeFisnishDialog()"
                    >
                      Ok
                    </v-btn>
                  </v-card-actions>
                </v-card>
              </v-dialog>
            </v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list-item-group>
    </v-list>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  components: {
    AuctionDisplay: () => import('../AuctionDisplay')
  },
  props: {
    pAuctions: {type: Array, default: null},
    pIsActive: {type: Boolean, default: false}
  },
  data () {
    return {
      activeAuction: null,
      showFinishDialog: false
    }
  },
  created () {
    if (this.pIsActive) {
      this.activeAuction = this.pAuctions[0]
    }
  },
  methods: {
    closeFisnishDialog() {
      this.$emit('updateAuctions')
    },
    finishAuction (auction) {
      const config = { 
        headers: { 
          'Authorization': 'Bearer '+this.$auth.token(),
          'Content-Type': 'multipart/form-data' 
        }
      }
      const finishingAuctionData = new FormData()
      finishingAuctionData.append('auction_id', auction.id)
      this.$axios
        .post('auth/auction/finish', finishingAuctionData, config)
          .then (res => {
            this.showFinishDialog = true
          })
          .catch ((err) => {
            console.log(err)
          })
    },
    isFinished () {
      if (this.activeAuction) {
        switch (this.activeAuction.type) {
          case 'charity':
            let sum = 0
            this.activeAuction.participants.map(participant => sum += parseInt(participant.amount))
            if (sum >= this.activeAuction.auction_data[0].goal) {
              return true
            }
            return false
        
          case 'commercial':
            if (new Date().getTime() > new Date(this.activeAuction.auction_data[0].end_date).getTime()) {
              return true
            }
            return false
        }
      }
      return false
    }
  }
}
</script>

<style scoped>
  .finished-caption {
    margin-left: 10px;
  }
  .item-title {
    display: flex;
  }
</style>