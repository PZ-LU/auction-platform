<!-- Displays list of available topics -->
<template>
  <v-card
    v-if="topics.length && pCategory.id"
    outlined
  >
    <div
      v-for="topic in topics"
      :key="topic.id"
    >
      <v-card-title>
        {{ topic.title }}
        <v-spacer />
        <span
          v-if="topic.author_data.username"
          class="subtitle-1"
        >
          Author: {{ topic.author_data.username }}
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
                depressed
                class="expand-btn"
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
        </span>
      </v-card-title>
      <v-divider /> 
    </div>
  </v-card>
</template>

<script>
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
  created() {
    this.fetchTopics()
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
      const path = `forum/topics?category_id=${this.pCategory.id}`
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

<style>
  .expand-btn {
    margin-left: 10px;
  }
</style>
