<template>
  <v-dialog
    v-model="toogleEditModal"
    max-width="600px"
  >
    <v-card>
      <v-card-title>
        <span class="headline">{{
          $vuetify.lang.t('$vuetify.titles.edit', [
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
                v-model="editUser.firstName"
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
                v-model="editUser.lastName"
                :label="$vuetify.lang.t('$vuetify.lastName')"
                required
              />
            </v-col>
            <v-col
              cols="12"
              md="5"
            >
              <v-text-field
                v-model="editUser.email"
                :label="$vuetify.lang.t('$vuetify.email')"
                :rules="formRule.email"
                :disabled="editUser.position.key==='manager'"
                autocomplete="off"
                required
              />
            </v-col>
            <v-col
              cols="12"
              md="7"
            >
              <vue-tel-input-vuetify
                v-model="editUser.phone"
                :placeholder="$vuetify.lang.t('$vuetify.phone_holder')"
                :label="$vuetify.lang.t('$vuetify.phone')"
                required
                :select-label="$vuetify.lang.t('$vuetify.country')"
                v-bind="bindProps"
                :error-messages="errorPhone"
                @country-changed="onCountry"
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
                v-model="editUser.pinCode"
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
                v-model="editUser.confirm_pinCode"
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
                v-model="editUser.position"
                :items="roles"
                :label="$vuetify.lang.t('$vuetify.menu.access')"
                item-text="name"
                :loading="isAccessLoading"
                :disabled="!!isAccessLoading || editUser.position.key==='manager'"
                multiple
                return-object
              />
            </v-col>
            <v-col
              cols="12"
              md="6"
            >
              <v-select
                v-model="editUser.shops"
                :items="shops"
                :label="$vuetify.lang.t('$vuetify.menu.shop')"
                item-text="name"
                return-object
                multiple
                :loading="isShopLoading"
                :disabled="!!isShopLoading || editUser.position.key==='manager'"
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
          @click="toogleEditModal(false)"
        >
          <v-icon>mdi-close</v-icon>
          {{ $vuetify.lang.t('$vuetify.actions.cancel') }}
        </v-btn>
        <v-btn
          :disabled="!formValid"
          class="mb-2"
          color="primary"
          @click="updateUserHandler"
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
  name: 'EditUser',
  data () {
    return {
      formValid: false,
      hidePinCode1: true,
      hidePinCode2: true,
      errorPhone: null,
      formRule: {
        firstName: [
          (v) => !!v || this.$vuetify.lang.t('$vuetify.rule.required', [
            this.$vuetify.lang.t('$vuetify.name')
          ])
        ],
        email: [
          (v) =>
            !!v || this.$vuetify.lang.t('$vuetify.rule.required', [
              this.$vuetify.lang.t('$vuetify.email')
            ]),
          (v) => /.+@.+\..+/.test(v) ||
                        this.$vuetify.lang.t('$vuetify.rule.bad_email', [
                          this.$vuetify.lang.t('$vuetify.email')
                        ])
        ],
        pinCode: [
          (v) => !!v || this.$vuetify.lang.t('$vuetify.rule.required', [
            this.$vuetify.lang.t('$vuetify.pinCode')
          ]),
          (v) => (v && v.length >= 4) || this.$vuetify.lang.t('$vuetify.rule.pin.min', ['4']),
          (v) => (v && v.length <= 6) || this.$vuetify.lang.t('$vuetify.rule.pin.max', ['6'])
        ],
        confirm_pinCode: [
          (v) => !!v || this.$vuetify.lang.t('$vuetify.rule.required', [
            this.$vuetify.lang.t('$vuetify.confirm_pinCode')
          ]),
          (v) => (!!v && v) === this.editUser.pinCode ||
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
    ...mapState('user', ['saved', 'editUser']),
    ...mapState('role', ['roles', 'isAccessLoading']),
    ...mapState('shop', ['shops', 'isShopLoading']),
    getAvatar () {
      return `${this.editUser.avatar ||
            '/assets/avatar/avatar-undefined.jpg'}`
    },
    bindProps () {
      return {
        mode: 'international',
        defaultCountry: this.editUser.country ? this.editUser.country : 'US',
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
    this.getRoles()
    this.getShops()
  },
  methods: {
    ...mapActions('user', ['updateUser', 'toogleEditModal']),
    ...mapActions('role', ['getRoles']),
    ...mapActions('shop', ['getShops']),
      onCountry (event) {
          this.editShop.country = event.iso2
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
    onChangeImage (file) {
      this.editUser.avatar = `data:${file.file.type};base64,${file.file.base64}`
    },
    onInput (number, object) {
      const lang = this.$vuetify.lang
      if (object.valid) {
        this.editUser.phone = number
        this.errorPhone = null
      } else {
        this.errorPhone = lang.t('$vuetify.rule.bad_phone', [
          lang.t('$vuetify.phone')
        ])
      }
    },
    async updateUserHandler () {
      if (this.$refs.form.validate()) {
        this.loading = true
        await this.updateUser(this.editUser).then(() => {
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

<style scoped></style>
