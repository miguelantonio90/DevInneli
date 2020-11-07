<template>
  <v-dialog
    v-model="toogleEditModal"
    max-width="450"
    persistent
  >
    <v-card>
      <v-card-title>
        <span class="headline">{{
          $vuetify.lang.t('$vuetify.titles.new', [
            $vuetify.lang.t('$vuetify.tax.name'),
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
                v-model="editTax.name"
                :label="$vuetify.lang.t('$vuetify.firstName')"
                :rules="formRule.firstName"
                required
              />
            </v-col>
            <v-col
              cols="12"
              md="12"
            >
              <v-text-field-money
                v-model="editTax.value"
                :label="$vuetify.lang.t('$vuetify.tax.value')"
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
            <h4>{{ $vuetify.lang.t('$vuetify.tax.rate') }}</h4>
            <v-radio-group
              v-model="editTax.percent"
              row
            >
              <v-radio
                :label="$vuetify.lang.t('$vuetify.tax.percent')"
                value="true"
              />
              <v-radio
                :label="$vuetify.lang.t('$vuetify.tax.permanent')"
                value="false"
              />
            </v-radio-group>
            <v-col />
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
          {{ $vuetify.lang.t('$vuetify.actions.cancel') }}
        </v-btn>
        <v-btn
          :disabled="!formValid || isActionInProgress"
          :loading="isActionInProgress"
          class="mb-2"
          color="primary"
          @click="updateTaxHandler"
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
  name: 'EditTax',
  data () {
    return {
      formValid: false,
      errorPhone: null,
      formRule: this.$rules
    }
  },
  computed: {
    ...mapState('tax', ['saved', 'editTax', 'isActionInProgress'])
  },
  methods: {
    ...mapActions('tax', ['updateTax', 'toogleEditModal']),
    async updateTaxHandler () {
      if (this.$refs.form.validate()) {
        this.loading = true
        await this.updateTax(this.editTax)
      }
    }
  }
}
</script>

<style scoped></style>
