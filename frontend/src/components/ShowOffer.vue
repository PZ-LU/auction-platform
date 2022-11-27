<template>
  <v-form>
    <v-card>
      <v-card-title>
        <div>
          <h3>{{ pOffer.title }}</h3>
          
          <h4 class="subtitle-1">
            Posted on {{ new Date(pOffer.created_at).toLocaleDateString() }}
          </h4>
        </div>
        <v-spacer />
        <DeleteOfferDialog
          v-if="pOffer.status === 'active'"
          :pOffer="pOffer"
          @delete="deleteOffer()"
        />
        <v-btn
          icon
          @click="closeDialog()"
        >
          <v-icon>mdi-close</v-icon>
        </v-btn>
      </v-card-title>
      <v-card-text>
        <v-card
          style="margin-bottom: 10px"
          outlined
        >
          <v-card-title
            class="font-weight-light"
          >
            Description
          </v-card-title>
          <v-card-text>
            {{ pOffer.body }}
          </v-card-text>
        </v-card>
        <v-card
          outlined
          v-if="tags.length"
        >
          <v-card-title
            class="font-weight-light"
          >
            Tags
          </v-card-title>
          <v-card-text>
            <v-data-table
              :headers="headers"
              :items="tagsItems"
              :items-per-page="5"
              item-key="label"
              group-by="category"
              show-group-by
              dense
            />
          </v-card-text>
        </v-card>
        <v-container>
          <v-card>
            <image-lightbox
              v-for="image in images"
              :key="image"
              :src="image"
              class="image d-inline-block ma-5"
            />
          </v-card>
        </v-container>

        <v-container
          class="contacts"
        >
          <h3>
            Contacts
          </h3>
          <v-row>
            <v-col>
              <h3 class="subtitle-1">
                Added By:
              </h3>
              <v-chip
                large
              >
                <v-row
                  class="author-row"
                >
                  <v-col
                    class="author-col"
                  >
                    <v-img
                      class="profile-pic"
                      :src="pOffer.author_info.avatar_path"
                      width="32"
                      height="32"
                    />
                  </v-col>
                  <v-col
                    class="author-col"
                  >
                    {{ pOffer.author_info.username }}
                  </v-col>
                </v-row>
              </v-chip>
            </v-col>
            <v-col>
              <h3 class="subtitle-1">
                Phone:
              </h3>
              <v-chip>
                <span class="constacts-data">{{ pOffer.contact_phone }}</span>
              </v-chip>
            </v-col>
            <v-col>
              <h3 class="subtitle-1">
                Email:
              </h3>
              <v-chip>
                <span class="constacts-data">{{ pOffer.author_info.email }}</span>
              </v-chip>
            </v-col>
          </v-row>
        </v-container>
      </v-card-text>
    </v-card>
  </v-form>
</template>

<script>
export default {
  components: {
    DeleteOfferDialog: () => import('./essentials/DeleteOfferDialog'),
    ImageLightbox: () => import('./helpers/ImageLightbox')
  },
  props: {
    pCategories: {type: Array, default: null},
    pOffer: {type: Object, default: null}
  },
  data() {
    return {
      images: [],
      tags: [],

      // table data
      headers: [
        {
          text: 'Label',
          align: 'start',
          value: 'label'
        },
        {
          text: 'Category',
          value: 'category'
        }
      ],
      tagsItems: []
    }
  },
  created() {
    this.getImages()
    this.getTags()
  },
  methods: {
    closeDialog () {
      this.$emit('closeShowDialog')
    },
    deleteOffer () {
      const config = { 
        headers: { 
          'Authorization': 'Bearer '+this.$auth.token()
        }
      }
      this.$axios
        .post('auth/offer/delete_soft', { id : this.pOffer.id }, config)
        .then(res => {
          this.$emit('closeShowDialog')
        })
        .catch(err => {

        })
    },
    getCategoryName (categoryId) {
      const category = this.pCategories.find(category => { return category.id === categoryId })
      return category.label
    },
    getImages () {
      const req = 'offer_media/'+this.pOffer.id
      this.$axios
        .get (req)
        .then ((result) => {
          result.data.photo_path.forEach(element => {
            this.images.push(element)
          });
        })
        .catch((err) => {
          console.log(err)
        })
    },
    async getTags () {
      const req = 'offer/'+this.pOffer.id
      this.$axios
        .get (req)
        .then ((result) => {
          const {data:{data}} = result
          this.tags = data.tags
          this.getTableContents()
        })
        .catch((err) => {
          console.log(err)
        })
    },
    getTableContents () {
      for (const part of this.tags) {
        this.tagsItems.push({
          'label': part.part,
          'category': this.getCategoryName(part.category)
        })
      }
    }
  },
}
</script>

<style scoped>
  .author-col {
    align-self: center;
  }
  .contacts {
    border: 1px solid lightgrey;
    border-radius: 5px;
    padding-bottom: 0;
  }
  .constacts-data {
    font-weight: bold;
  }
  .image {
    max-width: 200px;
    max-height: 200px;
  }
  .profile-pic {
    border-radius: 50%;
  }
</style>