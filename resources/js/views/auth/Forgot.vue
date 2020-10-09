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
              {{ $vuetify.lang.t('$vuetify.forgot') }}
            </h1>
          </v-card-title>
          <v-card-text>
            <v-form
              ref="form"
              v-model="formValid"
              class="my-10"
              lazy-validation
            >
              <v-text-field
                v-model="email"
                :label="$vuetify.lang.t('$vuetify.email')"
                :placeholder="$vuetify.lang.t('$vuetify.email')"
                :rules="formRule.email"
                append-icon="mdi-email"
                autocomplete="off"
                name="register"
                required
                type="email"
              />
            </v-form>
          </v-card-text>
          <v-card-actions>
            <v-btn
              :to="{ name: 'login' }"
              color="secondary"
            >
              <v-icon>mdi-arrow-left</v-icon>
              {{ $vuetify.lang.t('$vuetify.login') }}
            </v-btn>
            <v-spacer />
            <v-btn
              :disabled="!formValid"
              :loading="loading"
              color="primary"
              @click="sendNotify"
            >
              <v-icon>mdi-send</v-icon>
              {{ $vuetify.lang.t('$vuetify.forgot_btn') }}
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
  name: 'Forgot',
  data () {
    return {
      email: '',
      loading: false,
      formValid: false,
      formRule: this.$rules
    }
  },
  computed: {
    ...mapGetters(['errors']),
    ...mapState('auth', ['successForgot']),
    prefix () {
      return ''
    }
  },
  methods: {
    ...mapActions('auth', ['sendEmailForgot']),
    async sendNotify () {
      this.loading = true
      await this.sendEmailForgot(this.email)
    }
  }
}
</script>

<style lang="sass" scoped>
.page-login
  &__card
    max-width: 600px
    margin: 0 auto
</style>
