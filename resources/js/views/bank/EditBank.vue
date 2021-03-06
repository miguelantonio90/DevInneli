<template>
  <v-dialog
    v-model="toogleEditModal"
    max-width="600px"
    persistent
  >
    <v-card>
      <v-card-title>
        <span class="headline">{{
          $vuetify.lang.t("$vuetify.titles.edit", [
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
              <v-autocomplete
                v-model="editBank.count_type"
                :label="$vuetify.lang.t('$vuetify.tax.type')"
                disabled
                :items="getTypeBank"
              >
                <template v-slot:selection="data">
                  <i>
                    {{ $vuetify.lang.t('$vuetify.payment.' + data.item) }}</i>
                </template>
                <template v-slot:item="data">
                  <template
                    v-if="typeof data.item !== 'object'"
                  >
                    <v-list-item-content
                      v-text="$vuetify.lang.t('$vuetify.payment.' + data.item)"
                    />
                  </template>
                  <template v-else>
                    <v-list-item-icon>
                      <v-list-item-content>
                        <v-list-item-title>
                          {{ $vuetify.lang.t('$vuetify.payment.' + data.item.text) }}
                        </v-list-item-title>
                      </v-list-item-content>
                    </v-list-item-icon>
                  </template>
                </template>
              </v-autocomplete>
            </v-col>
            <v-col
              cols="12"
              md="6"
            >
              <v-text-field
                v-model="editBank.name"
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
                  v-model="editBank.date"
                  no-title
                  @input="menu2 = false"
                />
              </v-menu>
            </v-col>
            <v-col
              cols="12"
              md="6"
            >
              <v-text-field
                v-model="editBank.count_number"
                :label="$vuetify.lang.t('$vuetify.bank.count_number')"
                :rules="formRule.firstName"
                required
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
                      v-model="editBank.currency"
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
                      style="margin-top: 15"
                    />
                  </v-col>
                  <v-col
                    :cols="currencies.length > 0 ? 9 : 12"
                  >
                    <v-text-field-money
                      v-model="editBank.init_balance"
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
              cols="12"
              md="12"
            >
              <v-textarea
                v-model="editBank.description"
                :label="$vuetify.lang.t('$vuetify.description')"
                required
              />
            </v-col>
            <v-col
              cols="12"
              md="12"
            >
              <app-color-picker
                :value="editBank.color"
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
          @click="toogleEditModal(false)"
        >
          <v-icon>mdi-close</v-icon>
          {{ $vuetify.lang.t("$vuetify.actions.cancel") }}
        </v-btn>
        <v-btn
          :disabled="!formValid || isActionInProgress"
          :loading="isActionInProgress"
          class="mb-2"
          color="primary"
          @click="updateBankHandler"
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
  name: 'EditBank',
  data () {
    return {
      menu2: false,
      currencies: [],
      formValid: false,
      errorPhone: null,
      formRule: this.$rules
    }
  },
  computed: {
    ...mapState('bank', ['saved', 'editBank', 'isActionInProgress']),
    ...mapState('exchangeRate', ['changes']),
    ...mapGetters('auth', ['user']),
    computedDateFormatted () {
      return this.formatDate(this.editBank.date)
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
        this.editBank.currency = this.currencies[0]
      }
    }
  },
  methods: {
    ...mapActions('bank', ['updateBank', 'toogleEditModal']),
    inputColor (color) {
      this.editBank.color = color
    },
    formatDate (date) {
      if (!date) return null
      const [year, month, day] = date.split('-')
      return `${day}/${month}/${year}`
    },
    async updateBankHandler () {
      if (this.$refs.form.validate()) {
        this.loading = true
        await this.updateBank(this.editBank)
      }
    }
  }
}
</script>

<style scoped></style>
