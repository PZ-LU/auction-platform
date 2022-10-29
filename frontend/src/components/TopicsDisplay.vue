<template>
  <v-card
    v-if="topics.length && pCategory.id"
    outlined
  >
    <v-card-title
      v-for="topic in topics"
      :key="topic.id"
    >
      {{ topic.title }}
      <v-spacer />
      <span
        v-if="topic.author_data.username"
        class="subtitle-1"
      >
        Author: {{ topic.author_data.username }}
      </span>
      <v-dialog
        v-model="topic.dialog"
        persistent
        fullscreen
      >
        <template
          #activator="{ on }"
        >
          <v-btn
            v-on="on"
            @click="topic.dialog = true"
            text
          >
            Expand
          </v-btn>
        </template>

        <div
          style="background-color: white; height: 100%;"
        >
          <Topic
            :pTopic="topic"
            @closeDialog="closeDialog()"
          />
        </div>
      </v-dialog>
    </v-card-title>
  </v-card>
</template>

<script>
import axios from 'axios'

export default {
  components: {
    Topic: () => import('./Topic.vue')
  },
  props: {
    pCategory: {type: Object, default: null}
  },
  data () {
    return {
      topics: []
    }
  },
  watch: {
    pCategory: function () {
      this.fetchTopics()
    }
  },
  methods: {
    closeDialog () {
      this.topics.map(topic => {
        topic.dialog = false
      })
      this.fetchTopics()
    },
    fetchTopics () {
      this.topics = []
      const path = `forum/topics?category=${this.pCategory.id}`
      this.$axios
        .get(path)
        .then(res => {
          const {data:{data}} = res
          if (data) {
            this.topics = data
          }
          for (const topic of this.topics) {
            topic.dialog = false
          }
        })
        .catch(err => {
          console.log(err)
        })
    },
  }
}
</script>
