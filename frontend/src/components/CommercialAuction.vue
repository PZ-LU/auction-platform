<!-- Commercial auction content for Slot -->
<template>
  <AuctionTemplate
    :pAuction="pAuction"
    :pUsePayPal="false"
  >
    <v-container
      class="bid-container"
    >
      <div
        class="crucial-info-container"
      >
        <h2
          class="font-weight-light"
        >
          Starting bid: {{ pAuction.auction_data[0].start_bid }} EUR
        </h2>
        <v-spacer />
        <h3
          class="font-weight-light"
        >
          Ends on: <span class="font-weight-bold">{{ new Date(pAuction.auction_data[0].end_date).toLocaleDateString() }}</span>
        </h3>
      </div>

      <v-row
        v-if="checkUserParticipation"
      >
        <v-col
          class="bid-col"
        >
          <v-text-field
            v-model="bid"
            class="bid-field"
            :value="highestBid"
            :rules="[scoped_rules.commericalAmount, rules.types.integersOnly]"
            label="Amount (EUR)"
            outlined
          />
        </v-col>
        <v-col
          class="bid-col"
        >
          <v-dialog
            persistent
            v-model="showConfirmationDialog"
          >
            <template #activator="{ on }">
              <v-btn
                depressed
                color="primary"
                v-on="on"
                @click="showConfirmationDialog = true"
                :disabled="!correctAmount"
              >
                Place Bid
              </v-btn>
            </template>

            <v-card
              v-if="!isPaid"
            >
              <v-card-title>
                Attention!
              </v-card-title>
              <v-card-text>
                By placing a bid of {{ bid }} EUR
                You agree to pay the delivered amount within
                24 hours after the end of the auction.
                Continue?
              </v-card-text>
              <v-card-actions>
                <v-btn
                  text
                  color="primary"
                  @click="submitBid()"
                >
                  Ok
                </v-btn>
                <v-btn
                  text
                  color="error"
                  @click="showConfirmationDialog = false"
                >
                  Cancel
                </v-btn>
              </v-card-actions>
            </v-card>

            <v-card
              v-if="isPaid"
            >
              <v-card-title>
                {{ response.status }}
              </v-card-title>
              <v-card-text>
                <h1>{{ response.message }}</h1>
              </v-card-text>
              <v-card-actions>
                <v-btn
                  block
                  depressed
                  @click="closeDialog()"
                >
                  Ok
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-col>
      </v-row>

      <v-row
        class="bid-row"
        v-if="pAuction.auction_data[0].highest_bid_user_id"
      >
        <v-col>
          Highest bid:
        </v-col>
        <v-col>
          <v-chip
            v-if="bidLeader"
            large
          >
            <v-img
              class="profile-pic"
              :src="bidLeader.avatar_path"
              width="32"
              height="32"
            />
            {{ bidLeader.username }}
            {{ getHighestBidAmount() }} EUR
          </v-chip>
        </v-col>
      </v-row>
    </v-container>
  </AuctionTemplate>
</template>

<script>
import external_rules from '@/plugins/rules/rules.js'
import insertAuctionParticipant from '../plugins/insertAuctionParticipant'

export default {
  components: {
    AuctionTemplate: () => import('./AuctionTemplate')
  },
  props: {
    pAuction: {type: Object, default: null}
  },
  data () {
    return {
      bid: 0,
      rules: external_rules,
      scoped_rules: {
        commericalAmount: v => (!!v && v > (this.pAuction.auction_data[0].highest_bid_user_id ? this.highestBid : this.highestBid - 1 )) || 'Invalid amount'
      },
      showConfirmationDialog: false,

      response: {
        status: null,
        message: ''
      },
      isPaid: false,

      bidLeader: {},
      highestBid: 0
    }
  },
  computed: {
    checkUserParticipation: function (params) {
      // Check if user is eligible to participate based on participation date
      const now = new Date().getTime()
      const endDate = new Date(this.pAuction.auction_data[0].end_date).getTime()
      
      if (now > endDate) {
        return false
      }

      if (this.isUserIsLogged && !this.pAuction.participants.find(participant => 
        participant.id === this.$auth.user().id
      )) {
        return true
      }
      return false
    },
    isUserIsLogged: function () {
      if (Object.keys(this.$auth.user()).length !== 0) {
        return true
      }
      return false
    },
    correctAmount: function () {
      if (this.rules.types.integersOnly(this.bid) !== true) {
        return false
      }
      if (this.bidLeader != {}) {
        console.log(this.bid);
        console.log(this.highestBid);
        if (this.bid > this.highestBid) {
          return true
        }
      } else {
        if (this.bid >= this.highestBid) {
          return true
        }
      }
      return false
    },
  },
  watch: {
    pAuction: function () {
      this.getHighestBidUser(this.pAuction.auction_data[0].highest_bid_user_id)
    }
  },
  created () {
    this.highestBid = this.pAuction.auction_data[0].start_bid
    this.bid = this.highestBid
    this.getHighestBidUser(this.pAuction.auction_data[0].highest_bid_user_id)
  },
  methods: {
    closeDialog () {
      this.showConfirmationDialog = false
      this.$emit('updateAuction')
    },
    getHighestBidAmount () {
      return this.bidLeader.amount
    },
    getHighestBidUser (userId) {
      this.bidLeader = this.pAuction.participants.find(participant =>
        participant.id === userId
      )
      if (this.bidLeader) {
        this.highestBid = this.bidLeader.amount
        this.bid = parseInt(this.highestBid) + 1
      }
    },
    async submitBid () {
      const insertStatus = await insertAuctionParticipant(
        this.$auth.token(),
        this.$auth.user().id,
        this.pAuction.id,
        this.bid,
        this.response,
        this.isPaid
      )
      const config = { 
        headers: { 
          'Authorization': 'Bearer '+this.$auth.token(),
          'Content-Type': 'multipart/form-data' 
        }
      }

      const auctionBidData = new FormData()
      auctionBidData.append('auction_id', this.pAuction.id)
      auctionBidData.append('user_id', this.$auth.user().id)
      auctionBidData.append('amount', this.bid)

      this.$axios
        .post('auth/auction/checkBid', auctionBidData, config)
        .then (res => {
          console.log(res)
        })
        .catch ((err) => {
          console.log(err)
        })
      this.response.status = insertStatus.response.status
      this.response.message = insertStatus.response.message
      this.isPaid = insertStatus.isPaid
    }
  }
}
</script>

<style scoped>
  .bid-col {
    align-self: center;
  }
  .bid-container {
    padding: 0;
  }
  .bid-field {
    margin-top: 30px;
  }
  .bid-row {
    align-items: center;
    text-align: center;
  }
  .crucial-info-container {
    display: flex;
  }
  .profile-pic {
    border-radius: 50%;
    margin-right: 10px;
  }
</style>