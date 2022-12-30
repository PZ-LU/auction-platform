<template>
  <div
    style="display: flex;"
  >
    <v-card
      outlined
      class="offers"
    >
      <v-card-title>
        User Offers
        <v-spacer />
        <v-btn
          icon
          @click="fetchAll()"
        >
          <v-icon>mdi-autorenew</v-icon>
        </v-btn>
      </v-card-title>
      <v-card-subtitle>
        Active Offers
      </v-card-subtitle>
      <v-container
        class="offers-container"
      >
        <v-row
          v-for="offer in activeOffers"
          :key="offer.id"
        >
          <OffersDisplay
            class="offer"
            :pOffer="offer"
            :pCategories="categories"
            :pIsTiled="true"
            :pIsAdmin="true"
            @reloadOffers="fetchAll"
          />
        </v-row>
      </v-container>

      <v-card-subtitle>
        Archived Offers
      </v-card-subtitle>
      <v-container
        class="offers-container"
      >
        <v-row
          v-for="offer in archivedOffers"
          :key="offer.id"
        >
          <OffersDisplay
            class="offer"
            :pOffer="offer"
            :pCategories="categories"
            :pIsTiled="true"
            :pIsAdmin="true"
            @reloadOffers="fetchAll"
          />
        </v-row>
      </v-container>
    </v-card>
    <!-- object types -->
    <v-card
      outlined
    >
      <v-card-title>Tag Categories</v-card-title>
      <v-divider />
      <v-list>
        <v-list-item-group
          v-model="tagCatSection"
          class="scrollable"
        >
          <v-list-item
            v-for="tagCat in categories"
            :key="tagCat.id"
            @click="selectedTagCat = tagCat"
          >
            <v-list-item-content>
              <v-list-item-title
                style="display: contents;"
              >
                {{ tagCat.label }}
                <v-spacer/>
                <v-btn
                  v-if="$auth.user().id && ($auth.user().role == 'admin' || $auth.user().role == 'super_admin')"
                  icon
                  color="error"
                  style="margin-left: auto;"
                  @click="showDeleteTagCatDialog = true"
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
        v-model="addTagCatDialog"
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
            New Tag Category
          </v-btn>
        </template>

        <v-form
          ref="catForm"
          v-if="!response"
        >
          <AddTagCategory
            @closeAddTagCatDialog="closeAddTagCatDialog()"
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
              @click="closeAddTagCatDialog()"
            >
              Ok
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-card>
    <!-- hidden delete object type dialog -->
    <v-dialog
      v-model="showDeleteTagCatDialog"
      persistent
    >
      <v-card
        v-if="!response"
      >
        <v-card-title>
          Attention!
        </v-card-title>
        <v-card-text>
          Would You really like to delete this category?
          You cannot delete categories that are already assigned to posted offer tags.
        </v-card-text>
        <v-card-actions>
          <v-btn
            width="50%"
            text
            color="primary"
            @click="deleteTagCategory()"
          >
            Confirm
          </v-btn>
          <v-btn
            width="50%"
            text
            color="error"
            @click="showDeleteTagCatDialog = false"
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
            @click="closeAddTagCatDialog()"
          >
            Ok
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
export default {
  components: {
    OffersDisplay: () => import('../OffersDisplay'),
    AddTagCategory: () => import('./AddTagCategory')
  },
  data () {
    return {
      activeOffers: [],
      archivedOffers: [],
      categories: [],
      selectedTagCat: null,

      addTagCatDialog: false,
      tagCatSection: null,
      showDeleteTagCatDialog: false,

      response: null
    }
  },
  created () {
    this.fetchAll()
  },
  methods: {
    fetchAll () {
      this.fetchTagCategories()
      this.fetchAllOffers()
    },
    fetchTagCategories () {
      this.$axios
        .get('tag_categories')
        .then(res => {
          const {data:{data}} = res
          if (data){
            if (data.length)
              this.categories = data
          }
        })
        .catch(err => {
          this.errorCode = err.response.status
          this.responseError()
        })
    },
    closeAddTagCatDialog () {
      this.addTagCatDialog = false
      this.showDeleteTagCatDialog = false
      this.response = null
      this.fetchTagCategories()
    },
    fetchAllOffers () {
      this.activeOffers = []
      this.archivedOffers = []
      const config = { 
        headers: { 
          'Authorization': 'Bearer '+this.$auth.token(),
        }
      }
      this.$axios
        .get('auth/offers')
        .then(res => {
          const {data:{data}} = res
          data.map(offer => {
            switch (offer.status) {
              case 'active':
                this.activeOffers.push(offer)
                break

              case 'archived':
                this.archivedOffers.push(offer)
                break
            }
          })
        })
        .catch(err => {
          console.log(err);
          this.errorCode = err
          this.responseError()
        })
    },
    deleteTagCategory () {
      const config = { 
        headers: { 
          'Authorization': 'Bearer '+this.$auth.token(),
        }
      }
      this.$axios
        .post('auth/offers/tags/deleteCategory', { category: this.selectedTagCat.id}, config)
        .then (res => {
          let {data:data} = res
          if (data.status == 'error') {
            this.showDeleteTagCatDialog = true
            this.response = data.message
          } else {
            this.closeAddTagCatDialog()
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
  .offers-container {
    max-height: 500px;
    overflow: auto;
  }
  .offer {
    width: 100%;
  }
  .offers {
    min-width: 70%;
    margin-right: 25px;
  }
  .scrollable {
    max-height: 500px;
    overflow: auto;
  }
</style>