<template>
  <v-dialog
    v-model="toogleNewModal"
    max-width="600px"
    persistent
  >
    <v-card>
      <v-card-title>
        <span class="headline">{{
          $vuetify.lang.t("$vuetify.titles.new", [
            $vuetify.lang.t("$vuetify.menu.client")
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
                v-model="newClient.firstName"
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
                v-model="newClient.lastName"
                :label="$vuetify.lang.t('$vuetify.lastName')"
                required
              />
            </v-col>
            <v-col
              cols="12"
              md="5"
            >
              <v-text-field
                v-model="newClient.email"
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
                v-model="newClient.phone"
                :placeholder="
                  $vuetify.lang.t('$vuetify.phone_holder')
                "
                :label="$vuetify.lang.t('$vuetify.phone')"
                required
                :rules="formRule.phone"
                :select-label="
                  $vuetify.lang.t('$vuetify.country')
                "
                v-bind="bindProps"
                :error-messages="errorPhone"
                :prefix="
                  countrySelect
                    ? `+` + countrySelect.dialCode
                    : ``
                "
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
              md="4"
            >
              <v-text-field
                v-model="newClient.province"
                :label="$vuetify.lang.t('$vuetify.province')"
              />
            </v-col>
            <v-col
              cols="12"
              md="4"
            >
              <v-text-field
                v-model="newClient.city"
                :label="$vuetify.lang.t('$vuetify.city')"
              />
            </v-col>
            <v-col
              cols="12"
              md="4"
            >
              <v-text-field-simplemask
                v-model="newClient.barCode"
                :label="$vuetify.lang.t('$vuetify.barCode')"
                :properties="{
                  clearable: true,
                  required: true,
                  rules: formRule.required
                }"
                :options="{
                  inputMask: '##-####-####-###',
                  outputMask: '#############',
                  empty: null,
                  alphanumeric: true
                }"
                :focus="focus"
                @focus="focus = false"
              />
            </v-col>
            <v-col>
              <v-text-field
                v-model="newClient.address"
                :counter="120"
                :rules="formRule.address"
                :label="$vuetify.lang.t('$vuetify.address')"
                required
              />
            </v-col>
            <v-col>
              <v-text-field
                v-model="newClient.description"
                :counter="120"
                :label="
                  $vuetify.lang.t(
                    '$vuetify.access.description'
                  )
                "
              />
            </v-col>
          </v-row>
        </v-form>
      </v-card-text>
      <v-card-actions>
        <v-spacer />
        <v-btn
          class="mb-2"
          :disabled="isActionInProgress"
          @click="toogleNewModal(false)"
        >
          <v-icon>mdi-close</v-icon>
          {{ $vuetify.lang.t("$vuetify.actions.cancel") }}
        </v-btn>
        <v-btn
          :disabled="!formValid || isActionInProgress"
          :loading="isActionInProgress"
          class="mb-2"
          color="primary"
          @click="createNewClient"
        >
          <v-icon>mdi-content-save</v-icon>
          {{ $vuetify.lang.t("$vuetify.actions.save") }}
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import { mapActions, mapState } from 'vuex'

export default {
  name: 'NewClient',
  data () {
    return {
      formValid: false,
      hidePinCode1: true,
      hidePinCode2: true,
      errorPhone: null,
      formRule: this.$rules,
      countrySelect: null,
      focus: false
    }
  },
  computed: {
    ...mapState('client', ['saved', 'newClient', 'isActionInProgress']),
    getAvatar () {
      return `${this.newClient.avatar ||
                '/assets/avatar/avatar-undefined.jpg'}`
    },
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
    this.formValid = false
  },
  methods: {
    ...mapActions('client', ['createClient', 'toogleNewModal']),
    onCountry (event) {
      this.newClient.country = event.iso2
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
      this.newClient.avatar = `data:${file.type};base64,${file.base64}`
    },
    onInput (number, object) {
      const lang = this.$vuetify.lang
      if (object.valid) {
        this.newClient.phone = number.replace(' ', '')
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
    async createNewClient () {
      if (this.$refs.form.validate()) {
        this.loading = true
        await this.createClient(this.newClient)
      }
    }
  }
}
</script>

<style scoped></style>
