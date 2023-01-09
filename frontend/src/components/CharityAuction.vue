<!-- Charity auction content for Slot -->
<template>
  <AuctionTemplate
    :pAuction="pAuction"
    @updateAuction="fetchUpdatedAuctions()"
  >
    <v-container>
      <v-row>
        <!-- CUrrent money amount visible in progressbar -->
        <v-progress-linear
          height="25"
          color="amber"
          :value="totalProgress"
        >
          <span
            class="font-weight-light progress-caption"
          >Progress</span>
          <v-spacer />
          <span
            class="font-weight-light progress-caption"
          >{{ totalAmount }} / {{ pAuction.auction_data[0].goal || 0 }} EUR</span>
        </v-progress-linear>
      </v-row>
    </v-container>
  </AuctionTemplate>
</template>

<script>
export default {
  components: {
    AuctionTemplate: () => import('./AuctionTemplate')
  },
  props: {
    pAuction: {type: Object, default: null}
  },
  computed: {
    totalAmount: function () {
      return this.$store.getters['charityTotal']
    },
    totalProgress: function () {
      return (this.totalAmount / this.pAuction.auction_data[0].goal) * 100
    }
  },
  methods: {
    fetchUpdatedAuctions () {
      this.$emit('updateAuction')
    }
  }
}
</script>

<style scoped>
  .progress-caption {
    margin-left: 10px;
    margin-right: 10px;
  }
</style>