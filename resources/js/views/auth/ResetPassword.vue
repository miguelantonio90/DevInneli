<template>
  <v-container
    class="page-reset"
    fill-height
  >
    <v-row>
      <v-col>
        <v-card
          class="pa-3 page-reset__card"
          tile
        >
          <v-card-title>
            <h1 class="primary--text display-1 text-center">
              {{ $vuetify.lang.t('$vuetify.reset_password') }}
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
                v-model="formReset.email"
                :label="$vuetify.lang.t('$vuetify.email')"
                :rules="formRule.email"
                append-icon="mdi-email"
                autocomplete="off"
                name="email"
                required
                type="email"
              />
              <v-text-field
                v-model="formReset.password"
                :append-icon="
                  hidePassword1 ? 'mdi-eye' : 'mdi-eye-off'
                "
                :label="$vuetify.lang.t('$vuetify.password')"
                :rules="formRule.password"
                :type="hidePassword1 ? 'password' : 'text'"
                autocomplete="off"
                name="password"
                required
                @click:append="hidePassword1 = !hidePassword1"
              />
              <v-text-field
                v-model="formReset.password_confirmation"
                :append-icon="
                  hidePassword2 ? 'mdi-eye' : 'mdi-eye-off'
                "
                :label="
                  $vuetify.lang.t('$vuetify.confirm_password')
                "
                :rules="formRule.password_confirmation"
                :type="hidePassword2 ? 'password' : 'text'"
                autocomplete="off"
                name="password_confirmation"
                required
                @click:append="hidePassword2 = !hidePassword2"
              />
            </v-form>
          </v-card-text>
          <v-card-actions>
            <v-tooltip
              bottom
            >
              <template v-slot:activator="{ on, attrs }">
                <v-icon
                  v-bind="attrs"
                  class="mr-3"
                  v-on="on"
                  @click="$router.push({name:'login'})"
                >
                  mdi-chevron-left
                </v-icon>
              </template>
              <span>{{ $vuetify.lang.t('$vuetify.login') }}</span>
            </v-tooltip>
            <v-spacer />
            <v-btn
              :disabled="!formValid || loadingReset"
              :loading="loadingReset"
              color="primary"
              @click="changePassword"
            >
              <v-icon>mdi-content-save</v-icon>
              {{ $vuetify.lang.t('$vuetify.reset_password') }}
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapActions, mapState } from 'vuex'

export default {
  name: 'ResetPassword',
  data () {
    return {
      formValid: false,
      hidePassword1: true,
      hidePassword2: true,
      formRule: this.$rules
    }
  },
  computed: {
    ...mapState('auth', ['formReset', 'successReset', 'loadingReset'])
  },
  methods: {
    ...mapActions('auth', ['sendResetPassword']),
    async changePassword () {
      const data = {
        token: this.$route.params.hash,
        email: this.formReset.email,
        password: this.formReset.password,
        password_confirmation: this.formReset.password_confirmation
      }
      await this.sendResetPassword(data)
    }
  }
}
</script>

<style lang="sass" scoped>
.page-reset
    &__card
        max-width: 450px
        margin: 0 auto
        border-radius: 6px !important
</style>
