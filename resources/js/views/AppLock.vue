<template>
  <v-container
    class="page-lock"
    fill-height
  >
    <v-row>
      <v-col>
        <v-card
          class="pa-3 page-lock__card"
          tile
        >
          <v-card-title />
          <v-card-text>
            <vue-pincode
              ref="pincodeInput"
              @pincode="login"
            />
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapActions, mapGetters, mapState } from 'vuex'

export default {
  name: 'AppLock',
  computed: {
    ...mapState('auth', ['isLoggedIn', 'userData']),
    ...mapGetters(['errors']),
    ...mapGetters('auth', ['isManagerIn', 'user'])
  },
  created () {
    this.getUserData()
  },
  methods: {
    ...mapActions('auth', ['getUserData', 'sendLoginPincode']),
    async login (pincode) {
      try {
        const email = this.user.email
        this.sendLoginPincode({
          email,
          pincode
        }).then(() => {
          if (this.isLoggedIn && this.isManagerIn) {
            this.loading = false
            this.$router.push('/dashboard')
            this.$refs.pincodeInput.triggerSuccess()
          } else if (this.isLoggedIn && !this.isManagerIn) {
            this.loading = false
            this.$router.push({ name: 'vending' })
            this.$refs.pincodeInput.triggerSuccess()
          } else {
            this.loading = false
            this.$refs.pincodeInput.triggerMiss()
          }
        })
      } catch (e) {
        this.$refs.pincodeInput.triggerMiss()
      }
    }
  }
}
</script>

<style lang="sass" scoped>
.page-lock
    &__card
        max-width: 320px
        margin: 0 auto
</style>
