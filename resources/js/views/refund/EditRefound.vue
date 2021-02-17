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
                v-model="editRefund.cant"
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
                v-model="editRefund.money"
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
	name: 'EditRefound',
	data () {
		return {
			formValid: false,
			formRule: this.$rules
		}
	},
	computed: {
		...mapState('refund', ['saved', 'editRefund', 'isActionInProgress']),
		disabledButon () {
			return this.editRefund.cant > 0 || this.editRefund.money > 0
		}
	},
	methods: {
		...mapActions('refund', ['toogleEditModal', 'updateRefund']),
		async handlerRefund () {
			if (this.editRefund.cant > this.editRefund.article.cant || this.editRefund.cant < 0) {
				this.$Swal.fire({
					title: this.$vuetify.lang.t('$vuetify.titles.new', [
						this.$vuetify.lang.t('$vuetify.menu.articles')
					]),
					text: this.$vuetify.lang.t('$vuetify.rule.between', [this.$vuetify.lang.t('$vuetify.variants.cant')], [0], [this.editRefund.article.cant]),
					icon: 'warning',
					showCancelButton: false,
					confirmButtonText: this.$vuetify.lang.t(
						'$vuetify.actions.accept'
					),
					confirmButtonColor: 'red'
				})
			} else if (this.editRefund.money > this.editRefund.article.cant * this.editRefund.article.price || this.editRefund.cant < 0) {
				this.$Swal.fire({
					title: this.$vuetify.lang.t('$vuetify.titles.new', [
						this.$vuetify.lang.t('$vuetify.menu.articles')
					]),
					text: this.$vuetify.lang.t('$vuetify.rule.between',
						[this.$vuetify.lang.t('$vuetify.payment.cash')], [0], [this.editRefund.article.cant * this.editRefund.article.price]),
					icon: 'warning',
					showCancelButton: false,
					confirmButtonText: this.$vuetify.lang.t(
						'$vuetify.actions.accept'
					),
					confirmButtonColor: 'red'
				})
			} else if (this.$refs.form.validate()) {
				this.loading = true
				await this.updateRefund(this.editRefund).catch(() => {
					this.loading = false
				})
			}
		}
	}
}
</script>

<style scoped>

</style>
