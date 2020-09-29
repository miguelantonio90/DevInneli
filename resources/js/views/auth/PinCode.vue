<template>
  <v-container
    class="page-login"
    fill-height
  >
    <v-row>
      <v-col>
        <v-card
          class="pa-3 page-login__card"
          tile
        >
          <v-card-title>
            <h1 class="primary--text display-1 text-center">
              {{ $vuetify.lang.t('$vuetify.pinTile') }}
            </h1>
          </v-card-title>
          <v-card-text>
            <vue-pincode
              ref="pincodeInput"
              @pincode="login"
            />
          </v-card-text>
          <v-card-actions>
            <v-btn
              :to="{ name: 'login' }"
              color="secondary"
            >
              <v-icon>mdi-arrow-left</v-icon>
              {{ $vuetify.lang.t('$vuetify.login') }}
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapActions, mapGetters, mapState } from 'vuex'

export default {
  name: 'PinCode',
  props: {
    email: {
      type: String,
      default: ''
    }
  },
  data () {
    return {
      pinCode: '',
      loading: false
    }
  },
  computed: {
    ...mapGetters(['errors']),
    ...mapState('auth', ['isLoggedIn']),
    prefix () {
      return ''
    }
  },
  methods: {
    ...mapActions('auth', ['sendLoginPincode']),
    async login (pincode) {
      try {
        const email = this.$route.params.email
        this.sendLoginPincode({ email, pincode }).then(() => {
          if (this.isLoggedIn) {
            this.loading = false
            this.$router.push('/dashboard')
          } else {
            this.loading = false
          }
        })
        this.$refs.pincodeInput.triggerSuccess()
      } catch (e) {
        this.$refs.pincodeInput.triggerMiss()
      }
    }
  }
}
</script>

<style lang="sass" scoped>
.page-login
  &__card
    max-width: 320px
    margin: 0 auto
</style>
