<template>
  <v-dialog
    v-model="toogleNewModal"
    max-width="600px"
  >
    <v-card>
      <v-card-title>
        <span class="headline">{{
          $vuetify.lang.t('$vuetify.titles.new', [
            $vuetify.lang.t('$vuetify.user.user'),
          ])
        }}</span>
      </v-card-title>
      <v-card-text>
        <v-form
          ref="form"
          v-model="formValid"
          class="my-10"
          lazy-validation
        >
          <v-row>
            <v-col
              cols="12"
              md="4"
            >
              <v-text-field
                v-model="newUser.firstName"
                :counter="10"
                :label="$vuetify.lang.t('$vuetify.firstName')"
                :rules="formRule.firstName"
                required
                @keypress="letters"
              />
            </v-col>
            <v-col
              cols="12"
              md="4"
            >
              <v-text-field
                v-model="newUser.lastName"
                :counter="10"
                :label="$vuetify.lang.t('$vuetify.lastName')"
                required
                @keypress="letters"
              />
            </v-col>
            <v-col
              cols="12"
              md="4"
            >
              <v-text-field
                v-model="newUser.company"
                :counter="25"
                :label="$vuetify.lang.t('$vuetify.company')"
                :rules="formRule.company"
                @keypress="lettersNumbers"
              />
            </v-col>
            <v-col
              cols="12"
              md="4"
            >
              <v-text-field
                v-model="newUser.email"
                :label="$vuetify.lang.t('$vuetify.email')"
                :rules="formRule.email"
                autocomplete="off"
                required
              />
            </v-col>
            <v-col
              cols="12"
              md="4"
            >
              <v-text-field
                v-model="newUser.username"
                :counter="8"
                :label="$vuetify.lang.t('$vuetify.username')"
                autocomplete="off"
                required
                value=""
                @keypress="lettersNumbers"
              />
            </v-col>
            <v-col
              cols="12"
              md="4"
            >
              <v-text-field
                v-model="newUser.password"
                :label="$vuetify.lang.t('$vuetify.password')"
                :rules="formRule.password"
                autocomplete="off"
                name="password"
                required
                type="password"
              />
            </v-col>
          </v-row>
          <v-row>
            <v-col
              cols="12"
              md="4"
            >
              <v-autocomplete
                v-model="newUser.country"
                :items="arrayCountry"
                :label="
                  $vuetify.lang.t('$vuetify.country')
                "
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
            </v-col>
            <v-col
              cols="12"
              md="4"
            >
              <v-text-field
                v-model="newUser.phone"
                :label="$vuetify.lang.t('$vuetify.phone')"
                autocomplete="off"
                class="hiddenSpinner"
                name="phone"
                required
                @keypress="numbers"
              />
            </v-col>
          </v-row>
          <v-row>
            <v-col
              cols="12"
              md="12"
            >
              <v-text-field
                v-model="newUser.address"
                :label="$vuetify.lang.t('$vuetify.address')"
                required
              />
            </v-col>
            <v-col
              cols="12"
              md="12"
            >
              <v-text-field
                v-model="newUser.aboutMe"
                :label="$vuetify.lang.t('$vuetify.about_me')"
                required
              />
            </v-col>
          </v-row>
        </v-form>
      </v-card-text>
      <v-card-actions>
        <v-spacer />
        <v-btn
          class="mb-2"
          color="error"
          @click="toogleNewModal(false)"
        >
          <v-icon>mdi-close</v-icon>
          {{ $vuetify.lang.t('$vuetify.actions.cancel') }}
        </v-btn>
        <v-btn
          :disabled="!formValid"
          class="mb-2"
          color="primary"
          @click="createNewUser"
        >
          <v-icon>mdi-check</v-icon>
          {{ $vuetify.lang.t('$vuetify.actions.save') }}
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import { mapActions, mapState } from 'vuex'

export default {
  name: 'NewUser',
  data () {
    return {
      formValid: false,
      password_confirmation: '',
      formRule: {
        firstName: [
          (v) =>
            !!v ||
              this.$vuetify.lang.t('$vuetify.rule.required', [
                this.$vuetify.lang.t('$vuetify.name')
              ])
        ],
        company: [
          (v) =>
            !!v ||
              this.$vuetify.lang.t('$vuetify.rule.required', [
                this.$vuetify.lang.t('$vuetify.company')
              ])
        ],
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
        ],
        password_confirmation: [
          (v) =>
            !!v ||
              this.$vuetify.lang.t('$vuetify.rule.required', [
                this.$vuetify.lang.t('$vuetify.confirm_password')
              ]),
          (v) =>
            (!!v && v) === this.newUser.password ||
              this.$vuetify.lang.t(
                '$vuetify.rule.match',
                [this.$vuetify.lang.t('$vuetify.password')],
                [this.$vuetify.lang.t('$vuetify.confirm_password')]
              )
        ]
      }
    }
  },
  computed: {
    ...mapState('statics', ['arrayCountry']),
    ...mapState('user', ['saved', 'newUser'])
  },
  mounted () {
    this.formValid = false
  },
  methods: {
    ...mapActions('user', ['createUser', 'toogleNewModal']),
    changePhone (e) {
      console.log(e)
    },
    letters (event) {
      const regex = new RegExp('^[A-Za-z ]+$')
      const key = String.fromCharCode(
        !event.charCode ? event.which : event.charCode
      )
      if (!regex.test(key)) {
        event.preventDefault()
        return false
      }
    },
    lettersNumbers (event) {
      const regex = new RegExp('^[a-zA-Z0-9]+$')
      const key = String.fromCharCode(
        !event.charCode ? event.which : event.charCode
      )
      if (!regex.test(key)) {
        event.preventDefault()
        return false
      }
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
    onCountry (digit) {
      this.newUser.country = this.arrayCountry.filter(
        (c) => c.code === digit
      )[0].name
      console.log(this.newUser.country)
    },
    async createNewUser () {
      if (this.$refs.form.validate()) {
        this.loading = true
        await this.createUser(this.newUser).then(() => {
          if (this.saved) {
            this.loading = false
            const msg = this.$vuetify.lang.t(
              '$vuetify.messages.success_profile'
            )
            this.$Toast.fire({
              icon: 'success',
              title: msg
            })
          }
        })
      }
    }
  }
}
</script>

<style scoped>
.hiddenSpinner input[type='number'] {
  -moz-appearance: textfield;
}

.hiddenSpinner input::-webkit-outer-spin-button,
.hiddenSpinner input::-webkit-inner-spin-button {
  -webkit-appearance: none;
}
</style>
