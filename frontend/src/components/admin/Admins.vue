<template>
  <v-card
    outlined
  >
    <v-card-title>
      Privilege Control
    </v-card-title>
    <v-card-subtitle>
      Administrators
    </v-card-subtitle>
    <v-card-text>
      <UserList
        :pUsersArray="admins"
        :pIsSuperAdmin="true"
        @update="fetchAllUsers()"
      />
    </v-card-text>

    <v-card-subtitle>
      Active Users
    </v-card-subtitle>
    <v-card-text>
      <UserList
        :pUsersArray="activeUsers"
        :pIsSuperAdmin="true"
        @update="fetchAllUsers()"
      />
    </v-card-text>
  </v-card>
</template>

<script>
import axios from 'axios'

export default {
  components: {
    UserList: () => import('./UserList')
  },
  data () {
    return {
      activeUsers: [],
      admins: [],
      sAdmins: []
    }
  },
  created () {
    this.fetchAllUsers()
  },
  methods: {
    fetchAllUsers () {
      this.$axios
        .get('auth/users/service')
        .then(res => {
          let incomingUsers
          if (res.data.users.length) {
            incomingUsers = res.data.users
          } else {
            incomingUsers = [res.data.users]
          }

          this.activeUsers = []
          this.admins= []
          this.sAdmins= []

          for (const user of incomingUsers) {
            user.dialog = false
            if (user.status === 'active') {
              switch (user.role) {
                case 'user':
                  this.activeUsers.push(user)
                  break
              
                case 'admin':
                  this.admins.push(user)
                  break
                
                case 'super_admin':
                  this.sAdmins.push(user)
                  break
              }
            }
          }
        })
        .catch(err => {
          console.log(err)
        })
    }
  }
}
</script>
