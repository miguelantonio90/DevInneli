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
            $vuetify.lang.t('$vuetify.menu.supplier'),
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
              md="6"
            >
              <v-text-field
                v-model="editSupplier.name"
                :label="$vuetify.lang.t('$vuetify.firstName')"
                :rules="formRule.firstName"
                required
              />
            </v-col>
            <v-col
              cols="12"
              md="6"
            >
              <v-text-field
                v-model="editSupplier.identity"
                :label="$vuetify.lang.t('$vuetify.supplier.identity')"
                :rules="formRule.identity"
                required
              />
            </v-col>
            <v-col
              cols="12"
              md="5"
            >
              <v-text-field
                v-model="editSupplier.email"
                :label="$vuetify.lang.t('$vuetify.supplier.email')"
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
                v-model="editSupplier.phone"
                :placeholder="$vuetify.lang.t('$vuetify.phone_holder')"
                :label="$vuetify.lang.t('$vuetify.supplier.phone')"
                required
                :rules="formRule.phone"
                :select-label="$vuetify.lang.t('$vuetify.supplier.country')"
                v-bind="bindProps"
                :error-messages="errorPhone"
                :prefix="countrySelect ?`+`+countrySelect.dialCode:``"
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
                v-model="editSupplier.contract"
                :counter="120"
                :rules="formRule.contract"
                :label="$vuetify.lang.t('$vuetify.supplier.contract')"
                required
              />
            </v-col>
            <v-col
              cols="12"
              md="6"
            >
              <v-select
                v-model="editSupplier.expanse"
                :items="categories"
                clearable
                :loading="isCategoryLoading"
                :disabled="!!isCategoryLoading"
                item-text="name"
                item-value="id"
                :label="$vuetify.lang.t('$vuetify.supplier.expense')"
              >
                <template v-slot:append-outer>
                  <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                      <v-icon
                        v-bind="attrs"
                        v-on="on"
                        @click="$store.dispatch('expenseCategory/toogleNewModal',true)"
                      >
                        mdi-plus
                      </v-icon>
                    </template>
                    <span>{{ $vuetify.lang.t('$vuetify.titles.newAction') }}</span>
                  </v-tooltip>
                </template>
              </v-select>
            </v-col>
            <v-col
              cols="12"
              md="12"
            >
              <v-text-field
                v-model="editSupplier.address"
                :counter="120"
                :rules="formRule.address"
                :label="$vuetify.lang.t('$vuetify.supplier.address')"
                required
              />
            </v-col>
            <v-col
              cols="12"
              md="12"
            >
              <v-text-field
                v-model="editSupplier.note"
                :counter="120"
                :label="$vuetify.lang.t('$vuetify.supplier.note')"
              />
            </v-col>
          </v-row>
        </v-form>
      </v-card-text>
      <v-card-actions>
        <v-spacer />
        <v-btn
          class="mb-2"
          @click="toogleNewModal(false)"
        >
          <v-icon>mdi-close</v-icon>
          {{ $vuetify.lang.t('$vuetify.actions.cancel') }}
        </v-btn>
        <v-btn
          :disabled="!formValid"
          :loading="isActionInProgress"
          class="mb-2"
          color="primary"
          @click="updateSupplierHandler"
        >
          <v-icon>mdi-content-save</v-icon>
          {{ $vuetify.lang.t('$vuetify.actions.save') }}
        </v-btn>
      </v-card-actions>
      <new-expense-category v-if="$store.state.expenseCategory.showNewModal" />
    </v-card>
  </v-dialog>
</template>

<script>
import { mapActions, mapState } from 'vuex'
import NewExpenseCategory from '../expense_category/New'

export default {
  components: { NewExpenseCategory },
  data () {
    return {
      formValid: false,
      errorPhone: null,
      formRule: this.$rules,
      countrySelect: null
    }
  },
  computed: {
    ...mapState('supplier', ['saved', 'editSupplier', 'isActionInProgress']),
    ...mapState('expenseCategory', ['saved', 'categories', 'isCategoryLoading']),
    bindProps () {
      return {
        mode: 'national',
        clearable: true,
        defaultCountry: this.editSupplier.country ? this.editSupplier.country : 'US',
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
    this.getExpenseCategories()
  },
  methods: {
    ...mapActions('supplier', ['updateSupplier', 'toogleEditModal']),
    ...mapActions('expenseCategory', ['getExpenseCategories']),
    onCountry (event) {
      this.editSupplier.country = event.iso2
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
        this.editSupplier.phone = number
        this.errorPhone = null
      } else {
        this.errorPhone = lang.t('$vuetify.rule.bad_phone', [
          lang.t('$vuetify.phone')
        ])
      }
    },
    async updateSupplierHandler () {
      if (this.$refs.form.validate()) {
        this.loading = true
        await this.updateSupplier(this.editSupplier)
      }
    }
  }
}
</script>

<style scoped></style>
