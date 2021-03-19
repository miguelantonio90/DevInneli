<template>
  <v-dialog
    v-model="toogleOpenCloseModal"
    max-width="450"
    persistent
  >
    <app-loading v-show="loadingData" />
    <v-card
      v-if="!loadingData"
    >
      <v-card-title>
        <span class="headline">
          {{
            openClose.box.state === 'open'
              ? $vuetify.lang.t('$vuetify.actions.close')
              : $vuetify.lang.t('$vuetify.actions.open')
          }}
          {{ openClose.box.name }}
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
              cols="12"
              md="6"
            >
              <v-autocomplete
                v-model="openClose.open_to"
                :rules="formRule.country"
                :items="users"
                required
                :label="$vuetify.lang.t('$vuetify.to') + ':'"
                item-text="firstName"
                return-object
              />
            </v-col>
            <v-col
              v-if="openClose.box.state !== 'open'"
              cols="12"
              md="6"
            >
              <v-text-field-money
                v-model="openClose.open_money"
                :label="
                  $vuetify.lang.t('$vuetify.variants.cant')
                "
                :options="{
                  length: 15,
                  precision: 2,
                  empty: 0.00
                }"
                :prefix="user.company.currency"
                :properties="{
                  clearable: true
                }"
                :rules="formRule.required"
                required
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
                :label="
                  $vuetify.lang.t(
                    '$vuetify.actions.open_to'
                  ) + ':'
                "
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
                :options="{
                  length: 15,
                  precision: 2,
                  empty: 0.00
                }"
                :properties="{
                  clearable: true
                }"
                :rules="formRule.required"
                readonly
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
                :label="
                  $vuetify.lang.t(
                    '$vuetify.payment.' + payment.method
                  )
                "
                :prefix="user.company.currency"
                :value="
                  payment.total
                    ? parseFloat(payment.total).toFixed(2)
                    : 0.00
                "
                readonly
              />
            </v-col>
            <v-col
              :disabled="true"
              class="py-0"
              cols="12"
              md="6"
            >
              <template :disabled="true">
                <v-text-field
                  :label="
                    $vuetify.lang.t('$vuetify.menu.refund')
                  "
                  :value="
                    openClose.totalRefunds
                      ? parseFloat(
                        openClose.totalRefunds
                      ).toFixed(2)
                      : 0.00
                  "
                  :prefix="user.company.currency"
                  readonly
                />
              </template>
            </v-col>
            <v-col
              :disabled="true"
              class="py-0"
              cols="12"
              md="6"
            >
              <template :disabled="true">
                <v-text-field-money
                  v-model="openClose.close_money"
                  :label="
                    $vuetify.lang.t('$vuetify.boxes.final')
                  "
                  :options="{
                    length: 15,
                    precision: 2,
                    empty: 0.0
                  }"
                  :prefix="user.company.currency"
                  :properties="{
                    clearable: true
                  }"
                  :rules="formRule.required"
                  required
                  @input="calcTotal"
                />
              </template>
            </v-col>
            <v-col
              :disabled="true"
              class="py-0"
              cols="12"
              md="6"
            >
              <template :disabled="true">
                <v-text-field
                  :append-icon="
                    total[1] < 0
                      ? 'mdi-close-circle'
                      : 'mdi-check-circle'
                  "
                  :color="total[1] < 0 ? 'danger' : 'primary'"
                  :label="
                    $vuetify.lang.t(
                      '$vuetify.boxes.difference'
                    )
                  "
                  :value="
                    total[1]
                      ? parseFloat(total[1]).toFixed(2)
                      : 0.00
                  "
                  :prefix="user.company.currency"
                  readonly
                  required
                />
              </template>
            </v-col>
          </v-row>
        </v-form>
      </v-card-text>
      <v-card-actions>
        <v-spacer />
        <v-btn
          :disabled="isActionInProgress"
          class="mb-2"
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
      loadingData: false,
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
    ...mapGetters('auth', ['user']),
    isValid () {
      return this.openClose.box.state === 'close' ? !!this.openClose.open_to : false
    }
  },
  async created () {
    this.loadingData = true
    this.formValid = false
    await this.getUsers()
    if (this.openClose.box.state !== 'close') {
      this.calcTotal()
    }
    this.loadingData = false
  },
  methods: {
    ...mapActions('boxes', ['openCloseBox', 'toogleOpenCloseModal']),
    ...mapActions('user', ['getUsers']),
    calcTotal () {
      console.log('asasasasasasasaas')
      this.total[1] =
          -1 *
          (parseFloat(this.openClose.open_money) +
              this.openClose.payments.filter(p => p.method === 'cash')[0]
                .total -
              parseFloat(this.openClose.totalRefunds) -
              parseFloat(this.openClose.close_money))
    },
    async openCloseBoxHandler () {
      if (this.$refs.form.validate()) {
        this.loading = true
        await this.openCloseBox(this.openClose).then((v) => {
          window.location.reload()
        })
      }
    }
  }
}
</script>

<style scoped></style>
