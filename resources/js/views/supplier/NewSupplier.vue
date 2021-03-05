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
            $vuetify.lang.t("$vuetify.menu.supplier")
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
                v-model="newSupplier.name"
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
                v-model="newSupplier.email"
                :label="
                  $vuetify.lang.t('$vuetify.supplier.email')
                "
                :rules="formRule.email"
                autocomplete="off"
                required
              />
            </v-col>
            <v-col
              cols="12"
              md="6"
            >
              <v-text-field
                v-model="newSupplier.identity"
                :label="
                  $vuetify.lang.t(
                    '$vuetify.supplier.identity'
                  )
                "
                :rules="formRule.identity"
                required
              />
            </v-col>
            <v-col
              cols="12"
              md="6"
            >
              <v-text-field
                v-model="newSupplier.contract"
                :rules="formRule.contract"
                :label="
                  $vuetify.lang.t(
                    '$vuetify.supplier.contract'
                  )
                "
                required
              />
            </v-col>
            <v-col
              cols="12"
              md="6"
            >
              <vue-tel-input-vuetify
                v-model="newSupplier.phone"
                :placeholder="
                  $vuetify.lang.t('$vuetify.phone_holder')
                "
                :label="
                  $vuetify.lang.t('$vuetify.supplier.phone')
                "
                required
                :rules="formRule.phone"
                :select-label="
                  $vuetify.lang.t('$vuetify.supplier.country')
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
              md="6"
            >
              <v-select
                v-model="newSupplier.expanse"
                :items="categories"
                clearable
                item-text="name"
                item-value="id"
                :loading="isCategoryLoading"
                :disabled="!!isCategoryLoading"
                :label="
                  $vuetify.lang.t('$vuetify.supplier.expense')
                "
                required
                :rules="formRule.country"
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
                            'expenseCategory/toogleNewModal',
                            true
                          )
                        "
                      >
                        mdi-plus
                      </v-icon>
                    </template>
                    <span>{{
                      $vuetify.lang.t(
                        "$vuetify.titles.newAction"
                      )
                    }}</span>
                  </v-tooltip>
                </template>
              </v-select>
            </v-col>
            <v-col
              cols="12"
              md="6"
            >
              <v-switch
                v-model="newSupplier.walking"
                :label="$vuetify.lang.t('$vuetify.articles.walking')"
              />
            </v-col>
            <v-col
              cols="12"
              md="12"
            >
              <v-text-field
                v-model="newSupplier.address"
                :rules="formRule.address"
                :label="
                  $vuetify.lang.t('$vuetify.supplier.address')
                "
                required
              />
            </v-col>
            <v-col
              cols="12"
              md="12"
            >
              <v-text-field
                v-model="newSupplier.note"
                :label="
                  $vuetify.lang.t('$vuetify.supplier.note')
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
      <new-expense-category
        v-if="$store.state.expenseCategory.showNewModal"
      />
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
      showModal: false,
      formValid: false,
      hidePinCode1: true,
      hidePinCode2: true,
      errorPhone: null,
      formRule: this.$rules,
      countrySelect: null
    }
  },
  computed: {
    ...mapState('supplier', ['saved', 'newSupplier', 'isActionInProgress']),
    ...mapState('expenseCategory', [
      'saved',
      'categories',
      'isCategoryLoading'
    ]),
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
    this.getExpenseCategories()
  },
  methods: {
    ...mapActions('supplier', ['createSupplier', 'toogleNewModal']),
    ...mapActions('expenseCategory', ['getExpenseCategories']),
    onCountry (event) {
      this.newSupplier.country = event.iso2
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
        this.newSupplier.phone = number.replace(' ', '')
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
    createNewClient () {
      if (this.$refs.form.validate()) {
        this.$Swal
          .fire({
            title: this.$vuetify.lang.t('$vuetify.titles.new', [
              this.$vuetify.lang.t('$vuetify.supplier.name')
            ]),
            text: this.$vuetify.lang.t(
              '$vuetify.messages.warning_requested_provider'
            ),
            icon: 'info',
            showCancelButton: true,
            cancelButtonText: this.$vuetify.lang.t(
              '$vuetify.actions.no'
            ),
            confirmButtonText: this.$vuetify.lang.t(
              '$vuetify.actions.yes'
            ),
            confirmButtonColor: 'primary'
          })
          .then(result => {
            this.loading = true
            this.newSupplier.sendEmail = result.value
            this.createSupplier(this.newSupplier)
          })
      }
    }
  }
}
</script>

<style scoped></style>
