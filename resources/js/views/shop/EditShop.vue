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
            $vuetify.lang.t('$vuetify.menu.shop')
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
                v-model="editShop.name"
                :counter="10"
                :label="$vuetify.lang.t('$vuetify.menu.shop')"
                :rules="formRule.shop"
                prepend-icon="mdi-home-variant"
                required
                @keypress="lettersNumbers"
              />
            </v-col>
            <v-col>
              <vue-tel-input-vuetify
                v-model="editShop.phone"
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
          </v-row>
          <v-row>
            <v-col
              cols="12"
              md="12"
            >
              <v-text-field
                v-model="editShop.address"
                :counter="120"
                :label="$vuetify.lang.t('$vuetify.address')"
                :rules="formRule.address"
                prepend-icon="mdi-home-map-marker"
                required
              />
            </v-col>
            <v-col
              cols="12"
              md="12"
            >
              <v-text-field
                v-model="editShop.description"
                :counter="120"
                :label="
                  $vuetify.lang.t(
                    '$vuetify.access.description'
                  )
                "
                :rules="formRule.description"
                prepend-icon="mdi-file-document"
                required
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
          :disabled="!formValid || isActionInProgress"
          :loading="isActionInProgress"
          class="mb-2"
          color="primary"
          @click="editShopAction"
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
      errorPhone: null,
      formValid: false,
      formRule: this.$rules,
      countrySelect: null
    }
  },
  computed: {
    ...mapState('shop', ['saved', 'editShop', 'isActionInProgress']),
    bindProps () {
      return {
        mode: 'national',
        clearable: true,
        defaultCountry: this.editShop.country
          ? this.editShop.country
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
  methods: {
    ...mapActions('shop', ['updateShop', 'toogleEditModal']),
    onInput (number, object) {
      const lang = this.$vuetify.lang
      if (object.valid) {
        this.editShop.phone = number
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
      this.editShop.country = event.iso2
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
    async editShopAction () {
      if (this.$refs.form.validate()) {
        this.loading = true
        await this.updateShop(this.editShop)
      }
    }
  }
}
</script>

<style scoped></style>
