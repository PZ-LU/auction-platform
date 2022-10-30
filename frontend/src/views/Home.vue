<template>
  <v-container>
    <h1
      class="display-1 font-weight-light"
    >
      Welcome to the _
    </h1>
    <h1
      class="display-4 font-weight-light"
    >
      Auction Platform
    </h1>
    <v-divider />
    <v-container
      class="slogan-container"
    >
      <div
        class="display-3"
        style="border-bottom: 1px solid;"
      >
        Fast and Reliable.
      </div>
    </v-container>
    <h1
      class="display-5 font-weight-light"
    >
      Latest auction entries
    </h1>
    <AuctionFeed />
  </v-container>
</template>

<script>

export default {
  components: {
    AuctionFeed: () => import('../components/AuctionFeed')
  }
}
</script>

<style scoped>
  .slogan-container {
    width: 100vw;
    height: 50vh;
    text-align: center;
    display: table-cell;
    vertical-align: middle;
  }
  .subtitling {
    text-align: center;
  }
</style>
