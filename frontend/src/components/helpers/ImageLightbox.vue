<!-- Image lightbox component, used everywhere -->
<template>
  <div
    :style="`max-height: ${pMaxHeight}; overflow: hidden;`"
  >
    <v-dialog v-model="lightboxDialog">
      <template #activator="{ on }">
        <v-img
          v-on="on"
          :src="src"
        />
      </template>

      <v-container>
        <v-img
          :src="src"
        />
      </v-container>
    </v-dialog>
  </div>
</template>

<script>
export default {
  name: 'ImageLightbox',
  props: {
    src: {type: String, default: null},
    pMaxHeight: {type: String, default: ''}
  },
  data () {
    return {
      lightboxDialog: false
    }
  }
}
</script>
