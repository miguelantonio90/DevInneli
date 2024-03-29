<template>
  <v-dialog
    v-model="toogleNewModal"
    max-width="550"
    persistent
  >
    <v-card>
      <v-card-title>
        <span class="headline">{{
          $vuetify.lang.t('$vuetify.titles.new', [
            $vuetify.lang.t('$vuetify.payment.name')
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
              class="py-0"
              cols="12"
              md="6"
            >
              <v-autocomplete
                v-model="pay.bank"
                :items="banks"
                :label="$vuetify.lang.t('$vuetify.menu.bank')"
                item-text="name"
                item-value="value"
                required
                return-object
              />
            </v-col>
            <v-col
              class="py-0"
              cols="12"
              md="6"
            >
              <v-autocomplete
                v-model="pay.method"
                :filter="customFilter"
                :items="pay.bank.paymentBanks"
                :label="
                  $vuetify.lang.t('$vuetify.payment.name')
                "
                :rules="formRule.required"
                item-text="name"
                item-value="method"
                return-object
              >
                <template v-slot:selection="data">
                  {{
                    $vuetify.lang.t(
                      '$vuetify.payment.' +
                        data.item.method
                    )
                  }}
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
              </v-autocomplete>
            </v-col>
            <v-col
              class="py-0"
              cols="12"
              md="6"
            >
              <v-text-field-money
                v-model="pay.cant"
                :label="
                  $vuetify.lang.t('$vuetify.variants.cant')
                "
                :options="{
                  length: 15,
                  precision: 2,
                  empty: 0.0,
                  min:0.00,
                  max:pending
                }"
                :properties="{
                  prefix: currency,
                  clearable: true,
                  max:pending,
                  min:0.00,
                }"
                :rules="formRule.required"
                :value="pending"
                required
                style="margin-top: 8px"
              />
            </v-col>
            <v-col
              v-if="pay.name !== 'counted'"
              class="py-0"
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
                        '$vuetify.payment.pay_before'
                      )
                    "
                    persistent-hint
                    prepend-icon="mdi-calendar"
                    readonly
                    v-on="on"
                  />
                </template>
                <v-date-picker
                  v-model="pay.mora"
                  no-title
                  @input="menu2 = false"
                />
              </v-menu>
            </v-col>
            <v-col
              v-if="pay.name !== 'counted'"
              class="py-0"
              cols="12"
              md="6"
            >
              <v-text-field-money
                v-model="pay.cantMora"
                :label="
                  $vuetify.lang.t(
                    '$vuetify.payment.pay_delay'
                  )
                "
                :options="{
                  length: 15,
                  precision: 2,
                  empty: 0.0
                }"
                :properties="{
                  prefix: currency,
                  clearable: true
                }"
                :rules="formRule.required"
                :value="pending"
                required
              />
            </v-col>
            <v-col
              v-if="pay.method.method === 'cash'"
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
                      v-model="pay.currency"
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
                      style="margin-top: 15px"
                      @change="calcDifference"
                    />
                  </v-col>
                  <v-col
                    :cols="currencies.length > 0 ? 9 : 12"
                  >
                    <v-text-field-money
                      v-model="pay.cant_pay"
                      :label="
                        $vuetify.lang.t(
                          '$vuetify.payment.cant_pay'
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
                            ? currency
                            : '',
                        clearable: true
                      }"
                      :rules="formRule.required"
                      :value="pending"
                      required
                    />
                  </v-col>
                </v-row>
              </template>
            </v-col>
            <v-col
              v-if="pay.method.method === 'cash'"
              class="py-0"
              cols="12"
              md="6"
            >
              <v-text-field-money
                v-model="pay.cant_back"
                :label="
                  $vuetify.lang.t(
                    '$vuetify.payment.cant_back'
                  )
                "
                :options="{
                  length: 15,
                  precision: 2,
                  empty: 0.0
                }"
                :properties="{
                  prefix: currency,
                  clearable: true
                }"
                :rules="formRule.required"
                :value="pending"
                readonly
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
          @click="toogleNewModal(false)"
        >
          <v-icon>mdi-close</v-icon>
          {{ $vuetify.lang.t('$vuetify.actions.cancel') }}
        </v-btn>
        <v-btn
          :disabled="!formValid || isActionInProgress ||
            pay.method.method === 'cash' && parseFloat(pay.cant_pay).toFixed(2) === '0.00' ||
            pay.method.method === 'cash' && pay.cant_back < 0.00"
          :loading="isActionInProgress"
          class="mb-2"
          color="primary"
          @click="$emit('addPayment', pay)"
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
  name: 'NewPayment',
  props: {
    payments: {
      type: Array,
      // eslint-disable-next-line vue/require-valid-default-prop
      default: []
    },
    pending: {
      type: Number,
      default: 0.0
    },
    currency: {
      type: String,
      default: ''
    }
  },
  data () {
    return {
      menu2: false,
      formValid: false,
      currencies: [],
      pay: {
        name: 'counted',
        bank: {},
        method: '',
        cant: '',
        delay: 0.00,
        mora: new Date().toISOString().substr(0, 10),
        cantMora: 0.00,
        cant_pay: 0.00,
        currency: {},
        cant_back: 0.00
      },
      localPayments: [],
      formRule: this.$rules
    }
  },
  computed: {
    ...mapState('payment', ['saved', 'newPayment', 'isActionInProgress']),
    ...mapState('exchangeRate', ['saved', 'changes']),
    ...mapState('bank', ['saved', 'banks']),
    computedDateFormatted () {
      return this.formatDate(this.pay.mora)
    },
    getPay () {
      return [
        {
          text: this.$vuetify.lang.t('$vuetify.pay.counted'),
          value: 'counted'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.pay.credit'),
          value: 'credit'
        }
      ]
    }
  },
  watch: {
    'pay.cant': function (val) {
      if (
        parseFloat(val).toFixed(2) > parseFloat(this.pending).toFixed(2)
      ) {
        this.pay.cant = parseFloat(this.pending).toFixed(2)
      }
    },
    'pay.name': function (val) {
      this.updateMethod()
    },
    'pay.cant_pay': function () {
      this.calcDifference()
    },
    changes: function () {
      if (this.changes.length > 0) {
        this.currencies = []
        this.currencies.push({
          currency: this.currency,
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
        this.pay.currency = this.currencies[0]
      }
    }
  },
  mounted () {
    this.formValid = false
    this.pay.name = 'counted'
    this.pay.cant = this.pending
    this.getChanges()
    this.updateMethod()
    this.getBanks()
  },
  methods: {
    ...mapActions('payment', ['createPayment', 'toogleNewModal']),
    ...mapActions('exchangeRate', ['getChanges']),
    ...mapActions('bank', ['getBanks']),
    customFilter (item, queryText, itemText) {
      return (
        this.$vuetify.lang
          .t('$vuetify.payment.' + item.method)
          .toLowerCase()
          .indexOf(queryText.toLowerCase()) > -1
      )
    },
    calcDifference () {
      this.pay.cant_back =
          this.currencies.length > 0
            ? this.pay.cant_pay * this.pay.currency.change -
              this.pay.cant
            : this.pay.cant_pay - this.pay.cant
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
    async createNewPayment () {
      if (this.$refs.form.validate()) {
        this.loading = true
        await this.createPayment(this.newPayment)
      }
    },
    updateMethod () {
      this.localPayments = this.payments.filter(
        p => p.name === this.pay.name
      )
      this.pay.method = this.localPayments[0]
    },
    formatDate (date) {
      if (!date) return null
      const [year, month, day] = date.split('-')
      return `${day}/${month}/${year}`
    },
    parseDate (date) {
      if (!date) return null

      const [month, day, year] = date.split('/')
      return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`
    }
  }
}
</script>

<style scoped></style>
