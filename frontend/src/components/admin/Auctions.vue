<template>
  <div
    style="display: flex;"
  >
    <v-card
      outlined
      class="auctions"
    >
      <v-card-title>Active Auctions</v-card-title>
      <v-divider />
      <v-card-subtitle>
        <h3>Charity</h3>
      </v-card-subtitle>
      <AuctionList
        v-if="activeCharityAuction"
        :pAuctions="[activeCharityAuction]"
        :pIsActive="true"
        @updateAuctions="closeDialog()"
      />
      <v-card-actions
        v-else
      >
        <v-dialog
          v-model="showCharityCreateDialog"
          persistent
        >
          <template
            #activator="{ on }"
          >
            <v-btn
              depressed
              block
              color="primary"
              v-on="on"
            >
              Add Auction
            </v-btn>
          </template>

          <AddAuction
            :pType="'charity'"
            @closeDialog="showCharityCreateDialog = false"
          />
        </v-dialog>
      </v-card-actions>

      <v-card-subtitle>
        <h3>Commercial</h3>
      </v-card-subtitle>
      <AuctionList
        v-if="activeCommercialAuction"
        :pAuctions="[activeCommercialAuction]"
        :pIsActive="true"
        @updateAuctions="closeDialog()"
      />
      <v-card-actions
        v-else
      >
        <v-dialog
          v-model="showCommercialCreateDialog"
          persistent
        >
          <template
            #activator="{ on }"
          >
            <v-btn
              depressed
              block
              color="primary"
              v-on="on"
            >
              Add Auction
            </v-btn>
          </template>

          <AddAuction
            :pType="'commercial'"
            @closeDialog="closeDialog()"
          />
        </v-dialog>
      </v-card-actions>

      <v-divider />
      <v-card-title>Dismissed Auctions</v-card-title>
      <v-divider />
      <v-card-subtitle>
        <h3>Charity</h3>
      </v-card-subtitle>
      <AuctionList
        :pAuctions="charityAuctions"
      />
      
      <v-card-subtitle>
        <h3>Commercial</h3>
      </v-card-subtitle>
      <AuctionList
        :pAuctions="commercialAuctions"
      />
    </v-card>
    <!-- object types -->
    <v-card
      outlined
    >
      <v-card-title>Object Types</v-card-title>
      <v-divider />
      <v-list>
        <v-list-item-group
          v-model="objTypeSection"
          class="scrollable"
        >
          <v-list-item
            v-for="objType in objTypes"
            :key="objType.id"
            @click="selectedObjType = objType"
          >
            <v-list-item-content>
              <v-list-item-title
                style="display: contents;"
              >
                {{ objType.label }}
                <v-spacer/>
                <v-btn
                  v-if="$auth.user().id && ($auth.user().role == 'admin' || $auth.user().role == 'super_admin')"
                  icon
                  color="error"
                  style="margin-left: auto;"
                  @click="showDeleteObjTypeDialog = true"
                >
                  <v-icon>mdi-delete-forever-outline</v-icon>
                </v-btn>
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list-item-group>
      </v-list>
      <v-dialog
        v-if="$auth.user().id && ($auth.user().role == 'admin' || $auth.user().role == 'super_admin')"
        v-model="addObjTypeDialog"
        persistent
      >
        <template
          #activator="{ on }"
        >
          <v-btn
            text
            block
            v-on="on"
          >
            New Object Type
          </v-btn>
        </template>

        <v-form
          ref="catForm"
          v-if="!response"
        >
          <AddObjectType
            @closeAddObjTypeDialog="closeAddObjTypeDialog()"
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
              @click="closeAddObjTypeDialog()"
            >
              Ok
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-card>
    <!-- hidden delete object type dialog -->
    <v-dialog
      v-model="showDeleteObjTypeDialog"
      persistent
    >
      <v-card
        v-if="!response"
      >
        <v-card-title>
          Attention!
        </v-card-title>
        <v-card-text>
          Would You really like to delete this type?
          You cannot delete types that are already assigned to auction objects.
        </v-card-text>
        <v-card-actions>
          <v-btn
            width="50%"
            text
            color="primary"
            @click="deleteObjType()"
          >
            Confirm
          </v-btn>
          <v-btn
            width="50%"
            text
            color="error"
            @click="showDeleteObjTypeDialog = false"
          >
            Cancel
          </v-btn>
        </v-card-actions>
      </v-card>

      <v-card
        v-if="response"
      >
        <v-card-title>
          {{ response }}
        </v-card-title>
        <v-card-actions>
          <v-btn
            text
            block
            @click="closeAddObjTypeDialog()"
          >
            Ok
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import fetchAuctions from '../../plugins/fetchAuctions'

export default {
  components: {
    AddAuction: () => import('./AddAuction'),
    AuctionList: () => import('./AuctionList'),
    AddObjectType: () => import('./AddObjectType')
  },
  data () {
    return {
      activeCharityAuction: null,
      activeCommercialAuction: null,

      charityAuctions: [],
      commercialAuctions: [],

      showCharityCreateDialog: false,
      showCommercialCreateDialog: false,

      selectedObjType: null,
      addObjTypeDialog: false,
      objTypeSection: null,
      showDeleteObjTypeDialog: false,
      objTypes: [],

      response: null
    }
  },
  created () {
    this.fetchActiveAuctions()
    this.fetchDismissedAuctions()
    this.fetchObjectTypes()
  },
  methods: {
    closeDialog() {
      this.showCharityCreateDialog = false
      this.showCommercialCreateDialog = false
      this.fetchActiveAuctions()
      this.fetchDismissedAuctions()
    },
    closeAddObjTypeDialog () {
      this.addObjTypeDialog = false
      this.showDeleteObjTypeDialog = false
      this.response = null
      this.fetchObjectTypes()
    },
    fetchActiveAuctions() {
      this.activeCharityAuction = null
      this.activeCommercialAuction = null
      fetchAuctions()
        .then(res => {
          this.activeCharityAuction = res.charityAuctions[0]
          this.activeCommercialAuction = res.commercialAuctions[0]
        })
    },
    fetchDismissedAuctions() {
      fetchAuctions('dismissed')
      .then(res => {
        this.charityAuctions = res.charityAuctions
        this.commercialAuctions = res.commercialAuctions
        for (const auction of this.charityAuctions) {
          auction['showDialog'] = false
        }
        for (const auction of this.commercialAuctions) {
          auction['showDialog'] = false
        }
      })
    },
    fetchObjectTypes() {
      this.$axios
      .get('auction/objects')
      .then(res => {
        let {data:{obj_types:obj_types}} = res || {}
        if (obj_types) {
          this.objTypes = obj_types
        }
      })
      .catch(err => {
        console.log(err)
      })
    },
    deleteObjType () {
      const config = { 
        headers: { 
          'Authorization': 'Bearer '+this.$auth.token(),
        }
      }
      this.$axios
        .post('auth/auction/object/deleteType', { type: this.selectedObjType.id}, config)
        .then (res => {
          let {data:data} = res
          if (data.status == 'error') {
            this.showDeleteObjTypeDialog = true
            this.response = data.message
          } else {
            this.closeAddObjTypeDialog()
          }
        })
        .catch ((err) => {
          console.log(err)
        })
    },
  }
}
</script>

<style scoped>
  .auctions {
    min-width: 70%;
    margin-right: 25px;
  }
  .scrollable {
    max-height: 500px;
    overflow: auto;
  }
  .v-card__subtitle {
    padding-top: 20px;
    padding-bottom: 0;
  }
</style>