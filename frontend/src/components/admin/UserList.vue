<template>
  <v-container
    class="users-container"
  >
    <v-row
      v-for="user in pUsersArray"
      :key="user.id"
    >
      <v-col>
        <span class="font-weight-bold">{{ user.email }}</span> ({{ user.username }})
      </v-col>
      <v-col>
        <v-dialog
          persistent
          v-model="user.suspendDialog"
        >
          <template
            #activator="{ on }"
          >
            <v-btn
              text
              color="error"
              v-on="on"
            >
              <span
                v-if="pIsSuperAdmin"
              >
                {{ user.role === 'user' ? 'Promote' : 'Remove' }}
              </span>
              <span
                v-else
              >{{ isUserSuspended(user.status) ? 'Activate' : 'Suspend' }}</span>
            </v-btn>
          </template>
          
          <v-card>
            <v-card-title>
              Attention!
            </v-card-title>

            <v-card-text>
              Would you really like to 
              <span
                v-if="pIsSuperAdmin"
              >
                {{ user.role === 'user' ? 'promote to admins' : 'remove from admins' }}
              </span>
              <span
                v-else
              >
                {{ isUserSuspended(user.status) ? 'activate' : 'suspend' }}
              </span>
              user
              <span class="font-weight-bold">{{ user.email }}</span> ({{ user.username }}) ?
            </v-card-text>
            <v-card-actions>
              <v-btn
                text
                width="50%"
                color="success"
                @click="pIsSuperAdmin ? changeUserPrivilege(user, (user.role === 'user' ? 'admin' : 'user')) : changeUserStatus(user, (isUserSuspended(user.status) ? 'active' : 'suspended'))"
              >
                Confirm
              </v-btn>
              <v-btn
                text
                width="50%"
                color="error"
                @click="user.suspendDialog = false"
              >
                Cancel
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import axios from 'axios'

export default {
  props: {
    pUsersArray: {type: Array, default: null},
    pIsSuperAdmin: {type: Boolean, default: false}
  },
  methods: {
    changeUserPrivilege (user, role) {
      const config = { 
        headers: { 
          'Authorization': 'Bearer '+this.$auth.token(),
        }
      }
      this.$axios
        .post('auth/user/setRole', { user_id: user.id, role: role }, config)
        .then(res => {
          this.$emit('update')
        })
        .catch(err => {
          console.log(err)
        })
    },
    changeUserStatus (user, status) {
      const config = { 
        headers: { 
          'Authorization': 'Bearer '+this.$auth.token(),
        }
      }
      this.$axios
        .post('auth/user/setStatus', { user_id: user.id, status: status }, config)
        .then(res => {
          this.$emit('update')
        })
        .catch(err => {
          console.log(err)
        })
    },
    isUserSuspended: function (status) {
      if (status === 'active') {
        return false
      }
      return true
    }
  }
}
</script>
