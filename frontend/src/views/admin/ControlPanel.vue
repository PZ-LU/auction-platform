<!-- Modular control panel view for admins -->
<template>
  <v-container>
    <v-row>
      <h2 class="headline">
        Logged as: {{ $auth.user().username }}, {{ $auth.user().email }}
      </h2>
      <v-spacer />
      <h2 class="headline">
        Role: {{ getTranslatedRole() }}
      </h2>
    </v-row>
    <v-row>
      <v-col
        cols="3"
      >
        <v-card
          outlined
        >
          <v-card-title>
            Menu
          </v-card-title>
          <v-list>
            <v-list-item-group
              mandatory
              v-model="menuSection"
            >
              <v-list-item
                v-for="(item, index) in menuItems"
                :key="index"
              >
                <v-list-item-icon>
                  <v-icon>
                    {{ item.icon }}
                  </v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                  <v-list-item-title>
                    {{ item.label }}
                  </v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </v-list-item-group>
          </v-list>
        </v-card>
      </v-col>

      <v-col
        v-if="menuSection === 0"
      >
        <Auctions />
      </v-col>

      <v-col
        v-if="menuSection === 1"
      >
        <Offers />
      </v-col>

      <v-col
        v-if="menuSection === 2"
      >
        <Users />
      </v-col>

      <v-col
        v-if="menuSection === 3"
      >
        <Admins />
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  components: {
    Admins: () => import('../../components/admin/Admins'),
    Auctions: () => import('../../components/admin/Auctions'),
    Offers: () => import('../../components/admin/Offers'),
    Users: () => import('../../components/admin/Users')
  },
  data () {
    return {
      menuItems: [
        {
          label: 'Auctions',
          icon: 'mdi-currency-usd'
        },
        {
          label: 'Offers',
          icon: 'mdi-view-list'
        },
        {
          label: 'Users',
          icon: 'mdi-account-supervisor'
        }
      ],
      menuSection: null
    }
  },
  created () {
    this.checkIfSA()
  },
  methods: {
    getTranslatedRole () {
      switch (this.$auth.user().role) {
        case 'admin':
          return 'Administrator'
      
        case 'super_admin':
          return 'Super User'
      }
    },
    checkIfSA () {
      if (this.$auth.user().role === 'super_admin') {
        this.menuItems.push(
          {
            label: 'Admins',
            icon: 'mdi-account-box-multiple'
          }
        )
      }
    }
  }
}
</script>
