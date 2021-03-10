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
              cols="12"
              md="6"
            >
              <v-text-field-money
                v-model="newRefund.cant"
                :label="
                  $vuetify.lang.t('$vuetify.variants.cant')
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
              cols="12"
              md="6"
            >
              <v-text-field-money
                v-model="newRefund.money"
                :label="
                  $vuetify.lang.t('$vuetify.payment.cash')
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
              md="8"
            >
              <v-select
                v-model="newRefund.box"
                clearable
                :rules="formRule.country"
                :items="localBoxes"
                required
                :label="$vuetify.lang.t('$vuetify.menu.box')"
                item-text="name"
                return-object
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
                            'boxes/toogleNewModal',
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
    disabledButon () {
      return this.newRefund.cant > 0 || this.newRefund.money > 0
    }
  },
  watch: {
    boxes: function () {
      this.getLocalBoxes()
    }
  },
  async created () {
    if (this.newRefund.sale.type === 'sale') {
      await this.getBoxes()
    }
  },
  methods: {
    ...mapActions('refund', ['toogleNewModal', 'createRefund']),
    ...mapActions('boxes', ['getBoxes']),
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
        this.localBoxes = this.boxes.filter(
          bx => bx.shop_id === this.newRefund.sale.shop.id
        )
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
