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
              class="py-0"
              cols="12"
              md="6"
            >
              <v-text-field
                v-model="totalCount[3]"
                :label="$vuetify.lang.t('$vuetify.payment.counted')"
                readonly
              />
            </v-col>
            <v-col
              class="py-0"
              cols="12"
              md="6"
            >
              <v-text-field
                v-model="totalCash[3]"
                :label="$vuetify.lang.t('$vuetify.payment.cash')"
                :rules="formRule.required"
                required
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
              class="py-0"
              cols="12"
              md="6"
              :disabled="true"
            >
              <template :disabled="true">
                <v-text-field
                  v-model="totalCredit[3]"
                  :label="$vuetify.lang.t('$vuetify.payment.credit')"
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
                <v-text-field
                  v-model="totalRefunds[3]"
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
                  v-model="total[1]"
                  :append-icon="total[1]<0?'mdi-close-circle':'mdi-check-circle'"
                  :label="$vuetify.lang.t('$vuetify.boxes.difference')"
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
      totalCount: [0, 0, ''],
      totalCredit: [0, 0, ''],
      totalRefunds: [0, 0, ''],
      totalCash: [0, 0, ''],
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
    if (this.openClose.box.state !== 'open') {
      await this.getUsers().then((s) => {
        this.openClose.open_to = this.users[0]
      })
    } else {
      this.openClose.sales.forEach((v) => {
        if (v.pay === 'credit') {
          this.totalCredit[0] += 1
          this.totalCredit[1] += v.totalPrice
        } else if (v.pay === 'counted' && v.payments.name !== 'cash') {
          this.totalCount[0] += 1
          this.totalCount[1] += v.totalPrice
        } else {
          this.totalCash[0] += 1
          this.totalCash[1] += v.totalPrice
        }
        this.totalCash[0] += 1
        this.totalRefunds[1] += this.getTotalRefunds(v.articles)
      })
      this.totalCredit[3] = this.user.company.currency + ' ' + parseFloat(this.totalCredit[1]).toFixed(2) + ' (' + this.$vuetify.lang.t('$vuetify.pay.pays') + ': ' + this.totalCredit[0] + ')'
      this.totalCount[3] = this.user.company.currency + ' ' + parseFloat(this.totalCount[1]).toFixed(2) + ' (' + this.$vuetify.lang.t('$vuetify.pay.pays') + ': ' + this.totalCount[0] + ')'
      this.totalCash[3] = this.user.company.currency + ' +' + parseFloat(this.totalCash[1]).toFixed(2) + ' (' + this.$vuetify.lang.t('$vuetify.pay.pays') + ': ' + this.totalCash[0] + ')'
      this.totalRefunds[3] = this.user.company.currency + ' -' + parseFloat(this.totalRefunds[1]).toFixed(2) + ' (' + this.$vuetify.lang.t('$vuetify.variants.cant') + ': ' + this.totalRefunds[0] + ')'
    }
  },
  methods: {
    ...mapActions('boxes', ['openCloseBox', 'toogleOpenCloseModal']),
    ...mapActions('user', ['getUsers']),
    calcTotal () {
      this.total[1] = this.openClose.open_money + this.totalCash[1] - this.totalRefunds[1] - this.openClose.close_money
    },
    async openCloseBoxHandler () {
      if (this.$refs.form.validate()) {
        this.loading = true
        await this.openCloseBox(this.openClose)
      }
    },
    getTotalRefunds (articles) {
      let totalR = 0
      articles.forEach((article) => {
        article.refounds.forEach((v) => {
          totalR += v.money
        })
      })
      return totalR
    }
  }
}
</script>

<style scoped>
</style>
