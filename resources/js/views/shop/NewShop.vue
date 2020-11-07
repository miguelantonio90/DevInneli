<template>
  <v-dialog
    v-model="toogleNewModal"
    max-width="600px"
    persistent
  >
    <v-card>
      <v-card-title>
        <span class="headline">{{
          $vuetify.lang.t('$vuetify.titles.newF', [
            $vuetify.lang.t('$vuetify.menu.shop'),
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
                v-model="newShop.name"
                prepend-icon="mdi-home-variant"
                :counter="10"
                :label="$vuetify.lang.t('$vuetify.menu.shop')"
                :rules="formRule.firstName"
                required
                @keypress="lettersNumbers"
              />
            </v-col>
            <v-col>
              <vue-tel-input-vuetify
                v-model="newShop.phone"
                :placeholder="$vuetify.lang.t('$vuetify.phone_holder')"
                :label="$vuetify.lang.t('$vuetify.phone')"
                required
                :select-label="$vuetify.lang.t('$vuetify.country')"
                v-bind="bindProps"
                :error-messages="errorPhone"
                :prefix="countrySelect ?`+`+countrySelect.dialCode:``"
                :rules="formRule.phone"
                @keypress="numbers"
                @country-changed="onCountry"
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
          </v-row>
          <v-row>
            <v-col
              cols="12"
              md="12"
            >
              <v-text-field
                v-model="newShop.address"
                prepend-icon="mdi-home-map-marker"
                :counter="120"
                :rules="formRule.address"
                :label="$vuetify.lang.t('$vuetify.address')"
                required
              />
            </v-col>
            <v-col
              cols="12"
              md="12"
            >
              <v-text-field
                v-model="newShop.description"
                prepend-icon="mdi-file-document"
                :counter="120"
                :rules="formRule.description"
                :label="$vuetify.lang.t('$vuetify.access.description')"
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
          :disabled="isActionInProgress"
          @click="toogleNewModal(false)"
        >
          <v-icon>mdi-close</v-icon>
          {{ $vuetify.lang.t('$vuetify.actions.cancel') }}
        </v-btn>
        <v-btn
          :disabled="!formValid || isActionInProgress"
          class="mb-2"
          color="primary"
          :loading="isActionInProgress"
          @click="createNewShopAction"
        >
          <v-icon>mdi-content-save</v-icon>
          {{ $vuetify.lang.t('$vuetify.actions.save') }}
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import { mapActions, mapState } from 'vuex'

export default {
  data () {
    return {
      formValid: false,
      errorPhone: null,
      formRule: this.$rules,
      countrySelect: null
    }
  },
  computed: {
    ...mapState('shop', ['saved', 'newShop', 'isActionInProgress']),
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
  watch: {
    toogleNewModal () {
      this.$refs.form.reset()
    }
  },
  mounted () {
    this.formValid = false
  },
  methods: {
    ...mapActions('shop', ['createShop', 'toogleNewModal']),
    onInput (number, object) {
      const lang = this.$vuetify.lang
      if (object.valid) {
        this.newShop.phone = number
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
    onCountry (event) {
      this.newShop.country = event.iso2
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
    async createNewShopAction () {
      if (this.$refs.form.validate()) {
        this.loading = true
        await this.createShop(this.newShop)
      }
    }
  }
}
</script>

<style scoped>
</style>
