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
    ...mapGetters('auth', ['isManagerIn', 'pinSuccess', 'user'])
  },
  created () {
    this.getUserData()
  },
  methods: {
    ...mapActions('auth', ['getUserData', 'sendLoginPincode']),
    async login (pincode) {
      const email = this.user.email
      await this.sendLoginPincode({ email, pincode })

      if (this.pinSuccess) {
        if (this.isLoggedIn && this.isManagerIn) {
          this.loading = false
          this.$refs.pincodeInput.triggerSuccess()
          await this.$router.push('/dashboard')
        } else if (this.isLoggedIn && !this.isManagerIn) {
          this.loading = false
          this.$refs.pincodeInput.triggerSuccess()
          await this.$router.push({ name: 'vending' })
        }
      } else {
        this.loading = false
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
