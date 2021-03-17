<template>
  <v-dialog
    v-model="toogleNewModal"
    max-width="600"
    persistent
  >
    <v-card>
      <v-card-title>
        <span class="headline">{{
          $vuetify.lang.t("$vuetify.titles.new", [
            $vuetify.lang.t("$vuetify.menu.bank")
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
                v-model="newBank.name"
                :label="$vuetify.lang.t('$vuetify.firstName')"
                :rules="formRule.firstName"
                required
              />
            </v-col>
            <v-col
              cols="12"
              md="6"
            >
              <v-menu
                v-model="menu2"
                :close-on-content-click="false"
                max-width="290px"
                min-width="290px"
                offset-y
                transition="scale-transition"
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    v-model="computedDateFormatted"
                    v-bind="attrs"
                    :label="
                      $vuetify.lang.t(
                        '$vuetify.date'
                      )
                    "
                    persistent-hint
                    prepend-icon="mdi-calendar"
                    readonly
                    v-on="on"
                  />
                </template>
                <v-date-picker
                  v-model="newBank.date"
                  no-title
                  @input="menu2 = false"
                />
              </v-menu>
            </v-col>
            <v-col
              cols="12"
              md="6"
            >
              <v-text-field-simplemask
                v-model="newBank.count_number"
                required
                :label="$vuetify.lang.t('$vuetify.bank.count_number')"
                :rules="formRule.firstName"
                :properties="{
                  clearable: true,
                }"
                :options="{
                  inputMask: '####-####-####-####',
                  outputMask: '################',
                  empty: null,
                  alphanumeric: true,
                }"
                :focus="focus"
                @focus="focus = false"
              />
            </v-col>
            <v-col
              class="py-0"
              cols="12"
              md="6"
              no-gutters
            >
              <template>
                <v-row no-gutters>
                  <v-col
                    v-if="currencies.length > 0"
                    cols="3"
                  >
                    <v-autocomplete
                      v-model="newBank.currency"
                      :items="currencies"
                      :label="
                        $vuetify.lang.t(
                          '$vuetify.currency'
                        )
                      "
                      auto-select-first
                      item-text="currency"
                      required
                      return-object
                    />
                  </v-col>
                  <v-col
                    :cols="currencies.length > 0 ? 9 : 12"
                  >
                    <v-text-field-money
                      v-model="newBank.init_balance"
                      :label="
                        $vuetify.lang.t(
                          '$vuetify.boxes.init'
                        )
                      "
                      :options="{
                        length: 15,
                        precision: 2,
                        empty: 0.0
                      }"
                      :properties="{
                        prefix:
                          currencies.length === 0
                            ? user.company.currency
                            : '',
                        clearable: true
                      }"
                      :rules="formRule.required"
                      required
                    />
                  </v-col>
                </v-row>
              </template>
            </v-col>
            <v-col
              class="py-0"
              cols="12"
              md="12"
            >
              <v-select
                v-model="newBank.payments_banks"
                clearable
                item-text="name"
                required
                chips
                :filter="customFilter"
                :items="payments"
                :label="
                  $vuetify.lang.t('$vuetify.payment.name')
                "
                :rules="formRule.required"
                return-object
                item-value="method"
                multiple
              >
                <template v-slot:selection="data">
                  <v-chip
                    v-bind="data.attrs"
                    :input-value="data.selected"
                    close
                    @click="data.select"
                    @click:close="remove(data.item)"
                  >
                    {{
                      $vuetify.lang.t(
                        '$vuetify.payment.' +
                          data.item.method
                      )
                    }}
                  </v-chip>
                </template>
                <template v-slot:item="data">
                  <template
                    v-if="typeof data.item !== 'object'"
                  >
                    <v-list-item-content
                      v-text="data.item"
                    />
                  </template>
                  <template v-else>
                    <v-list-item-content>
                      <v-list-item-title>
                        {{
                          $vuetify.lang.t(
                            '$vuetify.payment.' +
                              data.item.method
                          )
                        }}
                      </v-list-item-title>
                    </v-list-item-content>
                  </template>
                </template>
              </v-select>
            </v-col>
            <v-col
              cols="12"
              md="12"
            >
              <v-textarea
                v-model="newBank.description"
                :label="$vuetify.lang.t('$vuetify.description')"
                required
              />
            </v-col>
            <v-col
              cols="12"
              md="12"
            >
              <app-color-picker
                :value="newBank.color"
                @input="inputColor"
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
          @click="createBankHandler"
        >
          <v-icon>mdi-content-save</v-icon>
          {{ $vuetify.lang.t("$vuetify.actions.save") }}
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import { mapActions, mapGetters, mapState } from 'vuex'

export default {
  name: 'NewBank',
  data () {
    return {
      focus: false,
      menu2: false,
      currencies: [],
      formValid: false,
      errorPhone: null,
      formRule: this.$rules
    }
  },
  computed: {
    ...mapState('bank', ['saved', 'newBank', 'isActionInProgress']),
    ...mapState('exchangeRate', ['changes']),
    ...mapState('payment', ['payments']),
    ...mapGetters('auth', ['user']),
    computedDateFormatted () {
      return this.formatDate(this.newBank.date)
    },
    getTypeBank () {
      return [
        'bank',
        'credit_card',
        'cash'
      ]
    }
  },
  watch: {
    changes: function () {
      if (this.changes.length > 0) {
        this.currencies = []
        this.currencies.push({
          currency: this.user.company.currency,
          change: 1,
          id: ''
        })
        this.changes.forEach(v => {
          this.currencies.push({
            currency: v.currency,
            change: v.change,
            id: v.id
          })
        })
        this.newBank.currency = this.currencies[0]
      }
    }
  },
  created () {
    this.getPayments()
    this.getChanges()
    this.formValid = false
    this.newBank.currency = this.currencies.length > 0 ? this.currencies[0] : { currency: this.user.company.currency, id: '' }
  },
  methods: {
    ...mapActions('bank', ['createBank', 'toogleNewModal']),
    ...mapActions('payment', ['getPayments']),
    ...mapActions('exchangeRate', ['getChanges']),
    remove (item) {
      const index = this.newBank.payments_banks.indexOf(item)
      if (index >= 0) this.newBank.payments_banks.splice(index, 1)
    },
    customFilter (item, queryText, itemText) {
      return (
        this.$vuetify.lang
          .t('$vuetify.payment.' + item.method)
          .toLowerCase()
          .indexOf(queryText.toLowerCase()) > -1
      )
    },
    formatDate (date) {
      if (!date) return null
      const [year, month, day] = date.split('-')
      return `${day}/${month}/${year}`
    },
    inputColor (color) {
      this.newBank.color = color
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
    async createBankHandler () {
      if (this.$refs.form.validate()) {
        this.loading = true
        await this.createBank(this.newBank)
      }
    }
  }
}
</script>

<style scoped></style>
