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
              src="/assets/logo-new.png"
            >
          </v-card-title>
          <v-card-text>
            <div class="sm12 text-center">
              <h3 class="primary--text">
                {{ $vuetify.lang.t('$vuetify.welcome_login') }}
              </h3>
            </div>
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
              <router-link
                v-slot="{ href, navigate }"
                :to="{ name: 'forgot' }"
              >
                <div class="sm12">
                  <div>
                    <a
                      :href="href"
                      class="text-info m-l-5"
                      style="text-decoration: none"
                      @click="navigate"
                    >{{ $vuetify.lang.t('$vuetify.forgot') }}</a>
                  </div>
                </div>
              </router-link>
            </v-form>
          </v-card-text>
          <v-card-actions>
            <!--<v-tooltip
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
            </v-tooltip>-->
            <v-btn
              :disabled="!formValid || pending"
              :loading="pending"
              block
              color="primary"
              @click="login"
            >
              <v-icon>mdi-login</v-icon>
              {{ $vuetify.lang.t('$vuetify.login') }}
            </v-btn>
          </v-card-actions>
          <router-link
            v-slot="{ href, navigate }"
            :to="{ name: 'register' }"
          >
            <div class="sm12 text-center">
              <div>
                {{ $vuetify.lang.t('$vuetify.no_account') }} <a
                  :href="href"
                  class="text-info m-l-5"
                  style="text-decoration: none"
                  @click="navigate"
                ><b>{{ $vuetify.lang.t('$vuetify.register') }}</b></a>
              </div>
            </div>
          </router-link>
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
      hidePassword: true,
      formRule: this.$rules
    }
  },
  computed: {
    ...mapState('auth', ['isLoggedIn', 'fromModel', 'socialIcons', 'pending']),
    ...mapGetters(['errors'])
  },
  created () {
    window.addEventListener('keypress', e => {
      if (e.key === 'Enter') {
        this.login()
      }
    })
  },
  methods: {
    ...mapActions('auth', ['sendLoginRequest']),
    ...mapActions('company', ['getCompaniesByEmail']),
    login () {
      if (this.$refs.form.validate()) {
        this.sendLoginRequest(this.fromModel).then(() => {
          if (this.isLoggedIn) {
            this.$router.push({ name: 'pinlogin', params: { email: this.fromModel.email } })
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
    max-width: 450px
    margin: 0 auto
    border-radius: 6px !important
</style>
