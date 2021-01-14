<template>
  <v-dialog
    v-model="toogleOpenCloseModal"
    max-width="450"
    persistent
  >
    <v-card>
      <v-card-title>
        <span class="headline">
          {{ openClose.box.state === 'open'?$vuetify.lang.t('$vuetify.actions.close')
            :$vuetify.lang.t('$vuetify.actions.open') }} {{ openClose.box.name }}
        </span>
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
              v-if="openClose.box.state === 'close'"
              class="py-0"
              cols="12"
              md="6"
            >
              <v-select
                v-model="openClose.open_to"
                style="margin-top: 15px"
                :clearable="users.length > 1"
                :items="users"
                :label="$vuetify.lang.t('$vuetify.to') +':'"
                item-text="firstName"
                return-object
                required
                :rules="formRule.country"
              />
            </v-col>
            <v-col
              v-if="openClose.box.state !== 'open'"
              class="py-0"
              cols="12"
              md="6"
            >
              <v-text-field-money
                v-model="openClose.open_money"
                :label="$vuetify.lang.t('$vuetify.variants.cant')"
                :rules="formRule.required"
                required
                :properties="{
                  clearable: true
                }"
                :options="{
                  length: 15,
                  precision: 2,
                  empty: 0.00,
                }"
              />
            </v-col>
          </v-row>
          <v-row v-if="openClose.box.state === 'open'">
            <v-col
              class="py-0"
              cols="12"
              md="6"
            >
              <v-text-field
                v-model="openClose.open_to.firstName"
                :label="$vuetify.lang.t('$vuetify.actions.open_to') +':'"
                readonly
              />
            </v-col>
            <v-col
              class="py-0"
              cols="12"
              md="6"
            >
              <v-text-field
                v-model="openClose.open_money"
                :label="$vuetify.lang.t('$vuetify.boxes.init')"
                :rules="formRule.required"
                readonly
                :properties="{
                  clearable: true
                }"
                :options="{
                  length: 15,
                  precision: 2,
                  empty: 0.00,
                }"
              />
            </v-col>
            <v-col
              v-for="payment in openClose.payments"
              :key="payment.id"
              class="py-0"
              cols="12"
              md="6"
            >
              <v-text-field
                :value="parseFloat(payment.total).toFixed(2)"
                :label="$vuetify.lang.t('$vuetify.payment.' + payment.method)"
                readonly
                :prefix="user.company.currency"
              />
            </v-col>
            <v-col
              class="py-0"
              cols="12"
              md="6"
              :disabled="true"
            >
              <template :disabled="true">
                <v-text-field
                  :value="parseFloat(openClose.totalRefunds).toFixed(2)"
                  :label="$vuetify.lang.t('$vuetify.menu.refund')"
                  :rules="formRule.required"
                  readonly
                  required
                />
              </template>
            </v-col>
            <v-col
              class="py-0"
              cols="12"
              md="6"
              :disabled="true"
            >
              <template :disabled="true">
                <v-text-field-money
                  v-model="openClose.close_money"
                  :label="$vuetify.lang.t('$vuetify.boxes.final')"
                  :rules="formRule.required"
                  required
                  :prefix="user.company.currency"
                  :properties="{
                    clearable: true
                  }"
                  :options="{
                    length: 15,
                    precision: 2,
                    empty: 0.00,
                  }"
                  @input="calcTotal"
                />
              </template>
            </v-col>
            <v-col
              class="py-0"
              cols="12"
              md="6"
              :disabled="true"
            >
              <template :disabled="true">
                <v-text-field
                  :value="parseFloat(total[1]).toFixed(2)"
                  :append-icon="total[1]<0?'mdi-close-circle':'mdi-check-circle'"
                  :label="$vuetify.lang.t('$vuetify.boxes.difference')"
                  :color="total[1] < 0 ? 'danger':'primary'"
                  required
                  readonly
                />
              </template>
            </v-col>
          </v-row>
        </v-form>
      </v-card-text>
      <v-card-actions>
        <v-spacer />
        <v-btn
          class="mb-2"
          :disabled="isActionInProgress"
          @click="toogleOpenCloseModal(false)"
        >
          <v-icon>mdi-close</v-icon>
          {{ $vuetify.lang.t('$vuetify.actions.cancel') }}
        </v-btn>
        <v-btn
          :disabled="!formValid || isActionInProgress"
          :loading="isActionInProgress"
          class="mb-2"
          color="primary"
          @click="openCloseBoxHandler"
        >
          <v-icon>mdi-content-save</v-icon>
          {{ $vuetify.lang.t('$vuetify.actions.save') }}
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import { mapActions, mapGetters, mapState } from 'vuex'

export default {
  name: 'OpenCloseBox',
  data () {
    return {
      formValid: false,
      formRule: this.$rules,
      total: [0, 0, '']
    }
  },
  computed: {
    ...mapState('boxes', ['saved', 'openClose', 'isActionInProgress']),
    ...mapState('sale', ['sales']),
    ...mapState('refund', ['refunds']),
    ...mapState('user', ['users']),
    ...mapGetters('auth', ['user'])
  },
  async created () {
    this.formValid = false
  },
  methods: {
    ...mapActions('boxes', ['openCloseBox', 'toogleOpenCloseModal']),
    ...mapActions('user', ['getUsers']),
    calcTotal () {
      this.total[1] = -1 * (parseFloat(this.openClose.open_money) + this.openClose.payments.filter(p => p.method === 'cash')[0].total -
          parseFloat(this.openClose.totalRefunds) - parseFloat(this.openClose.close_money))
    },
    async openCloseBoxHandler () {
      if (this.$refs.form.validate()) {
        this.loading = true
        await this.openCloseBox(this.openClose)
      }
    }
  }
}
</script>

<style scoped>
</style>
