<template>
  <v-dialog
    v-model="toogleEditModal"
    max-width="600px"
    persistent
  >
    <v-card>
      <v-card-title>
        <span class="headline">{{
          $vuetify.lang.t('$vuetify.titles.edit', [
            $vuetify.lang.t('$vuetify.menu.pay'),
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
                v-model="editCategory.name"
                :label="$vuetify.lang.t('$vuetify.firstName')"
                :rules="formRule.firstName"
                required
              />
            </v-col>
            <v-col
              cols="12"
              md="12"
            >
                <v-select v-model="editPayment.method" :items="getPaymentMethod"
                          clearable
                          :label="$vuetify.lang.t('$vuetify.menu.pay')"
                          item-text="name"
                          item-value="value"
                          required></v-select>
            </v-col>
          </v-row>
        </v-form>
      </v-card-text>
      <v-card-actions>
        <v-spacer />
        <v-btn
          class="mb-2"
          @click="toogleEditModal(false)"
        >
          <v-icon>mdi-close</v-icon>
          {{ $vuetify.lang.t('$vuetify.actions.cancel') }}
        </v-btn>
        <v-btn
          :disabled="!formValid"
          :loading="isActionInProgress"
          class="mb-2"
          color="primary"
          @click="updatePaymentHandler"
        >
          <v-icon>mdi-check</v-icon>
          {{ $vuetify.lang.t('$vuetify.actions.save') }}
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import { mapActions, mapState } from 'vuex'

export default {
    name:'EditPayment',
  data () {
    return {
      formValid: false,
      errorPhone: null,
      formRule: this.$rules
    }
  },
  computed: {
    ...mapState('payment', ['saved', 'editPayment', 'isActionInProgress']),
      getPaymentMethod(){
          return [
              {
                  name: this.$vuetify.lang.t('$vuetify.payment.card'),
                  value: 'card'
              },
              {
                  name: this.$vuetify.lang.t('$vuetify.payment.check'),
                  value: 'check'
              },
              {
                  name: this.$vuetify.lang.t('$vuetify.payment.other'),
                  value: 'other'
              },
          ]
      }
  },
  methods: {
    ...mapActions('payment', ['updatePayment', 'toogleEditModal']),
    async updatePaymentHandler () {
      if (this.$refs.form.validate()) {
        this.loading = true
        await this.updatePayment(this.editCategory)
      }
    }
  }
}
</script>

<style scoped></style>
