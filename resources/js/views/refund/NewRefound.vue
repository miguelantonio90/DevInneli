<template>
  <v-dialog
    v-model="toogleNewModal"
    max-width="450"
    persistent
  >
    <v-card>
      <v-card-title>
        <span class="headline">{{ $vuetify.lang.t('$vuetify.actions.refund') }}</span>
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
              md="12"
            >
              <v-text-field-money
                v-model="newRefund.cant"
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
            <v-col
              cols="12"
              md="12"
            >
              <v-text-field-money
                v-model="newRefund.money"
                :label="$vuetify.lang.t('$vuetify.payment.cash')"
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
          {{ $vuetify.lang.t('$vuetify.actions.cancel') }}
        </v-btn>
        <v-btn
          :disabled="!formValid || isActionInProgress || !disabledButon"
          :loading="isActionInProgress"
          class="mb-2"
          color="primary"
          @click="handlerRefund"
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
  name: 'NewRefound',
  data () {
    return {
      formValid: false,
      formRule: this.$rules
    }
  },
  computed: {
    ...mapState('refund', ['saved', 'newRefund', 'isActionInProgress']),
    disabledButon () {
      return this.newRefund.cant > 0 || this.newRefund.money > 0
    }
  },
  methods: {
    ...mapActions('refund', ['toogleNewModal', 'createRefund']),
    async handlerRefund () {
      let totalCantRefund = 0
      let totalMoneyRefund = 0
      this.newRefund.article.refounds.forEach((v) => {
        totalCantRefund += parseFloat(v.cant)
        totalMoneyRefund += parseFloat(v.money)
      })

      if (this.newRefund.cant > this.newRefund.article.cant - totalCantRefund || this.newRefund.cant < 0) {
        this.$Swal.fire({
          title: this.$vuetify.lang.t('$vuetify.actions.refund', [
            this.$vuetify.lang.t('$vuetify.menu.articles')
          ]),
          text: this.$vuetify.lang.t('$vuetify.messages.warning_refund_Cant', [totalCantRefund], [parseFloat(this.newRefund.article.cant - totalCantRefund).toFixed(2)]),
          icon: 'warning',
          showCancelButton: false,
          confirmButtonText: this.$vuetify.lang.t(
            '$vuetify.actions.accept'
          ),
          confirmButtonColor: 'red'
        })
      } else if (this.newRefund.money > this.newRefund.article.cant * this.newRefund.article.price - totalMoneyRefund || this.newRefund.money < 0) {
        this.$Swal.fire({
          title: this.$vuetify.lang.t('$vuetify.actions.refund', [
            this.$vuetify.lang.t('$vuetify.menu.articles')
          ]),
          text: this.$vuetify.lang.t('$vuetify.messages.warning_refund_Money',
            [totalMoneyRefund], [parseFloat(this.newRefund.article.cant * this.newRefund.article.price - totalMoneyRefund).toFixed(2)]),
          icon: 'warning',
          showCancelButton: false,
          confirmButtonText: this.$vuetify.lang.t(
            '$vuetify.actions.accept'
          ),
          confirmButtonColor: 'red'
        })
      } else if (this.$refs.form.validate()) {
        this.loading = true
        await this.createRefund(this.newRefund).then(() => {
          this.$emit('updateParent')
        }).catch(() => {
          this.loading = false
        })
      }
    }
  }
}
</script>

<style scoped>

</style>
