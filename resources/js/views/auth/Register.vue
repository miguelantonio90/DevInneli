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
              {{ $vuetify.lang.t('$vuetify.register') }}
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
                v-model="formRegister.shopName"
                :label="$vuetify.lang.t('$vuetify.company')"
                :rules="formRule.firstName"
                append-icon="mdi-account"
                autocomplete="off"
                name="register"
                required
                type="text"
              />
              <v-text-field
                v-model="formRegister.email"
                :label="$vuetify.lang.t('$vuetify.email')"
                :rules="formRule.email"
                append-icon="mdi-email"
                autocomplete="off"
                name="register"
                required
                type="email"
              />
              <v-text-field
                v-model="formRegister.password"
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
                v-model="formRegister.password_confirmation"
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
              <v-autocomplete
                v-model="formRegister.country"
                :items="arrayCountry"
                :label="
                  $vuetify.lang.t('$vuetify.country')
                "
                :rules="formRule.country"
                clearable
                item-text="name"
                item-value="id"
                required
              >
                <template
                  slot="item"
                  slot-scope="data"
                >
                  <template
                    v-if="
                      typeof data.item !==
                        'object'
                    "
                  >
                    <v-list-item-content
                      v-text="data.item"
                    />
                  </template>
                  <template v-else>
                    <v-list-item-avatar>
                      {{
                        data.item.emoji
                      }}
                    </v-list-item-avatar>
                    <v-list-item-content>
                      <v-list-item-title>{{ data.item.name }}</v-list-item-title>
                    </v-list-item-content>
                  </template>
                </template>
              </v-autocomplete>
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
              @click="registerUser"
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
  name: 'Register',
  data () {
    return {
      loading: false,
      formValid: false,
      hidePassword1: true,
      hidePassword2: true,
      formRule: {
        firstName: [
          (v) => !!v || this.$vuetify.lang.t('$vuetify.rule.required', [
            this.$vuetify.lang.t('$vuetify.name')
          ])
        ],
        email: [
          (v) => !!v || this.$vuetify.lang.t('$vuetify.rule.required', [
            this.$vuetify.lang.t('$vuetify.email')
          ]),
          (v) => /.+@.+\..+/.test(v) ||
                        this.$vuetify.lang.t('$vuetify.rule.bad_email', [
                          this.$vuetify.lang.t('$vuetify.email')
                        ])
        ],
        password: [
          (v) => !!v || this.$vuetify.lang.t('$vuetify.rule.required', [
            this.$vuetify.lang.t('$vuetify.password')
          ]),
          (v) => (v || '').length >= 8 || this.$vuetify.lang.t('$vuetify.rule.min', ['8'])
        ],
        password_confirmation: [
          (v) => !!v || this.$vuetify.lang.t('$vuetify.rule.required', [
            this.$vuetify.lang.t('$vuetify.confirm_password')
          ]),
          (v) => (!!v && v) === this.formRegister.password ||
                        this.$vuetify.lang.t(
                          '$vuetify.rule.match',
                          [this.$vuetify.lang.t('$vuetify.password')],
                          [this.$vuetify.lang.t('$vuetify.confirm_password')]
                        )
        ],
        country: [
          v => !!v || this.$vuetify.lang.t('$vuetify.rule.select')
        ]
      }
    }
  },
  computed: {
    ...mapState('auth', ['isLoggedIn', 'formRegister']),
    ...mapGetters(['errors']),
    ...mapState('statics', ['arrayCountry']),
    prefix () {
      return ''
    }
  },
  methods: {
    ...mapActions('auth', ['sendRegisterRequest']),
    registerUser () {
      if (this.$refs.form.validate()) {
        this.loading = true
        setTimeout(() => {
          this.sendRegisterRequest(this.formRegister).then(() => {
            if (this.isLoggedIn) {
              this.loading = false
              this.$router.push('/hi')
            } else {
              this.loading = false
            }
          })
        }, 1000)
      }
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
