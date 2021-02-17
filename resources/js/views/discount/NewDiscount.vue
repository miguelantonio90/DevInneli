<template>
  <v-dialog
    v-model="toogleNewModal"
    max-width="450"
    persistent
  >
    <v-card>
      <v-card-title>
        <span class="headline">{{
          $vuetify.lang.t('$vuetify.titles.new', [
            $vuetify.lang.t('$vuetify.menu.discount'),
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
                v-model="newDiscount.name"
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
                v-model="newDiscount.value"
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
              v-model="newDiscount.percent"
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
          @click="createNewDiscount"
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
	name: 'NewDiscount',
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
		...mapState('discount', ['saved', 'newDiscount', 'isActionInProgress'])
	},
	created () {
		this.formValid = false
	},
	methods: {
		...mapActions('discount', ['createDiscount', 'toogleNewModal']),
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
		async createNewDiscount () {
			if (this.$refs.form.validate()) {
				this.loading = true
				await this.createDiscount(this.newDiscount).catch(() => {
					this.loading = false
				})
			}
		}
	}
}
</script>

<style scoped>
</style>
