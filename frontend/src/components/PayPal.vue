<template>
  <div>
    <div v-if="!isPaid">
      <div
        style="float: right;"
      >
        <v-btn
          icon
          @click="closePayPal()"
        >
          <v-icon>mdi-close</v-icon>
        </v-btn>
      </div>
      <div
        class="description"
      >
        <h1>Auction participation amount</h1>
        <h3>for</h3>
        <h2>{{ product.description }}</h2>

        <div
          class="price"
        >
          <h1>{{ product.price }} EUR</h1>
        </div>
      </div>
    </div>

    <div v-if="isPaid">
      <h1>{{ response.status }}!</h1>
      <h1>{{ response.message }}</h1>
      <v-btn
        block
        depressed
        @click="closePayPal()"
      >
        Ok
      </v-btn>
    </div>

    <div
      v-if="!isPaid"
    >
      <div
        class="loading-container"
        v-if="!isLoaded"
      >
        <v-progress-circular
          class="loading"
          indeterminate
          color="white"
        />
      </div>
      <div
        class="paypal-container"
        ref="paypal"
      />
      <div
        v-if="isError"
      >
        <span
          class="caption"
          style="color: red;"
        >{{ response.message }}!</span>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import insertAuctionParticipant from '../plugins/insertAuctionParticipant'

export default {
  props: {
    pAmount: { type: Number, default: 0 },
    pProduct: { type: Object, default: null },
  },
  data () {
    return {
      isLoaded: false,
      isPaid: false,
      product: {
        price: 0,
        description: ""
      },

      response: {
        status: null,
        message: ''
      },
      isError: false
    };
  },
  created () {
    this.product.price = this.pAmount
    this.product.description = this.pProduct.description
  },
  mounted () {
    const script = document.createElement("script")
    script.src = "https://www.paypal.com/sdk/js?currency=EUR&client-id=AfmV7MYZEqRm0p_NtJREd41YtDYyRm5LVkxeXnnQW1kRs9YK2hCG3ItNUzecp173afMRFOZoJWq9jhMu"
    script.addEventListener("load", this.loadPayPalComponent)
    document.body.appendChild(script);
  },
  methods: {
    closePayPal () {
      this.$emit('closeModal')
    },
    loadPayPalComponent: function() {
      window.paypal.Buttons({
        style: {
          size: 'small',
          color: 'gold',
          shape: 'pill',
          label: 'checkout',
        },
        createOrder: (data, actions) => {
          this.isLoaded = false
          return actions.order.create({
            purchase_units: [
              {
                description: this.product.description,
                amount: {
                  currency_code: "EUR",
                  value: this.product.price
                }
              }
            ]
          })
        },
        onApprove: async (data, actions) => {
          const order = await actions.order.capture();
          if (order.status === "COMPLETED") {
            this.insertAuctionParticipant(this.product.price)
          }
        },
        onError: err => {
          this.response.status = 'Error'
          this.response.message = 'Error occurred on this transaction'
          this.isLoaded = true
          this.isError = true
        },
        onCancel: (data, actions) => {
          this.isLoaded = true
        }
      })
      .render(this.$refs.paypal)
        .then(() => {
          this.isLoaded = true
        })
    },
    async insertAuctionParticipant (price) {
      const insertStatus = await insertAuctionParticipant(
        this.$auth.token(),
        this.$auth.user().id,
        this.pProduct.auctionId,
        price,
        this.response,
        this.isPaid
      )
      this.response.status = insertStatus.response.status
      this.response.message = insertStatus.response.message
      this.isPaid = insertStatus.isPaid
      this.isLoaded = true
    }
  }
}
</script>

<style scoped>
  .description {
    text-align: center;
  }
  .loading {
    top: 50%;
  }
  .loading-container {
    position: absolute;
    background-color:rgba(0, 0, 0, 0.561);
    top: 0;
    left: 0;
    width: 100%;
    text-align-last: center;
    height: 100%;
    z-index: 90;
  }
  .paypal-container {
    position: relative;
    z-index: 0;
  }
  .price {
    border: 1px solid lightgrey;
    border-radius: 50px;
  }
</style>