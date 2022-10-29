<template>
  <v-container>
    <v-row>
      <v-col
        cols="3"
      >
        <v-card
          outlined
        >
          <v-card-title>
            Categories
          </v-card-title>
          <v-list>
            <v-list-item-group
              v-model="categorySection"
            >
              <v-list-item
                v-for="category in categories"
                :key="category.id"
                @click="selectedCategory = category"
              >
                <v-list-item-content>
                  <v-list-item-title>
                    {{ category.label }}
                  </v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </v-list-item-group>
          </v-list>
        </v-card>
      </v-col>

      <v-col
        v-if="selectedCategory"
      >
        <v-row
          v-if="$auth.user().id"
        >
          <v-dialog
            v-model="addTopicDialog"
          >
            <template
              #activator="{ on }"
            >
              <v-btn
                text
                block
                v-on="on"
              >
                Create Topic
              </v-btn>
            </template>

            <v-form
              ref="topForm"
              v-if="!response"
            >
              <v-card>
                <v-card-title>
                  Add Topic
                </v-card-title>
                <v-card-text>
                  <v-text-field
                    v-model="title"
                    :rules="[rules.required]"
                    :counter="32"
                    placeholder="Topic Title ..."
                    label="Title"
                    outlined
                  />
                  <v-textarea
                    v-model="body"
                    :rules="[rules.required]"
                    :counter="600"
                    rows="3"
                    placeholder="Topic Description ..."
                    label="Description"
                    outlined
                  />
                </v-card-text>
                <v-card-actions>
                  <v-btn
                    color="primary"
                    text
                    width="50%"
                    @click="submit()"
                  >
                    Submit
                  </v-btn>
                  <v-btn
                    color="error"
                    text
                    width="50%"
                    @click="closeDialog()"
                  >
                    Cancel
                  </v-btn>
                </v-card-actions>
              </v-card>
            </v-form>

            <v-card
              v-if="response"
            >
              <v-card-title>
                {{ response }}
              </v-card-title>
              <v-card-actions>
                <v-btn
                  color="success"
                  text
                  block
                  @click="closeDialog(true)"
                >
                  Ok
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-row>
        <v-divider />
        <v-row
          style="width: 100%; background-color: white;"
        >
          <TopicsDisplay
            style="width: 100%;"
            :pCategory="selectedCategory"
          />
        </v-row>
      </v-col>

      <v-col
        v-if="!selectedCategory"
      >
        <h1
          class="display-3 font-weight-light"
        >
          Auction Forum
        </h1>
        <v-divider />
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import axios from 'axios'
import external_rules from '../plugins/rules/rules'

export default {
  components: {
    TopicsDisplay: () => import('../components/TopicsDisplay')
  },
  data () {
    return {
      categories: [],
      categorySection: null,
      selectedCategory: null,

      addTopicDialog: false,
      rules: external_rules,

      title: null,
      body: null,
      response: null
    }
  },
  created () {
    this.fetchCategories()
  },
  methods: {
    closeDialog (afterSubmit = false) {
      this.body = null
      this.title = null
      if (!afterSubmit) {
        this.$refs.topForm.reset()
      }
      this.addTopicDialog = false
      this.response = null
    },
    fetchCategories () {
      this.$axios
        .get('forum/categories')
        .then(res => {
          const {data:{data}} = res
          if (data && !data.length){
            this.categories = [data]
          } else {
            this.categories = data
          }
        })
        .catch(err => {
          console.log(err)
        })
    },
    submit () {
      if (this.$refs.topForm.validate()) {
        const topicData = new FormData()
        topicData.append('user_id', this.$auth.user().id)
        topicData.append('title', this.title)
        topicData.append('body', this.body)
        topicData.append('category_id', this.selectedCategory.id)

        const config = { 
          headers: { 
            'Authorization': 'Bearer '+this.$auth.token(),
            'Content-Type': 'multipart/form-data' 
          }
        }
        this.$axios
          .post('auth/forum/topic/add', topicData, config)
          .then (res => {
            this.response = 'Your topic will be created!'
            this.fetchCategories()
          })
          .catch ((err) => {
            console.log(err)
            this.response = 'Something went wrong!'
          })
      }
    }
  }
}
</script>
