<!-- Dialog to delete offer -->
<template>
  <v-dialog
    persistent
    v-model="dialog"
  >
    <template
      #activator="{ on }"
    >
      <v-btn
        v-if="pOffer.author_id === $auth.user().id || $auth.user().role !== 'user'"
        text
        color="error"
        v-on="on"
      >
        <!-- Make sure proper label is displayed to unprivileged user -->
        {{ $auth.user().role === "user" ? 'Delete' : 'Archive' }}
      </v-btn>
    </template>

    <v-card>
      <v-card-title>
        Attention!
      </v-card-title>
      <v-card-text>
        Would You really like to {{ $auth.user().role === 'user' ? 'delete' : 'archive' }} this offer? <br>
        {{ pOffer.title }}
      </v-card-text>

      <v-card-actions>
        <v-btn
          text
          width="50%"
          color="error"
          @click="deleteOffer()"
        >
          {{ $auth.user().role === "user" ? 'Delete' : 'Archive' }}
        </v-btn>
        <v-btn
          text
          width="50%"
          @click="dialog = false"
        >
          Cancel
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  props: {
    pOffer: {type: Object, default: null}
  },
  data () {
    return {
      dialog: false
    }
  },
  methods: {
    deleteOffer () {
      this.dialog = false
      this.$emit('delete')
    }
  }
}
</script>

<style scoped>
  .dialog {
    position: relative;
  }
  .modal {
    position: absolute;
    background-color: rgba(red, green, blue, 1);
    width: 100%;
    height: 100%;
  }
</style>