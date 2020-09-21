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
            <img
              alt="Inneli APP"
              src="/assets/m.png"
              width="55"
            >
            <h1 class="primary--text display-1">
              INNELI
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
                v-model="fromModel.email"
                :label="$vuetify.lang.t('$vuetify.email')"
                :rules="formRule.email"
                append-icon="mdi-email"
                autocomplete="off"
                name="login"
                required
                type="email"
              />
              <v-text-field
                v-model="fromModel.password"
                :append-icon="
                  hidePassword ? 'mdi-eye' : 'mdi-eye-off'
                "
                :label="$vuetify.lang.t('$vuetify.password')"
                :rules="formRule.password"
                :type="hidePassword ? 'password' : 'text'"
                autocomplete="off"
                name="password"
                required
                @click:append="hidePassword = !hidePassword"
              />
              <v-btn
                :to="{ name: 'forgot' }"
                color="primary"
                small
                text
              >
                {{ $vuetify.lang.t('$vuetify.forgot') }}
              </v-btn>
            </v-form>
          </v-card-text>
          <v-card-actions>
            <v-tooltip
              v-for="item in socialIcons"
              :key="item.text"
              bottom
            >
              <template v-slot:activator="{ on, attrs }">
                <v-icon
                  v-bind="attrs"
                  class="mr-3"
                  v-on="on"
                  @click="handleSocialLogin"
                  v-text="item.icon"
                />
              </template>
              <span>{{ item.text }}</span>
            </v-tooltip>
            <v-spacer />
            <v-btn
              :disabled="!formValid"
              :loading="loading"
              color="primary"
              @click="login"
            >
              <v-icon>mdi-account</v-icon>
              {{ $vuetify.lang.t('$vuetify.login') }}
            </v-btn>
            <v-btn
              :to="{ name: 'register' }"
              color="secondary"
            >
              <v-icon>mdi-account-plus</v-icon>
              {{ $vuetify.lang.t('$vuetify.register') }}
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
  name: 'PageLogin',
  data () {
    return {
      formValid: false,
      loading: false,
      hidePassword: true,
      formRule: {
        email: [
          (v) =>
            !!v ||
              this.$vuetify.lang.t('$vuetify.rule.required', [
                this.$vuetify.lang.t('$vuetify.email')
              ]),
          (v) =>
            /.+@.+/.test(v) ||
              this.$vuetify.lang.t('$vuetify.rule.bad_email', [
                this.$vuetify.lang.t('$vuetify.email')
              ])
        ],
        password: [
          (v) =>
            !!v ||
              this.$vuetify.lang.t('$vuetify.rule.required', [
                this.$vuetify.lang.t('$vuetify.password')
              ]),
          (v) =>
            (v || '').length >= 8 ||
              this.$vuetify.lang.t('$vuetify.rule.min', ['8'])
        ]
      }
    }
  },
  computed: {
    ...mapState('auth', ['isLoggedIn', 'fromModel', 'socialIcons']),
    ...mapGetters(['errors'])
  },
  methods: {
    ...mapActions('auth', ['sendLoginRequest']),
    login () {
      if (this.$refs.form.validate()) {
        this.loading = true
        this.sendLoginRequest(this.fromModel).then(() => {
          if (this.isLoggedIn) {
            this.loading = false
            this.$router.push('/dashboard')
          } else {
            this.loading = false
          }
        })
      }
    },
    handleSocialLogin () {
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
