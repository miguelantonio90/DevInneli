<template>
  <v-dialog
    v-model="toogleEditModal"
    max-width="500"
    persistent
  >
    <v-card>
      <v-card-title>
        <span class="headline">{{
          $vuetify.lang.t("$vuetify.titles.edit", [
            $vuetify.lang.t("$vuetify.menu.discount")
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
                v-model="editDiscount.name"
                :label="$vuetify.lang.t('$vuetify.firstName')"
                :rules="formRule.firstName"
                required
              />
            </v-col>
            <v-col
              cols="12"
              md="6"
            >
              <v-text-field-money
                v-model="editDiscount.value"
                :label="$vuetify.lang.t('$vuetify.tax.value')"
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
            <v-col>
              <h4>{{ $vuetify.lang.t("$vuetify.tax.rate") }}</h4>
              <v-radio-group
                v-model="editDiscount.percent"
                row
              >
                <v-radio
                  :label="$vuetify.lang.t('$vuetify.tax.percent')"
                  value="true"
                />
                <v-radio
                  :label="
                    $vuetify.lang.t('$vuetify.tax.permanent')
                  "
                  value="false"
                />
              </v-radio-group>
            </v-col>

            <v-col
              v-if="
                editDiscount.value &&
                  editDiscount.value !== 0.0 &&
                  editDiscount.value !== 0.0 &&
                  editDiscount.percent === 'true'
              "
              cols="12"
              md="12"
            >
              <i style="color: green">{{
                $vuetify.lang.t(
                  '$vuetify.tax.example',
                  [editDiscount.value],
                  [user.company.currency]
                )
              }}</i>
            </v-col>
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
          {{ $vuetify.lang.t("$vuetify.actions.cancel") }}
        </v-btn>
        <v-btn
          :disabled="!formValid || isActionInProgress"
          :loading="isActionInProgress"
          class="mb-2"
          color="primary"
          @click="updateDiscountHandler"
        >
          <v-icon>mdi-content-save</v-icon>
          {{ $vuetify.lang.t("$vuetify.actions.save") }}
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import { mapActions, mapGetters, mapState } from 'vuex'

export default {
  name: 'EditDiscount',
  data () {
    return {
      formValid: false,
      errorPhone: null,
      formRule: this.$rules
    }
  },
  computed: {
    ...mapGetters('auth', ['user']),
    ...mapState('discount', ['saved', 'editDiscount', 'isActionInProgress'])
  },
  methods: {
    ...mapActions('discount', ['updateDiscount', 'toogleEditModal']),
    async updateDiscountHandler () {
      if (this.$refs.form.validate()) {
        this.loading = true
        await this.updateDiscount(this.editDiscount)
      }
    }
  }
}
</script>

<style scoped></style>
