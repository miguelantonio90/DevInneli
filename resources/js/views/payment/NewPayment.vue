<template>
  <v-dialog
    max-width="450"
    persistent
  >
    <v-card>
      <v-card-title>
        <span class="headline">{{
          $vuetify.lang.t("$vuetify.titles.new", [
            $vuetify.lang.t("$vuetify.payment.name")
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
              md="12"
            >
              <v-text-field
                v-model="newPayment.name"
                :label="$vuetify.lang.t('$vuetify.firstName')"
                :rules="formRule.firstName"
                required
              />
            </v-col>
            <v-col
              cols="12"
              md="12"
            >
              <v-select
                v-model="newPayment.method"
                :items="paymentsConst"
                clearable
                :label="$vuetify.lang.t('$vuetify.menu.pay')"
                item-text="name"
                item-value="key"
                required
                :rules="formRule.country"
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
          @click="createNewPayment"
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
  name: 'NewPayment',
  data () {
    return {
      formValid: false,
      hidePinCode1: true,
      hidePinCode2: true,
      errorPhone: null,
      formRule: this.$rules
    }
  },
  computed: {
    ...mapState('payment', [
      'saved',
      'newPayment',
      'paymentsConst',
      'isActionInProgress'
    ]),
    ...mapState('statics', ['payments'])
  },
  created () {
    this.formValid = false
  },
  methods: {
    ...mapActions('payment', ['createPayment', 'toogleNewModal']),
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
    }
  }
}
</script>

<style scoped></style>
