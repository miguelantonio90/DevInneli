<template>
  <div>
    <v-container
      v-if="!loadingData"
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
              <div class="xs12 text-center">
                <div class="user-thumb text-center">
                  <v-avatar size="80">
                    <img
                      src="/assets/creative_process/11.jpeg"
                      :alt="getCompanyName"
                    >
                  </v-avatar>
                  <h3>{{ getCompanyName }}</h3>
                  <!-- <v-progress-circular
                    v-if="loading"
                    :width="3"
                    indeterminate
                    color="primary"
                  />-->
                </div>
              </div>
              <v-progress-linear
                v-if="loading"
                color="primary"
                indeterminate
                rounded
              />
              <vue-pincode
                ref="pincodeInput"
                @pincode="login"
              />
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
import { mapActions, mapGetters, mapState } from 'vuex'

export default {
  name: 'AppLock',
  data () {
    return {
      loadingData: false,
      loading: false
    }
  },
  computed: {
    ...mapState('auth', ['isLoggedIn', 'userData']),
    ...mapGetters(['errors']),
    ...mapGetters('auth', [
      'isManagerIn',
      'isAdminIn',
      'pinSuccess',
      'user'
    ]),
    getCompanyName () {
      return `${this.user.company.name}`
    }
  },
  created () {
    this.loadingData = true
    this.getUserData().then(() => {
      this.loadingData = false
    })
  },
  methods: {
    ...mapActions('auth', ['getUserData', 'sendLoginPincode']),
    async login (pincode) {
      this.loading = true
      const email = this.user.email
      await this.sendLoginPincode({ email, pincode }).then(() => {
        if (this.pinSuccess) {
          if (this.isLoggedIn && this.isAdminIn) {
            this.loading = false
            this.$refs.pincodeInput.triggerSuccess()
            this.$router.push({ name: 'admin_dashboard' })
          } else if (this.isLoggedIn && this.isManagerIn) {
            this.loading = false
            this.$refs.pincodeInput.triggerSuccess()
            this.$router.push('/dashboard')
          } else if (this.isLoggedIn && !this.isManagerIn) {
            this.loading = false
            this.$refs.pincodeInput.triggerSuccess()
            this.$router.push({ name: 'dashboard' })
          }
        } else {
          this.loading = false
          this.$refs.pincodeInput.triggerMiss()
        }
      })
    }
  }
}
</script>

<style lang="sass" scoped>
.page-lock
    &__card
        max-width: 320px
        margin: 0 auto
        border-radius: 6px !important
</style>
