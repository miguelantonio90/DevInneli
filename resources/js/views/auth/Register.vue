<template>
  <v-container
    class="page-register"
    fill-height
  >
    <v-row>
      <v-col>
        <v-card
          class="pa-3 page-register__card"
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
                :rules="passwordConfirmation"
                :type="hidePassword2 ? 'password' : 'text'"
                autocomplete="off"
                name="password_confirmation"
                required
                @click:append="hidePassword2 = !hidePassword2"
              />
              <vue-tel-input-vuetify
                v-model="formRegister.phone"
                v-bind="bindProps"
                :error-messages="errorPhone"
                :label="$vuetify.lang.t('$vuetify.phone')"
                :placeholder="
                  $vuetify.lang.t('$vuetify.phone_holder')
                "
                :prefix="
                  countrySelect
                    ? `+` + countrySelect.dialCode
                    : ``
                "
                :rules="formRule.phone"
                :select-label="
                  $vuetify.lang.t('$vuetify.country')
                "
                required
                @input="onInput"
                @keypress="numbers"
                @country-changed="onCountry"
              >
                <template #message="{ key, message }">
                  <slot
                    v-bind="{ key, message }"
                    name="label"
                  />
                  {{ message }}
                </template>
              </vue-tel-input-vuetify>
              <v-autocomplete
                v-model="formRegister.sector"
                :filter="customFilter"
                :items="arraySector"
                :label="$vuetify.lang.t('$vuetify.sector.name')"
                :rules="formRule.country"
                clearable
                item-value="value"
                required
              >
                <template v-slot:selection="data">
                  <v-chip
                    v-bind="data.attrs"
                    :input-value="data.item.value"
                    @click="data.select"
                  >
                    {{
                      $vuetify.lang.t(
                        '$vuetify.sector.' +
                          data.item.value
                      )
                    }}
                  </v-chip>
                </template>
                <template v-slot:item="data">
                  <template
                    v-if="typeof data.item !== 'object'"
                  >
                    <v-list-item-content
                      v-text="
                        $vuetify.lang.t(
                          '$vuetify.sector.' +
                            data.item.value
                        )
                      "
                    />
                  </template>
                  <template v-else>
                    <v-list-item-content>
                      <v-list-item-title>
                        {{
                          $vuetify.lang.t(
                            '$vuetify.sector.' +
                              data.item.value
                          )
                        }}
                      </v-list-item-title>
                    </v-list-item-content>
                  </template>
                </template>
              </v-autocomplete>
            </v-form>
          </v-card-text>
          <v-card-actions>
            <v-tooltip bottom>
              <template v-slot:activator="{ on, attrs }">
                <v-icon
                  v-bind="attrs"
                  class="mr-3"
                  v-on="on"
                  @click="$router.push({ name: 'login' })"
                >
                  mdi-chevron-left
                </v-icon>
              </template>
              <span>{{ $vuetify.lang.t('$vuetify.login') }}</span>
            </v-tooltip>
            <v-spacer />
            <v-btn
              :disabled="!formValid || loading"
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
      errorPhone: null,
      countrySelect: null,
      loading: false,
      formValid: false,
      hidePassword1: true,
      hidePassword2: true,
      formRule: this.$rules,
      passwordConfirmation: [
        v =>
          !!v ||
            this.$vuetify.lang.t('$vuetify.rule.required', [
              this.$vuetify.lang.t('$vuetify.confirm_password')
            ]),
        v =>
          (!!v && v) === this.formRegister.password ||
            this.$vuetify.lang.t(
              '$vuetify.rule.match',
              [this.$vuetify.lang.t('$vuetify.password')],
              [this.$vuetify.lang.t('$vuetify.confirm_password')]
            )
      ]
    }
  },
  computed: {
    ...mapState('auth', ['isLoggedIn', 'formRegister']),
    ...mapGetters(['errors']),
    ...mapState('statics', ['arrayCountry', 'arraySector']),
    bindProps () {
      return {
        mode: 'national',
        clearable: true,
        disabledFetchingCountry: false,
        autocomplete: 'off',
        dropdownOptions: {
          disabledDialCode: false
        },
        inputOptions: {
          showDialCode: false
        }
      }
    }
  },
  created () {
    if (this.$route.params.hash) {
      const data = JSON.parse(atob(this.$route.params.hash))
      if (data) {
        this.formRegister.shopName = data.name
        this.formRegister.email = data.email
      }
    }
  },
  methods: {
    ...mapActions('auth', ['sendRegisterRequest']),
    customFilter (item, queryText, itemText) {
      return (
        this.$vuetify.lang
          .t('$vuetify.sector.' + item.value)
          .toLowerCase()
          .indexOf(queryText.toLowerCase()) > -1
      )
    },
    onCountry (event) {
      this.formRegister.country = event.iso2
      this.countrySelect = event
    },
    numbers (event) {
      const regex = new RegExp('^[0-9]+$')
      const key = String.fromCharCode(
        !event.charCode ? event.which : event.charCode
      )
      if (!regex.test(key)) {
        event.preventDefault()
        return false
      }
    },
    onInput (number, object) {
      const lang = this.$vuetify.lang
      if (object.valid) {
        this.formRegister.phone = number
        this.errorPhone = null
      } else {
        this.errorPhone = lang.t('$vuetify.rule.bad_phone', [
          lang.t('$vuetify.phone')
        ])
      }
    },
    async registerUser () {
      if (this.$refs.form.validate()) {
        this.loading = true
        this.formRegister.country = this.arrayCountry.filter(
          count => count.id === this.countrySelect.iso2
        )[0]
        await setTimeout(() => {
          this.sendRegisterRequest(this.formRegister)
            .then(() => {
              this.loading = false
            })
            .catch(() => {
              this.loading = false
            })
        }, 1000)
      }
    }
  }
}
</script>

<style lang="sass" scoped>
.page-register
  &__card
    max-width: 450px
    margin: 0 auto
    border-radius: 6px !important
</style>
