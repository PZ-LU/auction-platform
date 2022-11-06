<template>
  <div>
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
                    <v-list-item-title
                      style="display: contents;"
                    >
                      {{ category.label }}
                      <v-spacer/>
                      <v-btn
                        v-if="$auth.user().id && ($auth.user().role == 'admin' || $auth.user().role == 'super_admin')"
                        icon
                        color="error"
                        style="margin-left: auto;"
                        @click="showDeleteCategoryDialog = true"
                      >
                        <v-icon>mdi-delete-forever-outline</v-icon>
                      </v-btn>
                    </v-list-item-title>
                  </v-list-item-content>
                </v-list-item>
              </v-list-item-group>
            </v-list>
            <v-dialog
              persistent
              v-if="$auth.user().id && ($auth.user().role == 'admin' || $auth.user().role == 'super_admin')"
              v-model="addForumCategoryDialog"
            >
              <template
                #activator="{ on }"
              >
                <v-btn
                  text
                  block
                  v-on="on"
                >
                  New Category
                </v-btn>
              </template>

              <v-form
                ref="catForm"
                v-if="!response"
              >
                <AddForumCategory
                  @closeAddForumCategoryDialog="closeAddForumCategoryDialog()"
                />
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
                    @click="closeAddForumCategoryDialog(true)"
                  >
                    Ok
                  </v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog>
          </v-card>
        </v-col>

        <v-col
          v-if="selectedCategory"
        >
          <v-row
            v-if="$auth.user().id"
          >
            <v-dialog
              persistent
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
                      @click="closeTopicDialog()"
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
                    @click="closeTopicDialog(true)"
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
            Community Forum
          </h1>
          <v-divider />
        </v-col>
      </v-row>
    </v-container>
    <!-- hidden delete category dialog -->
    <v-dialog
      persistent
      v-model="showDeleteCategoryDialog"
    >
      <v-card>
        <v-card-title>
          Attention!
        </v-card-title>
        <v-card-text>
          Would You really like to delete this category with all its topics and comments?
        </v-card-text>
        <v-card-actions>
          <v-btn
            width="50%"
            text
            color="primary"
            @click="deleteCategory()"
          >
            Confirm
          </v-btn>
          <v-btn
            width="50%"
            text
            color="error"
            @click="showDeleteCategoryDialog = false"
          >
            Cancel
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import external_rules from '../plugins/rules/rules'

export default {
  components: {
    TopicsDisplay: () => import("../components/TopicsDisplay"),
    AddForumCategory: () => import("../components/AddForumCategory"),
},
  data () {
    return {
      categories: [],
      categorySection: null,
      selectedCategory: null,

      addTopicDialog: false,
      addForumCategoryDialog: false,
      showDeleteCategoryDialog: false,

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
    closeTopicDialog (afterSubmit = false) {
      this.body = null
      this.title = null
      if (!afterSubmit) {
        this.$refs.topForm.reset()
      }
      this.addTopicDialog = false
      this.response = null
    },
    closeAddForumCategoryDialog () {
      this.addForumCategoryDialog = false
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
    },
    deleteCategory () {
      const config = { 
        headers: { 
          'Authorization': 'Bearer '+this.$auth.token(),
        }
      }
      this.$axios
        .post('auth/forum/category/delete', { category: this.selectedCategory.id}, config)
        .then (res => {
          this.showDeleteCategoryDialog = false
          this.fetchCategories()
        })
        .catch ((err) => {
          console.log(err)
        })
    },
  }
}
</script>
