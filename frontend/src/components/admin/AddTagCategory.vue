<template>
    <div>
      <v-form 
        ref="catForm"
        v-if="!response.status"
      >
        <v-card
          style="overflow: hidden;"
          max-width="100%"
        >
          <v-card-title>
            <h1
              class="headline font-weight-regular"
            >
              Add new tag category
            </h1> 
            <v-spacer />
            <v-btn
              icon
              @click="closeDialog()"
            >
              <v-icon>mdi-close</v-icon>
            </v-btn>
          </v-card-title>
          <v-card-text>
            <v-text-field
              v-model="title"
              :rules="[rules.required]"
              :counter="32"
              placeholder="Category ..."
              label="Tag Category Name"
              outlined
            />
          </v-card-text>
          <v-card-actions class="pb-0">
            <v-container class="pa-0">              
              <v-divider />
              <v-row class="pa-0">
                <!-- escape buttons -->
                <v-btn 
                  style="border-radius: 0 0 0 5px;"
                  text
                  color="primary"
                  d-inline-block
                  width="50%"
                  height="50px"
                  @click="submit()"
                >
                  Submit
                </v-btn>
                <v-spacer />
                <v-btn 
                  style="border-radius: 0 0 5px 0;"
                  text
                  d-inline-block
                  width="50%"
                  height="50px"
                  @click="closeDialog()"
                >
                  Cancel
                </v-btn>
              </v-row>
            </v-container>
          </v-card-actions>
        </v-card>
      </v-form>
      <v-card
        v-if="response.status"
      >
        <v-card-title>
          {{ response.status }}
        </v-card-title>
        <v-card-text>
          {{ response.message }}
        </v-card-text>
        <v-btn
          class="dismiss-btn"
          depressed
          block
          @click="closeDialog(true)"
        >
          Ok
        </v-btn>
      </v-card>
    </div>
  </template>
  
  <script>
  import external_rules from '@/plugins/rules/rules.js'
  
  export default {
    props: {
    },
    data() {
      return {
        title: null,
  
        rules: external_rules,

        response: {
          status: null,
          message: null
        }
      }
    },
    beforeDestroy () {
      this.closeDialog()
    },
    methods: {
      closeDialog (afterSubmit = false) {
        this.title = null
        this.response = {
          status: null,
          message: null
        }
        if (!afterSubmit) {
          this.$refs.catForm.reset()
        }
        
        this.$emit('closeAddTagCatDialog')
      },
      submit () {
        if (this.$refs.catForm.validate()) {
          const tagData = new FormData()
          tagData.append('label', this.title)
          const config = { 
            headers: { 
              'Authorization': 'Bearer '+this.$auth.token(),
              'Content-Type': 'multipart/form-data' 
            }
          }
          this.$axios
            .post('/auth/offers/tags/addCategory', tagData, config)
            .then (res => {
              switch (res.status) {
                case 200:
                  this.response.status = 'Success'
                  this.response.message = 'Category has been successfully submitted!'
                  break
  
                case 500:
                  this.response.status = 'Error'
                  this.response.message = 'Server is not responding. Error code: 500'
                  break
              
                default:
                  this.response.status = 'Hmm...'
                  this.response.message = 'Something went wrong...'
                  break
              }
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
    .dismiss-btn {
      margin: 0 auto;
    }
  </style>