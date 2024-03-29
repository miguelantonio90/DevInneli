<template>
  <v-dialog
    v-model="toogleEditModal"
    max-width="600px"
    persistent
  >
    <v-card>
      <v-card-title>
        <span class="headline">{{
          $vuetify.lang.t('$vuetify.titles.edit', [
            $vuetify.lang.t('$vuetify.menu.user')
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
                :image-style="{
                  'border-radius': '50%',
                  height: '80px',
                  width: '80px'
                }"
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
                :disabled="editUser.isManager === 1"
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
                v-model="editUser.phone"
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
            </v-col>
            <v-col
              cols="12"
              md="6"
            >
              <v-text-field-simplemask
                v-model="editUser.pinCode"
                :label="$vuetify.lang.t('$vuetify.pinCode')"
                :options="{
                  inputMask: '#-#-#-#-#-#',
                  outputMask: '######',
                  empty: null,
                  alphanumeric: false
                }"
                :properties="{
                  clearable: true,
                  required: true,
                  rules: formRule.pinCode
                }"
              />
            </v-col>
            <v-col
              cols="12"
              md="6"
            >
              <v-select
                v-model="editUser.position"
                :disabled="!!isAccessLoading || editUser.position.name === 'CEO'"
                :items="roles"
                :label="$vuetify.lang.t('$vuetify.menu.access')"
                :loading="isAccessLoading"
                :rules="formRule.access"
                item-text="name"
                required
                return-object
              >
                <template v-slot:append-outer>
                  <v-tooltip bottom>
                    <template
                      v-slot:activator="{ on, attrs }"
                    >
                      <v-icon
                        v-bind="attrs"
                        v-on="on"
                        @click="
                          $store.dispatch(
                            'role/toogleNewModal',
                            true
                          )
                        "
                      >
                        mdi-plus
                      </v-icon>
                    </template>
                    <span>{{
                      $vuetify.lang.t(
                        '$vuetify.titles.newAction'
                      )
                    }}</span>
                  </v-tooltip>
                </template>
              </v-select>
            </v-col>
            <v-col
              cols="12"
              md="12"
            >
              <v-select
                v-model="editUser.shops"
                :disabled="!!isShopLoading || editUser.position.name === 'CEO'"
                :items="shops"
                :label="$vuetify.lang.t('$vuetify.menu.shop')"
                :loading="isShopLoading"
                item-text="name"
                multiple
                required
                return-object
                :rules="formRule.country"
              />
            </v-col>
          </v-row>
        </v-form>
      </v-card-text>
      <v-card-actions>
        <v-spacer />
        <v-btn
          :disabled="isActionInProgress"
          class="mb-2"
          @click="toogleEditModal(false)"
        >
          <v-icon>mdi-close</v-icon>
          {{ $vuetify.lang.t('$vuetify.actions.cancel') }}
        </v-btn>
        <v-btn
          :disabled="!formValid || isActionInProgress || editUser.shops.length === 0"
          :loading="isActionInProgress"
          class="mb-2"
          color="primary"
          @click="updateUserHandler"
        >
          <v-icon>mdi-content-save</v-icon>
          {{ $vuetify.lang.t('$vuetify.actions.save') }}
        </v-btn>
      </v-card-actions>
      <new-access v-if="$store.state.role.showNewModal" />
      <new-shop v-if="$store.state.shop.showNewModal" />
    </v-card>
  </v-dialog>
</template>

<script>
import { mapActions, mapState } from 'vuex'
import NewAccess from '../access/NewAccess'
import NewShop from '../shop/NewShop'

export default {
  name: 'EditUser',
  components: {
    NewAccess,
    NewShop
  },
  data () {
    return {
      formValid: false,
      hidePinCode1: true,
      hidePinCode2: true,
      errorPhone: null,
      formRule: this.$rules,
      countrySelect: null
    }
  },
  computed: {
    ...mapState('user', ['saved', 'editUser', 'isActionInProgress']),
    ...mapState('role', ['roles', 'isAccessLoading']),
    ...mapState('shop', ['shops', 'isShopLoading']),
    getAvatar () {
      return `${this.editUser.avatar ||
      '/assets/avatar/avatar-undefined.jpg'}`
    },
    bindProps () {
      return {
        mode: 'national',
        clearable: true,
        defaultCountry: this.editUser.country
          ? this.editUser.country
          : 'US',
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
      this.editUser.country = event.iso2
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
    onChangeImage (file) {
      this.editUser.avatar = `data:${file.type};base64,${file.base64}`
    },
    onInput (number, object) {
      const lang = this.$vuetify.lang
      if (object.valid) {
        this.editUser.phone = number.replace(' ', '')
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
        await this.updateUser(this.editUser)
      }
    }
  }
}
</script>

<style scoped></style>
