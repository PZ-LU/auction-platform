<template>
  <v-container
    class="contains"
  >
    <ImageLightbox
      :src="pAuction.auction_object.preview_image"
      class="img"
    />

    <v-divider
      vertical
    />

    <h2
      class="title"
    >
      {{ pAuction.auction_object.name }}
    </h2>

    <v-divider
      vertical
    />

    <v-container>
      <v-row
        justify="center"
      >
        Started At: {{ new Date(pAuction.started_at).toLocaleDateString() }}
      </v-row>
      <v-row
        v-if="pAuction.finished_at"
        justify="center"
      >
        Finished At: {{ new Date(pAuction.finished_at).toLocaleDateString() }}
      </v-row>
    </v-container>

    <v-divider
      vertical
    />

    <!-- charity block -->
    <v-container
      v-if="isCharity"
    >
      <v-row
        class="charity-row"
      >
        <v-col
          class="participant-col"
        >
          Goal
          <v-chip>
            {{ pAuction.auction_data[0].goal }} EUR
          </v-chip>
        </v-col>
      </v-row>
    </v-container>
    <!-- /charity block -->

    <!-- commercial block -->
    <v-container
      class="commercial-container"
      v-if="isCommercial"
    >
      <v-row>
        <v-col
          v-if="winner"
          cols="3"
          class="participant-col"
        >
          <div>
            <v-img
              class="profile-pic"
              :src="winner.avatar_path"
              width="48"
              height="48"
            />
          </div>
        </v-col>
        <v-col
          v-if="winner"
          cols="4"
          class="participant-col"
        >
          {{ winner.username }}
        </v-col>
        <v-col
          class="participant-col"
        >
          <span>Starting bid</span>
          <v-row>
            <v-chip>
              {{ pAuction.auction_data[0].start_bid }} EUR
            </v-chip>
          </v-row>
          <span
            v-if="winner"
          >Leading bid</span>
          <v-row
            v-if="winner"
          >
            <v-chip
              color="#9CCC65"
            >
              {{ winner.amount }} EUR
            </v-chip>
          </v-row>
        </v-col>
      </v-row>
    </v-container>
    <!-- /commercial block -->
  </v-container>
</template>

<script>
export default {
  components: {
    ImageLightbox: () => import('./helpers/ImageLightbox')
  },
  props: {
    pAuction: {type: Object, default: null},
    pType: {type: String, default: null},
    pMin: {type: Boolean, default: false}
  },
  data () {
    return {
      isCharity: false,
      isCommercial: false,

      // commerical
      winner: null
    }
  },
  created () {
    if (this.pType === 'charity') {
      this.isCharity = true
    } else if (this.pType === 'commercial') {
      this.isCommercial = true
    }

    if (this.isCommercial) {
      this.getCommercialWinner()
    }
  },
  methods: {
    // commerical
    getCommercialWinner () {
      const auctionData = this.pAuction.auction_data
      this.winner = this.pAuction.participants.find(participant => participant.id === auctionData[0].highest_bid_user_id)
    }
  }
}
</script>

<style scoped>
  .charity-row {
    align-items: center;
    text-align: center;
  }
  .commercial-container {
    margin-left: 20px;
  }
  .contains {
    max-height: 130px;
    align-items: center;
    display: inline-flex;
  }
  .img {
    max-width: 200px;
    max-height: 200px;
    margin-right: 20px;
  }
  .participant-col {
    align-self: center;
    justify-self: center;
  }
  .profile-pic {
    border-radius: 50%;
  }
  .title {
    margin-left: 20px;
    margin-right: 20px;
    width: 100%;
  }
</style>