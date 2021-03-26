<template>
  <v-dialog
    v-model="toogleNewMovesModal"
    max-width="550"
    persistent
  >
    <v-card>
      <v-card-title>
        <span class="headline">{{
          $vuetify.lang.t("$vuetify.titles.new", [
            $vuetify.lang.t("$vuetify.menu.account_move")
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
                :value="accountParent.name"
                :label="$vuetify.lang.t('$vuetify.menu.accounting')"
                readonly
              />
            </v-col>
            <v-col
              cols="12"
              md="6"
            >
              <v-text-field
                :value="$vuetify.lang.t('$vuetify.accounting_category.' + accountParent.category.nature)"
                :label="$vuetify.lang.t('$vuetify.accounting_category.nature')"
                readonly
              />
            </v-col>
            <v-col
              cols="12"
              md="4"
            >
              <v-menu
                ref="menu"
                v-model="menu"
                :close-on-content-click="true"
                :return-value.sync="date"
                transition="scale-transition"
                offset-y
                min-width="auto"
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    v-model="date"
                    :label="$vuetify.lang.t('$vuetify.date')"
                    readonly
                    v-bind="attrs"
                    v-on="on"
                  />
                </template>
                <v-date-picker
                  v-model="date"
                  no-title
                  scrollable
                />
              </v-menu>
            </v-col>
            <v-col
              cols="12"
              md="4"
            >
              <v-text-field
                v-model="newMove.ref"
                :label="$vuetify.lang.t('$vuetify.articles.ref')"
                :rules="formRule.firstName"
                required
              />
            </v-col>
            <v-col
              cols="12"
              md="4"
            >
              <v-text-field-money
                v-model="accountParent.current_balance"
                :label="$vuetify.lang.t('$vuetify.boxes.current')"
                :options="{
                  length: 15,
                  precision: 2,
                  empty: 0.00
                }"
                :properties="{
                  clearable: false,
                  readonly:true
                }"
              />
            </v-col>
            <v-col
              cols="12"
              md="4"
            >
              <v-text-field-money
                v-model="newMove.debit"
                :label="$vuetify.lang.t('$vuetify.accounting_category.debit')"
                :rules="formRule.required"
                required
                :properties="{
                  'prepend-inner-icon':accountParent.category.nature ==='creditor'? 'mdi-arrow-down-bold': 'mdi-arrow-up-bold',
                }"
                :options="{
                  length: 15,
                  precision: 2,
                  empty: 0.00,
                }"
              />
            </v-col>
            <v-col
              cols="12"
              md="4"
            >
              <v-text-field-money
                v-model="newMove.credit"
                :label="$vuetify.lang.t('$vuetify.accounting_category.assets')"
                :rules="formRule.required"
                :value="0.00"
                required
                :properties="{
                  'prepend-inner-icon':accountParent.category.nature ==='creditor'? 'mdi-arrow-up-bold': 'mdi-arrow-down-bold',
                }"
                :options="{
                  length: 15,
                  precision: 2,
                  empty: 0.00,
                }"
              />
            </v-col>
            <v-col
              cols="12"
              md="4"
            >
              <v-text-field
                v-model="future"
                :label="$vuetify.lang.t('$vuetify.boxes.future')"
                :rules="formRule.required"
                :value="0.00"
                required
              />
            </v-col>
            <v-col
              cols="12"
              md="12"
            >
              <v-text-field
                v-model="newMove.detail"
                :label="$vuetify.lang.t('$vuetify.access.description')"
                :rules="formRule.required"
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
          @click="toogleNewMovesModal(false)"
        >
          <v-icon>mdi-close</v-icon>
          {{ $vuetify.lang.t("$vuetify.actions.cancel") }}
        </v-btn>
        <v-btn
          :disabled="!formValid || isActionInProgress"
          :loading="isActionInProgress"
          class="mb-2"
          color="primary"
          @click="createNewMove"
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
  name: 'NewMove',
  props: {
    accountParent: {
      type: Object,
      default: function () {
        return {}
      }
    }
  },
  data () {
    return {
      date: new Date().toISOString().substr(0, 10),
      future: 0.00,
      menu: false,
      modal: false,
      formValid: false,
      hidePinCode1: true,
      hidePinCode2: true,
      errorPhone: null,
      formRule: this.$rules
    }
  },
  computed: {
    ...mapState('accountMove', ['saved', 'newMove', 'isActionInProgress'])
  },
  watch: {
    'newMove.credit': function () {
      if (this.newMove.credit !== 0.00) {
        this.newMove.debit = 0.00
        if (this.accountParent.category.nature === 'creditor') {
          this.future = parseFloat(this.accountParent.current_balance) + parseFloat(this.newMove.credit) + parseFloat(this.newMove.debit)
        } else {
          this.future = parseFloat(this.accountParent.current_balance) - parseFloat(this.newMove.credit) + parseFloat(this.newMove.debit)
        }
        this.future = parseFloat(this.future).toFixed(2)
      }
    },
    'newMove.debit': function () {
      if (this.newMove.debit !== 0.00) {
        this.newMove.credit = 0.00
        if (this.accountParent.category.nature === 'creditor') {
          this.future = parseFloat(this.accountParent.current_balance) - parseFloat(this.newMove.debit) + parseFloat(this.newMove.credit)
        } else {
          this.future = parseFloat(this.accountParent.current_balance) + parseFloat(this.newMove.debit) + parseFloat(this.newMove.credit)
        }
        this.future = parseFloat(this.future).toFixed(2)
      }
    }
  },
  created () {
    this.future = this.accountParent.current_balance
    this.formValid = false
    this.newMove.account_id = this.accountParent.id
  },
  methods: {
    ...mapActions('accountMove', ['createMove', 'toogleNewMovesModal']),
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
    async createNewMove () {
      if (this.$refs.form.validate()) {
        this.loading = true
        this.newMove.date = this.date
        await this.createMove(this.newMove)
      }
    }
  }
}
</script>

<style scoped></style>
