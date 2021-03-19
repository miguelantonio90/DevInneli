<template>
  <v-dialog
    v-model="toogleNewModal"
    max-width="450"
    persistent
  >
    <v-card>
      <v-card-title>
        <span class="headline">{{
          $vuetify.lang.t("$vuetify.actions.refund")
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
                v-model="newRefund.bank"
                :items="banks"
                :label="$vuetify.lang.t('$vuetify.menu.bank')"
                item-text="name"
                item-value="value"
                required
                return-object
              />
            </v-col>
            <v-col
              v-if="newRefund.bank"
              class="py-0"
              cols="12"
              md="6"
            >
              <v-autocomplete
                v-model="newRefund.method"
                :filter="customFilter"
                :items="newRefund.bank.paymentBanks"
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
              cols="12"
              md="6"
            >
              <v-text-field-money
                v-model="newRefund.cant"
                :label="
                  $vuetify.lang.t('$vuetify.menu.articles')
                "
                :rules="formRule.required"
                required
                :properties="{
                  clearable: true,
                  min:0.00,
                  max:totalCantArticleRefund
                }"
                :options="{
                  length: 15,
                  precision: 2,
                  empty: 0.0,
                  min:0.00,
                  max:totalCantArticleRefund
                }"
              />
            </v-col>
            <v-col
              cols="12"
              md="6"
            >
              <v-text-field-money
                v-model="newRefund.money"
                :label="
                  $vuetify.lang.t('$vuetify.sale.cant_money')
                "
                :rules="formRule.required"
                required
                :properties="{
                  clearable: true
                }"
                :options="{
                  length: 15,
                  precision: 2,
                  empty: 0.0
                }"
              />
            </v-col>
            <v-col
              v-if="newRefund.sale.type === 'sale'"
              cols="12"
              md="6"
            >
              <v-autocomplete
                v-model="newRefund.box"
                style="margin-left: 15px;margin-top: 15px"
                :items="localBoxes"
                item-text="name"
                required
                auto-select-first
                :rules="formRule.required"
                :label="$vuetify.lang.t('$vuetify.menu.box')"
                return-object
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
          :disabled="
            !formValid || isActionInProgress || !disabledButon
          "
          :loading="isActionInProgress"
          class="mb-2"
          color="primary"
          @click="handlerRefund"
        >
          <v-icon>mdi-content-save</v-icon>
          {{ $vuetify.lang.t("$vuetify.actions.save") }}
        </v-btn>
      </v-card-actions>
    </v-card>
    <new-box v-if="this.$store.state.boxes.showNewModal" />
  </v-dialog>
</template>

<script>
import { mapActions, mapState } from 'vuex'
import NewBox from '../boxes/NewBox'

export default {
  name: 'NewRefound',
  components: { NewBox },
  data () {
    return {
      formValid: false,
      localBoxes: [],
      formRule: this.$rules
    }
  },
  computed: {
    ...mapState('refund', ['saved', 'newRefund', 'isActionInProgress']),
    ...mapState('boxes', ['boxes', 'showNewModal']),
    ...mapState('bank', ['saved', 'banks']),
    disabledButon () {
      return this.newRefund.cant > 0 || this.newRefund.money > 0
    },
    totalCantArticleRefund () {
      let totalCantRefund = 0
      this.newRefund.article.refounds.forEach(v => {
        totalCantRefund += parseFloat(v.cant)
      })
      console.log(totalCantRefund)
      return totalCantRefund
    },
    totalCantMoneyRefund () {
      let totalMoneyRefund = 0
      this.newRefund.article.refounds.forEach(v => {
        totalMoneyRefund += parseFloat(v.money)
      })
      return totalMoneyRefund
    }
  },
  watch: {
    boxes: function () {
      this.getLocalBoxes()
    },
    'newRefund.cant': function (v1) {
      let totalCantRefund = 0
      this.newRefund.article.refounds.forEach(v => {
        totalCantRefund += parseFloat(v.cant)
      })
      if (v1 > this.newRefund.article.cant - totalCantRefund) { this.newRefund.cant = this.newRefund.article.cant - totalCantRefund }
    },
    'newRefund.money': function (v1) {
      let totalMoneyRefund = 0
      this.newRefund.article.refounds.forEach(v => {
        totalMoneyRefund += parseFloat(v.money)
      })
      if (v1 > this.newRefund.article.price * this.newRefund.article.cant - totalMoneyRefund) { this.newRefund.money = this.newRefund.article.cant * this.newRefund.article.price - totalMoneyRefund }
    }
  },
  async created () {
    if (this.newRefund.sale.type === 'sale') {
      await this.getBoxes()
    }
    await this.getBanks()
  },
  methods: {
    ...mapActions('refund', ['toogleNewModal', 'createRefund']),
    ...mapActions('boxes', ['getBoxes']),
    ...mapActions('bank', ['getBanks']),
    customFilter (item, queryText, itemText) {
      return (
        this.$vuetify.lang
          .t('$vuetify.payment.' + item.method)
          .toLowerCase()
          .indexOf(queryText.toLowerCase()) > -1
      )
    },
    async handlerRefund () {
      let totalCantRefund = 0
      let totalMoneyRefund = 0
      this.newRefund.article.refounds.forEach(v => {
        totalCantRefund += parseFloat(v.cant)
        totalMoneyRefund += parseFloat(v.money)
      })

      if (
        this.newRefund.cant >
                    this.newRefund.article.cant - totalCantRefund ||
                this.newRefund.cant < 0
      ) {
        this.showMessage(
          this.$vuetify.lang.t(
            '$vuetify.messages.warning_refund_Cant',
            [totalCantRefund],
            [
              parseFloat(
                this.newRefund.article.cant - totalCantRefund
              ).toFixed(2)
            ]
          )
        )
      } else if (
        this.newRefund.money >
                    this.newRefund.article.cant * this.newRefund.article.price -
                        totalMoneyRefund ||
                this.newRefund.money < 0
      ) {
        this.showMessage(
          this.$vuetify.lang.t(
            '$vuetify.messages.warning_refund_Money',
            [totalMoneyRefund],
            [
              parseFloat(
                this.newRefund.article.cant *
                                    this.newRefund.article.price -
                                    totalMoneyRefund
              ).toFixed(2)
            ]
          )
        )
      } else if (this.$refs.form.validate()) {
        this.loading = true
        await this.createRefund(this.newRefund)
          .then(() => {
            this.$emit('updateParent')
          })
          .catch(() => {
            this.loading = false
          })
      }
    },
    getLocalBoxes () {
      this.localBoxes = []
      if (this.newRefund.sale.shop) {
        this.localBoxes = this.boxes[this.newRefund.sale.shop.id].boxes
        if (this.localBoxes.length > 0) {
          this.newRefund.box = this.localBoxes[0]
        }
      }
    },
    showMessage (textMsg) {
      this.$Swal.fire({
        title: this.$vuetify.lang.t('$vuetify.actions.refund', [
          this.$vuetify.lang.t('$vuetify.menu.articles')
        ]),
        text: textMsg,
        icon: 'warning',
        showCancelButton: false,
        confirmButtonText: this.$vuetify.lang.t(
          '$vuetify.actions.accept'
        ),
        confirmButtonColor: 'red'
      })
    }
  }
}
</script>

<style scoped></style>
