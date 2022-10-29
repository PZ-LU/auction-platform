<template>
  <v-card
    outlined
  >
    <v-card-title>
      {{ pTopic.title }}
      <v-spacer />
      <v-dialog
        v-if="$auth.user().id && ($auth.user().id === pTopic.author_data.id || $auth.user().role !== 'user')"
        v-model="showDeleteDialog"
      >
        <template
          #activator="{ on }"
        >
          <v-btn
            icon
            v-on="on"
            color="error"
            @click="showDeleteDialog = true"
          >
            <v-icon>mdi-delete-forever-outline</v-icon>
          </v-btn>
        </template>

        <v-card>
          <v-card-title>
            Attention!
          </v-card-title>
          <v-card-text>
            Would You really like to delete this topic with all its comments?
          </v-card-text>
          <v-card-actions>
            <v-btn
              width="50%"
              text
              color="primary"
              @click="deleteTopic()"
            >
              Confirm
            </v-btn>
            <v-btn
              width="50%"
              text
              color="error"
              @click="showDeleteDialog = false"
            >
              Cancel
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>

      <v-btn
        icon
        @click="fetchComments()"
      >
        <v-icon>mdi-autorenew</v-icon>
      </v-btn>
      <v-btn
        icon
        @click="closeTopic()"
      >
        <v-icon>mdi-close</v-icon>
      </v-btn>
    </v-card-title>
    <v-card-subtitle>
      Added by: {{ pTopic.author_data.username }}
      <v-spacer />
      Added on: {{ pTopic.created_at }}
    </v-card-subtitle>
    <v-card-text
      class="font-weight-regular"
    >
      {{ pTopic.body }}
    </v-card-text>

    <v-card-text>
      <v-container
        v-if="$auth.user().id"
      >
        <v-form
          ref="comForm"
        >
          <v-textarea
            v-model="body"
            :rules="[rules.required]"
            :counter="600"
            rows="4"
            placeholder="Comment ..."
            label="Comment"
            outlined
          />
          <v-btn
            text
            @click="submitComment()"
          >
            Submit
          </v-btn>
        </v-form>
      </v-container>
      <v-container
        style="max-height: 700px; overflow: auto;"
        v-if="comments.length"
      >
        <v-card
          outlined
        >
          <v-card-text>
            <v-container>
              <v-row
                v-for="comment in comments"
                :key="comment.id"
              >
                <v-row>
                  <v-col
                    cols="3"
                  >
                    <v-row
                      class="profile-col"
                    >
                      <v-img
                        class="profile-pic"
                        width="32"
                        height="32"
                        :src="comment.author_data.avatar_path"
                      />
                    </v-row>
                    <v-row
                      class="profile-col"
                    >
                      {{ comment.author_data.username }}
                    </v-row>
                  </v-col>

                  <v-col>
                    {{ comment.body }}
                  </v-col>
                </v-row>
                
                <v-row>
                  Added on: {{ comment.created_at }}
                </v-row>
                <v-dialog
                  v-if="$auth.user().id && ($auth.user().id === comment.author_id || $auth.user().role !== 'user')"
                  v-model="comment.dialog"
                >
                  <template
                    #activator="{ on }"
                  >
                    <v-btn
                      icon
                      v-on="on"
                      color="error"
                      @click="comment.dialog = true"
                    >
                      <v-icon>mdi-delete-forever-outline</v-icon>
                    </v-btn>
                  </template>

                  <v-card>
                    <v-card-title>
                      Attention!
                    </v-card-title>
                    <v-card-text>
                      Would You really like to delete this comment?
                    </v-card-text>
                    <v-card-actions>
                      <v-btn
                        width="50%"
                        text
                        color="primary"
                        @click="deleteComment(comment.id)"
                      >
                        Confirm
                      </v-btn>
                      <v-btn
                        width="50%"
                        text
                        color="error"
                        @click="comment.dialog = false"
                      >
                        Cancel
                      </v-btn>
                    </v-card-actions>
                  </v-card>
                </v-dialog>
              </v-row>
            </v-container>
          </v-card-text>
        </v-card>
      </v-container>
    </v-card-text>
  </v-card>
</template>

<script>
import axios from 'axios'
import external_rules from '../plugins/rules/rules'

export default {
  props: {
    pTopic: {type: Object, default: null}
  },
  data () {
    return {
      comments: [],
      rules: external_rules,
      body: null,
      showDeleteDialog: false
    }
  },
  created () {
    this.fetchComments()
  },
  methods: {
    closeTopic () {
      this.$emit('closeDialog')
    },
    deleteComment (commentId) {
      const config = { 
        headers: { 
          'Authorization': 'Bearer '+this.$auth.token(),
        }
      }
      this.$axios
        .post('auth/forum/comment/delete', { comment: commentId}, config)
        .then (res => {
          this.showDeleteCommentDialog = false
          this.fetchComments()
        })
        .catch ((err) => {
          console.log(err)
        })
    },
    deleteTopic () {
      const config = { 
        headers: { 
          'Authorization': 'Bearer '+this.$auth.token(),
        }
      }
      this.$axios
        .post('auth/forum/topic/delete', { topic: this.pTopic.id}, config)
        .then (res => {
          this.closeTopic()
        })
        .catch ((err) => {
          console.log(err)
        })
    },
    fetchComments () {
      this.comments = []
      const path = `forum/comments?topic=${this.pTopic.id}`
      this.$axios
        .get(path)
        .then(res => {
          const {data:{data}} = res
          if (data){
            this.comments = data
          }
          for (const comment of this.comments) {
            comment.dialog = false
          }
        })
        .catch(err => {
          console.log(err)
        })
    },
    submitComment () {
      if (this.$refs.comForm.validate()) {
        const commentData = new FormData()
        commentData.append('user_id', this.$auth.user().id)
        commentData.append('body', this.body)
        commentData.append('topic_id', this.pTopic.id)

        const config = { 
          headers: { 
            'Authorization': 'Bearer '+this.$auth.token(),
            'Content-Type': 'multipart/form-data' 
          }
        }
        this.$axios
          .post('auth/forum/comment/add', commentData, config)
          .then (res => {
            this.$refs.comForm.reset()
            this.fetchComments()
          })
          .catch ((err) => {
            console.log(err)
          })
      }
    }
  }
}
</script>

<style scoped>
  .profile-col {
    place-content: center;
  }
  .profile-pic {
    border-radius: 50%;
    max-width: 32px;
  }
</style>