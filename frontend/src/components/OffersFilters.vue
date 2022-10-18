<template>
  <v-card
    outlined
  >
    <v-card-title
      @click="filtersExpanded = !filtersExpanded"
    >
      Filters
      <v-spacer />
      <div>
        <v-icon
          v-if="!filtersExpanded"
        >
          mdi-chevron-down
        </v-icon>
        <v-icon
          v-else
        >
          mdi-chevron-up
        </v-icon>
      </div>
    </v-card-title>
    <v-card-text
      class="filters-container"
      v-if="filtersExpanded"
    >
      <h3
        class="font-weight-light"
      >
        Title
      </h3>
      <v-text-field
        clearable
        v-model="title"
        outlined
        placeholder="Offer Title"
        dense
      />
      <h3
        class="font-weight-light"
      >
        Description
      </h3>
      <v-text-field
        clearable
        v-model="description"
        outlined
        placeholder="Offer Title"
        dense
      />
      <h3
        class="font-weight-light"
      >
        Tags
      </h3>
      <v-text-field
        clearable
        v-model="tags"
        outlined
        placeholder="Offer Title"
        dense
      />
      <h3
        class="font-weight-light"
      >
        Category
      </h3>
      <v-select
        clearable
        dense
        v-model="category"
        label="Category"
        solo
        :items="pCategories"
        item-text="label"
        item-value="id"
      />
      <div
        class="sorting-container"
      >
        <div
          class="sorting-title"
          @click="sortingExpanded = !sortingExpanded"
        >
          <h2>Sorting</h2>
          <v-spacer />
          <div>
            <v-icon
              v-if="!sortingExpanded"
            >
              mdi-chevron-down
            </v-icon>
            <v-icon
              v-else
            >
              mdi-chevron-up
            </v-icon>
          </div>
        </div>
        
        <div
          class="sorting-contains"
          v-if="sortingExpanded"
        >
          <h3
            class="font-weight-light"
          >
            By Date
          </h3>
          <v-select
            clearable
            dense
            v-model="date"
            solo
            :items="dateSorting"
          />
        </div>
      </div>
    </v-card-text>
    <v-divider />
    <v-card-actions
      class="actions-container"
      v-if="filtersExpanded"
    >
      <v-container
        class="btns-container"
      >
        <v-row>
          <v-col>
            <v-btn
              depressed
              block
              @click="fetchFilteredOffers()"
            >
              Search
            </v-btn>
          </v-col>
          <v-col>
            <v-btn
              depressed
              block
              @click="clearFilters()"
            >
              Clear
            </v-btn>
          </v-col>
        </v-row>
      </v-container>
    </v-card-actions>
  </v-card>
</template>

<script>
export default {
  props: {
    pCategories: { type: Array, default: null }
  },
  data () {
    return {
      title: null,
      description: null,
      tags: null,
      category: null,
      date: null,

      categories: [],
      filtersExpanded: false,
      sortingExpanded: false,

      dateSorting: [
        'ascending',
        'descending'
      ]
    }
  },
  mounted () {
    if (this.pCategories) {
      this.categories = this.pCategories
    }
  },
  methods: {
    clearFilters () {
      this.title = null
      this.description = null
      this.tags = null
      this.category = null

      this.fetchFilteredOffers()
    },
    fetchFilteredOffers () {
      const offerFilters = {
        title: this.title,
        description: this.description,
        tags: this.tags,
        category: this.category,
        date: this.date
      }
      
      this.$store.commit('setOffersFilters', offerFilters)
      this.$emit('updateFilters')
    }
  }
}
</script>

<style scoped>
  .actions-container, .filters-container {
    padding-bottom: 0;
    margin-bottom: 20px;
  }
  .btns-container {
    padding-top: 0;
  }
  .sorting-container {
    width: 100%;
  }
  .sorting-contains {
    margin-top: 20px;
  }
  .sorting-title {
    display: flex;
  }
</style>