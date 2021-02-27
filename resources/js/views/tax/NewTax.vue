<template>
  <v-dialog
      v-model="toogleNewModal"
      max-width="500"
      persistent
  >
    <v-card>
      <v-card-title>
        <span class="headline">{{
            $vuetify.lang.t('$vuetify.titles.new', [
              $vuetify.lang.t('$vuetify.tax.name')
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
                class="py-0"
                cols="12"
                md="6"
            >
              <v-text-field
                  v-model="newTax.name"
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
                  v-model="newTax.value"
                  :label="$vuetify.lang.t('$vuetify.tax.value')"
                  :options="{
                  length: 15,
                  precision: 2,
                  empty: 0.0
                }"
                  :properties="{
                  clearable: true
                }"
                  :rules="formRule.required"
                  required
              />
            </v-col>
            <v-col
                class="py-0"
                cols="12"
                md="6"
            >
              <h4>{{ $vuetify.lang.t('$vuetify.tax.rate') }}</h4>
              <v-radio-group
                  v-model="newTax.percent"
                  row
              >
                <v-radio
                    :label="
                    $vuetify.lang.t('$vuetify.tax.percent')
                  "
                    value="true"
                />
                <v-radio
                    :label="
                    $vuetify.lang.t(
                      '$vuetify.tax.permanent'
                    )
                  "
                    value="false"
                />
              </v-radio-group>
            </v-col>
            <v-col
                class="py-0"
                cols="12"
                md="6"
            >
              <v-select
                  v-model="newTax.type"
                  :items="getTypeTax"
                  :label="$vuetify.lang.t('$vuetify.tax.type')"
                  :rules="formRule.country"
                  clearable
                  item-text="text"
                  item-value="value"
                  required
              />
            </v-col>
            <v-col md="6">
              <v-switch
                  v-model="newTax.existing"
                  :label="
                  $vuetify.lang.t('$vuetify.tax.option_tax')
                "
              />
            </v-col>
            <v-col
                v-if="
                newTax.value &&
                  newTax.value !== 0.0 &&
                  newTax.value !== 0.0 &&
                  newTax.percent === 'true'
              "
                md="12"
            >
              <i style="color: green">{{
                  $vuetify.lang.t(
                      '$vuetify.tax.example',
                      [newTax.value],
                      [user.company.currency]
                  )
                }}</i>
            </v-col>
          </v-row>
        </v-form>
      </v-card-text>
      <v-card-actions>
        <v-spacer/>
        <v-btn
            :disabled="isActionInProgress"
            class="mb-2"
            @click="toogleNewModal(false)"
        >
          <v-icon>mdi-close</v-icon>
          {{ $vuetify.lang.t('$vuetify.actions.cancel') }}
        </v-btn>
        <v-btn
            :disabled="!formValid || isActionInProgress"
            :loading="isActionInProgress"
            class="mb-2"
            color="primary"
            @click="createNewTaxHandler"
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
  name: 'NewTax',
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
    ...mapState('tax', ['saved', 'newTax', 'isActionInProgress']),
    ...mapGetters('auth', ['user']),
    getTypeTax () {
      return [
        {
          text: this.$vuetify.lang.t('$vuetify.tax.include_tax'),
          value: 'included'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.tax.added_tax'),
          value: 'added'
        }
      ]
    }
  },
  created () {
    this.formValid = false
  },
  methods: {
    ...mapActions('tax', ['createTax', 'toogleNewModal']),
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
    async validCreate () {
      this.loading = true
      await this.createTax(this.newTax).catch(() => {
        this.loading = false
      })
    },
    createNewTaxHandler () {
      if (this.$refs.form.validate()) {
        if (!this.newTax.value || this.newTax.value <= 0) {
          this.$Swal
              .fire({
                title: this.$vuetify.lang.t('$vuetify.titles.new', [
                  this.$vuetify.lang.t('$vuetify.articles.tax')
                ]),
                text: this.$vuetify.lang.t(
                    '$vuetify.messages.warning_value_tax'
                ),
                icon: 'warning',
                showCancelButton: false,
                confirmButtonText: this.$vuetify.lang.t(
                    '$vuetify.actions.accept'
                ),
                confirmButtonColor: 'red'
              })
              .then(result => {
              })
        } else {
          this.validCreate()
        }
      }
    }
  }
}
</script>

<style scoped></style>
