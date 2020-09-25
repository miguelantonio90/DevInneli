<template>
  <v-dialog
    v-model="toogleNewModal"
    max-width="600px"
  >
    <v-card>
      <v-card-title>
        <span class="headline">{{
          $vuetify.lang.t('$vuetify.titles.new', [
            $vuetify.lang.t('$vuetify.menu.user'),
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
              align-self="start"
              class="pa-0"
              cols="2"
            >
              <avatar-picker
                :image-src="getAvatar"
                :image-style="{ 'border-radius': '50%','height':'80px','width':'80px' }"
                class="profile mx-auto d-block"
                @input="onChangeImage($event)"
              />
            </v-col>
            <v-col
              cols="12"
              md="5"
            >
              <v-text-field
                v-model="newUser.firstName"
                :label="$vuetify.lang.t('$vuetify.firstName')"
                :rules="formRule.firstName"
                required
              />
            </v-col>
            <v-col
              cols="12"
              md="5"
            >
              <v-text-field
                v-model="newUser.lastName"
                :label="$vuetify.lang.t('$vuetify.lastName')"
                required
              />
            </v-col>
            <v-col
              cols="12"
              md="5"
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
              md="7"
            >
              <vue-tel-input-vuetify
                v-model="newUser.phone"
                :placeholder="$vuetify.lang.t('$vuetify.phone_holder')"
                :label="$vuetify.lang.t('$vuetify.phone')"
                required
                :select-label="$vuetify.lang.t('$vuetify.country')"
                v-bind="bindProps"
                :error-messages="errorPhone"
                @keypress="numbers"
                @input="onInput"
              >
                <template #message="{ key, message }">
                  <slot
                    name="label"
                    v-bind="{ key, message }"
                  />
                  {{ message }}
                </template>
              </vue-tel-input-vuetify>
            </v-col>
            <v-col
              cols="12"
              md="6"
            >
              <v-text-field
                v-model="newUser.pinCode"
                :append-icon=" hidePinCode1 ? 'mdi-eye' : 'mdi-eye-off'"
                :label="$vuetify.lang.t('$vuetify.pinCode')"
                :rules="formRule.pinCode"
                :type="hidePinCode1 ? 'password' : 'number'"
                autocomplete="off"
                name="pinCode"
                required
                @keypress="numbers"
                @click:append="hidePinCode1 = !hidePinCode1"
              />
            </v-col>
            <v-col
              cols="12"
              md="6"
            >
              <v-text-field
                v-model="newUser.confirm_pinCode"
                :append-icon="hidePinCode2 ? 'mdi-eye' : 'mdi-eye-off'"
                :label="$vuetify.lang.t('$vuetify.confirm_pinCode')"
                :rules="formRule.confirm_pinCode"
                :type="hidePinCode2 ? 'password' : 'number'"
                autocomplete="off"
                name="confirm_pinCode"
                required
                @keypress="numbers"
                @click:append="hidePinCode2 = !hidePinCode2"
              />
            </v-col>
            <v-col
              cols="12"
              md="6"
            >
              <v-select
                v-model="newUser.positions"
                :items="roles"
                :label="$vuetify.lang.t('$vuetify.menu.access')"
                item-text="name"
                :loading="isAccessLoading"
                :disabled="!!isAccessLoading"
                return-object
              />
            </v-col>
            <v-col
              cols="12"
              md="6"
            >
              <v-select
                v-model="newUser.shops"
                :items="shops"
                :label="$vuetify.lang.t('$vuetify.menu.shop')"
                item-text="name"
                :loading="isShopLoading"
                :disabled="!!isShopLoading"
                multiple
                return-object
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
      hidePinCode1: true,
      hidePinCode2: true,
      errorPhone: null,
      formRule: {
        firstName: [
          (v) =>
            !!v ||
              this.$vuetify.lang.t('$vuetify.rule.required', [
                this.$vuetify.lang.t('$vuetify.name')
              ])
        ],
        email: [
          (v) =>
            !!v ||
              this.$vuetify.lang.t('$vuetify.rule.required', [
                this.$vuetify.lang.t('$vuetify.email')
              ]),
          (v) =>
            /.+@.+\..+/.test(v) ||
              this.$vuetify.lang.t('$vuetify.rule.bad_email', [
                this.$vuetify.lang.t('$vuetify.email')
              ])
        ],
        pinCode: [
          (v) =>
            !!v || this.$vuetify.lang.t('$vuetify.rule.required', [
              this.$vuetify.lang.t('$vuetify.pinCode')
            ]),
          (v) => (v && v.length >= 4) || this.$vuetify.lang.t('$vuetify.rule.pin.min', ['4']),
          (v) => (v && v.length <= 6) || this.$vuetify.lang.t('$vuetify.rule.pin.max', ['6'])
        ],
        confirm_pinCode: [
          (v) =>
            !!v ||
              this.$vuetify.lang.t('$vuetify.rule.required', [
                this.$vuetify.lang.t('$vuetify.confirm_pinCode')
              ]),
          (v) =>
            (!!v && v) === this.newUser.pinCode ||
              this.$vuetify.lang.t(
                '$vuetify.rule.match',
                [this.$vuetify.lang.t('$vuetify.pinCode')],
                [this.$vuetify.lang.t('$vuetify.confirm_pinCode')]
              )
        ]
      }
    }
  },
  computed: {
    ...mapState('user', ['saved', 'newUser']),
    ...mapState('role', ['roles', 'isAccessLoading']),
    ...mapState('shop', ['shops', 'isShopLoading']),
    getAvatar () {
      return `${this.newUser.avatar ||
          '/assets/avatar/avatar-undefined.jpg'}`
    },
    bindProps () {
      return {
        mode: 'international',
        defaultCountry: 'US',
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
    this.formValid = false
    this.getRoles()
    this.getShops()
  },
  methods: {
    ...mapActions('user', ['createUser', 'toogleNewModal']),
    ...mapActions('role', ['getRoles']),
    ...mapActions('shop', ['getShops']),
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
    onChangeImage (file) {
      this.newUser.avatar = `data:${file.type};base64,${file.base64}`
    },
    onInput (number, object) {
      const lang = this.$vuetify.lang
      if (object.valid) {
        this.newUser.phone = number
        this.errorPhone = null
      } else {
        this.errorPhone = lang.t('$vuetify.rule.bad_phone', [
          lang.t('$vuetify.phone')
        ])
      }
    },
    lettersNumbers (event) {
      const regex = new RegExp('^[a-zA-Z0-9 ]+$')
      const key = String.fromCharCode(
        !event.charCode ? event.which : event.charCode
      )
      if (!regex.test(key)) {
        event.preventDefault()
        return false
      }
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
</style>
