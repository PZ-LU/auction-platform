<!-- Template to participate in any kind of auction -->
<template>
  <v-card
    outlined
  >
    <v-card-title>
      <ImageLightbox
        class="lightbox"
        :src="imgSrc()"
      />
      <h2
        class="auction-object-title font-weight-light"
      >
        {{ pAuction.auction_object.name }}
      </h2>
      <div
        ref="description_wrapper"
        class="description-wrapper description-wrapper-hidden"
        style="width: 100%;"
        v-if="pAuction.auction_object.body"
      >
        <!-- Description text -->
        <v-btn
          depressed
          text
          class="description-btn"
          @click="resizeDescription()"
        >
          {{ descAction }}
        </v-btn>
        <div
          class="description-hidden"
          ref="description"
        >
          <p
            class="description-text"
          >
            {{ pAuction.auction_object.body }}
          </p>
        </div>
      </div>
    </v-card-title>
    
    <v-card-text>
      <!-- Slot for custom content -->
      <slot />
      <v-container
        v-if="!isUserIsLogged"
      >
        <h3>You need to Log In to be able to participate</h3>
      </v-container>
      <v-container>
        <div
          v-for="participant in sortedParticipants"
          :key="participant.id"
        >
          <v-divider />
          <v-container
            class="participant-row"
          >
            <v-row
              :class="{ 'hightlight-row' : participant.id === $auth.user().id }"
            >
              <v-col
                cols="2"
              >
                <div>
                  <v-img
                    class="profile-pic"
                    :src="participant.avatar_path"
                    width="32"
                    height="32"
                  />
                </div>
              </v-col>
              <v-col
                cols="5"
                class="participant-col"
              >
                {{ participant.username }}
              </v-col>
              <v-col
                class="participant-col"
              >
                <v-chip>
                  {{ participant.amount }} EUR
                </v-chip>
              </v-col>
            </v-row>
          </v-container>
        </div>
      </v-container>
    </v-card-text>
    <v-card-actions
      v-if="checkPayPal"
    >
      <v-container
        v-if="canUserParticipate"
      >
        <v-text-field
          v-model="amount"
          label="Amount (EUR)"
          outlined
          clearable
          :rules="[scoped_rules.charityAmount, rules.types.integersOnly]"
        />
        <v-btn
          @click="showPayPalDialog = true"
          block
          depressed
          color="#ffc439"
          :disabled="!correctAmount"
        >
          Participate
        </v-btn>
        <ModalDialog
          v-if="showPayPalDialog"
          class="modal"
        >
          <v-card>
            <v-container>
              <PayPal
                class="dialog"
                :pAmount="Number(amount)"
                :pProduct="auctionProduct"
                @closeModal="closePayPalDialog()"
              />
            </v-container>
          </v-card>
        </ModalDialog>
      </v-container>
    </v-card-actions>
  </v-card>
</template>

<script>
import external_rules from '@/plugins/rules/rules.js'

export default {
  components: {
    ImageLightbox: () => import('./helpers/ImageLightbox'),
    ModalDialog: () => import('./helpers/ModalDialog'),
    PayPal: () => import('./PayPal')
  },
  props: {
    pAuction: {type: Object, default: null},
    pUsePayPal: {type: Boolean, default: true}
  },
  data () {
    return {
      amount: 100.00,
      total_amount: 0,
      showPayPalDialog: false,
      auctionProduct: {},
      descAction: "Show More",
      descActionBtnPress: true,

      rules: external_rules,
      scoped_rules: {
        charityAmount: v => (!!v && v > 0 && v <= (this.pAuction.auction_data[0].goal - this.total_amount)) || 'Invalid amount'
      },

      sortedParticipants: null
    }
  },
  computed: {
    auctionParticipants: function () {
      return this.pAuction.participants
    },
    canUserParticipate: function () {
      if (this.auctionParticipants.find(participant => participant.id === this.$auth.user().id)) {
        return false
      }
      return true
    },
    checkPayPal: function () {
      if (Object.keys(this.$auth.user()).length && this.pUsePayPal) {
        return true
      }
      return false
    },
    correctAmount: function () {
      if (this.rules.types.integersOnly(this.amount) !== true) {
        return false
      }
      if (this.total_amount >= this.pAuction.auction_data[0].goal) {
        return false
      }
      if (this.amount > 0 && this.amount <= (this.pAuction.auction_data[0].goal - this.total_amount)) {
        return true
      }
      return false
    },
    isUserIsLogged: function () {
      if (Object.keys(this.$auth.user()).length !== 0) {
        return true
      }
      return false
    }
  },
  watch: {
    pAuction: {
      deep: true,
      handler: function () {
        this.getTotalAmount()
        this.setTotalAmount()
      }
    }
  },
  created () {
    this.getTotalAmount()
    this.setTotalAmount()

    this.auctionProduct = {
      auctionId: this.pAuction.id,
      description: this.pAuction.auction_object.name
    }

    this.sortParticipants()
  },
  methods: {
    closePayPalDialog () {
      this.showPayPalDialog = false
      this.$emit('updateAuction')
    },
    getTotalAmount () {
      this.total_amount = 0
      this.auctionParticipants.map(participant => {
        this.total_amount += parseInt(participant.amount)
      })
    },
    imgSrc () {
      if (this.pAuction) {
        return this.pAuction.auction_object.preview_image
      }
      return ''
    },
    setTotalAmount () {
      let operation
      switch (this.pAuction.type) {
        case 'charity':
          operation = 'setCharityTotal'
          break
      
        case 'commercial':
          operation = 'setCommercialTotal'
          break
      }
      this.$store.commit(operation, this.total_amount)
    },
    sortParticipants () {
      this.sortedParticipants = this.auctionParticipants.sort((a, b) => (b.amount - a.amount))
    },
    resizeDescription () {
      if (this.descActionBtnPress) {
        this.descActionBtnPress = false;
        this.descAction = "Show Less"
        this.$refs.description.classList.remove("description-hidden");
        this.$refs.description_wrapper.classList.remove("description-wrapper-hidden");
        this.$refs.description.classList.add("description");
      } else {
        this.descActionBtnPress = true;
        this.descAction = "Show More"
        this.$refs.description.classList.add("description-hidden");
        this.$refs.description_wrapper.classList.add("description-wrapper-hidden");
        this.$refs.description.classList.remove("description");
      }
    }
  }
}
</script>

<style scoped>
  .auction-object-title {
    margin-top: 10px;
  }
  .description {
    width: 100%;
    overflow:auto;
    max-height: 15em;
  }
  .description-btn {
    width: 100%;
    background-color: transparent;
  }
  .description-hidden {
    width: inherit;
    max-height: 50px;
    overflow:hidden;
  }
  .description-text {
    font-weight: 300;
    font-size: medium;
    white-space: pre-wrap;
  }
  .description-wrapper {
    margin-top: 15px;
    border: 1px solid lightgrey;
    border-radius: 10px;
    padding: 5px;
  }
  .description-wrapper-hidden {
    background: linear-gradient(0deg, rgb(202, 202, 202) 0%, rgba(255,255,255,0) 100%); 
  }
  .hightlight-row {
    background-color: lightgrey;
  }
  .lightbox {
    width: 100%;
  }
  .participant-col {
    align-self: center;
  }
  .participant-row {
    padding: 0;
  }
  .profile-pic {
    border-radius: 50%;
  }
</style>