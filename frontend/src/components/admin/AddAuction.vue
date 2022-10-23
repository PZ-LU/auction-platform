<template>
  <div>
    <v-form
      v-if="!response"
      ref="aucForm"
    >
      <v-card>
        <v-card-title>
          Add {{ pType }} auction
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col>
                <v-text-field
                  v-model="objectName"
                  label="Auction object name"
                  placeholder="Auction object name"
                  :counter="24"
                  :rules="[rules.required]"
                />
              </v-col>
              <v-col>
                <v-file-input
                  v-model="image"
                  label="Auction Image"
                  prepend-icon="mdi-camera"
                  :rules="[rules.required]"
                  show-size
                />
              </v-col>
            </v-row>

            <!-- /charity block -->
            <div
              v-if="pType === 'charity'"
            >
              <v-row>
                <v-col>
                  <v-text-field
                    v-model="amount"
                    label="Goal (EUR)"
                    :rules="[rules.required, scoped_rules.numbersOnly, scoped_rules.amount]"
                  />
                </v-col>
              </v-row>
            </div>
            <!-- /charity block -->

            <!-- commercial block -->
            <div
              v-if="pType === 'commercial'"
            >
              <v-row>
                <v-col>
                  <v-text-field
                    v-model="amount"
                    label="Starting bid (EUR)"
                    :rules="[rules.required, scoped_rules.numbersOnly, scoped_rules.amount]"
                  />
                </v-col>
                <v-col>
                  <v-menu
                    :rules="[rules.required]"
                    v-model="dateMenu"
                    offset-y
                  >
                    <template #activator="{ on }">
                      <v-text-field
                        v-model="date"
                        label="End date"
                        prepend-icon="mdi-event"
                        readonly
                        v-on="on"
                      />
                    </template>
                    <v-date-picker
                      v-model="date"
                      :min="new Date(new Date().getTime()+(1*24*60*60*1000)).toISOString().substr(0, 10)"
                    />
                  </v-menu>
                  <span
                    v-if="!date"
                  >
                    Specify participation ending date
                  </span>
                </v-col>
              </v-row>
            </div>
            <!-- /commercial block -->
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-btn
            text
            color="primary"
            width="50%"
            @click="submit()"
          >
            Submit
          </v-btn>
          <v-btn
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
        Attention!
      </v-card-title>
      <v-card-text>
        {{ response }}
      </v-card-text>
      <v-card-actions>
        <v-btn
          text
          block
          @click="closeDialog()"
        >
          Ok
        </v-btn>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
import axios from 'axios'
import rules from '../../plugins/rules/rules'

export default {
  props: {
    pType: {type: String, default: null}
  },
  data () {
    return {
      objectName: null,
      amount: null,
      date: null,
      dateMenu: null,
      image: null,
      response: null,

      rules: rules,
      scoped_rules: {
        numbersOnly: v => !!v && /^[0-9]+\.?[0-9]+$/.test(v) || 'Incorrect amount',
        amount: v => this.amount > 0 || 'Incorrect amount'
      }
    }
  },
  beforeDestroy () {
    this.closeDialog()
  },
  methods: {
    closeDialog () {
      this.destroyDialog()
      this.$emit('closeDialog')
    },
    destroyDialog () {
      this.objectName = null
      this.amount = null
      this.date = null
      this.dateMenu = null
      this.image = null
      this.response = null
      try {
        this.$refs.aucForm.reset()
      } catch (error) {
        return
      }
    },
    submit () {
      if (this.$refs.aucForm.validate() && (this.pType === 'commercial' ? this.date : true)) {
        const newAuctionData = new FormData()
        newAuctionData.append('type', this.pType)
        newAuctionData.append('object_name', this.objectName)
        if (this.image.size / 1024 / 1024 > 1.5) {
            alert("Single image size cannot exceed 1.5 MiB!")
            return
        }
        newAuctionData.append('preview_image', this.image, this.image.name)
        
        switch (this.pType) {
          case 'charity':
            newAuctionData.append('goal', this.amount)
            break
        
          case 'commercial':
            newAuctionData.append('start_bid', this.amount)
            newAuctionData.append('end_date', this.date)
            break
        }

        const config = { 
          headers: { 
            'Authorization': 'Bearer '+this.$auth.token(),
            'Content-Type': 'multipart/form-data' 
          }
        }

        axios
          .post('http://127.0.0.1:8000/api/auth/auction/add', newAuctionData, config)
          .then (res => {
            this.destroyDialog()
            this.response = `Auction successfully created!`
          })
          .catch ((err) => {
            this.response = `Something went wrong!`
            console.log(err)
          })
      }
    }
  }
}
</script>
