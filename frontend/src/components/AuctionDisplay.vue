<template>
  <v-container
    class="contains"
  >
    <ImageLightbox
      :src="pAuction.auction_object.preview_image"
      :class="{ img: !pMin, 'img-min': pMin }"
    />

    <v-divider
      vertical
    />

    <h4
      v-if="pMin"
      class="padded-col"
    >
      {{ pAuction.auction_object.name }}
    </h4>

    <h2
      v-else
      class="title"
    >
      {{ pAuction.auction_object.name }}
    </h2>

    <v-divider
      vertical
    />

    <v-container
      :class="{ 'padded-col': pMin }"
    >
      <v-row
        justify="center"
        :class="{ 'font-min': pMin }"
      >
        Started: {{ new Date(pAuction.started_at).toLocaleDateString() }}
      </v-row>
      <v-row
        v-if="pAuction.finished_at"
        justify="center"
        :class="{ 'font-min': pMin }"
      >
        Finished: {{ new Date(pAuction.finished_at).toLocaleDateString() }}
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
          :class="{ 'font-min': pMin }"
        >
          Goal
          <v-chip
            :class="{ 'font-min': pMin }"
          >
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
      <v-row
        :class="{ 'min-row': pMin }"
      >
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
          :class="{ 'font-min': pMin }"
        >
          {{ winner.username }}
        </v-col>
        <v-col
          class="participant-col"
        >
          <span
            :class="{ 'font-min': pMin }"
          >Starting bid</span>
          <v-row>
            <v-chip>
              {{ pAuction.auction_data[0].start_bid }} EUR
            </v-chip>
          </v-row>
          <span
            v-if="winner"
            :class="{ 'font-min': pMin }"
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
  .font-min {
    font-size: small;
  }
  .img {
    max-width: 130px;
    max-height: 130px;
    margin-right: 20px;
  }
  .img-min {
    max-width: 50px;
    max-height: 50px;
    margin-right: 20px;
  }
  .min-row {
    width: max-content;
  }
  .padded-col {
    padding-left: 15px;
    padding-right: 15px;
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