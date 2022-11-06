<template>
  <v-card
    outlined
  >
    <v-card-title>
      Users
    </v-card-title>
    <v-card-subtitle>
      Active
    </v-card-subtitle>
    <v-card-text>
      <UserList
        :pUsersArray="users"
        @update="fetchUsers()"
      />
    </v-card-text>
    <v-card-subtitle>
      Suspended
    </v-card-subtitle>
    <v-card-text>
      <UserList
        :pUsersArray="suspendedUsers"
        @update="fetchUsers()"
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
      users: [],
      suspendedUsers: [],
      suspendDialog: false
    }
  },
  created () {
    this.fetchUsers()
  },
  methods: {
    fetchUsers () {
      this.$axios
        .get('auth/users')
        .then(res => {
          let incomingUsers
          if (res.data.users.length) {
            incomingUsers = res.data.users
          } else {
            incomingUsers = [res.data.users]
          }

          this.users = []
          this.suspendedUsers= []

          for (const user of incomingUsers) {
            user.suspendDialog = false
            switch (user.status) {
              case 'active':
                this.users.push(user)
                break
            
              case 'suspended':
                this.suspendedUsers.push(user)
                break
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

<style scoped>
  .users-container {
    max-height: 70vh;
    overflow: auto;
  }
</style>