<template>
  <v-container>
    <v-row>
      <v-col
        class="py-0"
        cols="12"
      >
        <app-data-table
          :title="$vuetify.lang.t('$vuetify.menu.pay')"
          csv-filename="Categories"
          :headers="getTableColumns"
          :is-loading="isTableLoading"
          :items="payments"
          :manager="'payment'"
          :sort-by="['name']"
          :sort-desc="[false, true]"
          multi-sort
        >
          <template v-slot:[`item.company.name`]="{ item }">
            <template>
              {{ item.company.name }}
              <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                  <v-chip
                    v-bind="attrs"
                    v-on="on"
                  >
                    <v-avatar left>
                      {{ arrayCountry.filter(cou=>cou.id===item.company.country)[0].emoji }}
                    </v-avatar>
                    {{ item.country }}
                  </v-chip>
                </template>
                <span>{{ arrayCountry.filter(cou=>cou.id===item.company.country)[0].name }}</span>
              </v-tooltip>
            </template>
          </template>
        </app-data-table>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapActions, mapGetters, mapState } from 'vuex'
export default {
	name: 'ListPaymentAdmin',
	data () {
		return {
			search: ''
		}
	},
	computed: {
		...mapState('payment', [
			'showNewModal',
			'showEditModal',
			'showShowModal',
			'payments',
			'isTableLoading'
		]),
		...mapGetters('statics', ['arrayCountry']),
		getTableColumns () {
			return [{
				text: this.$vuetify.lang.t('$vuetify.company'),
				value: 'company.name',
				select_filter: true
			},
			{
				text: this.$vuetify.lang.t('$vuetify.firstName'),
				value: 'name',
				select_filter: true
			},
			{
				text: this.$vuetify.lang.t('$vuetify.menu.pay'),
				value: 'enEs'
			},
			{
				text: this.$vuetify.lang.t('$vuetify.actions.actions'),
				value: 'actions',
				sortable: false
			}
			]
		}
	},
	created () {
		this.getPayments()
	},
	methods: {
		...mapActions('payment', [
			'toogleNewModal',
			'openEditModal',
			'openShowModal',
			'getPayments',
			'deletePayment'
		]),
		editPaymentHandler ($event) {
			this.openEditModal($event)
		},
		deletePaymentHandler (id) {
			this.$Swal
				.fire({
					title: this.$vuetify.lang.t('$vuetify.titles.delete', [
						this.$vuetify.lang.t('$vuetify.menu.pay')
					]),
					text: this.$vuetify.lang.t(
						'$vuetify.messages.warning_delete'
					),
					icon: 'warning',
					showCancelButton: true,
					cancelButtonText: this.$vuetify.lang.t(
						'$vuetify.actions.cancel'
					),
					confirmButtonText: this.$vuetify.lang.t(
						'$vuetify.actions.delete'
					),
					confirmButtonColor: 'red'
				})
				.then((result) => {
					if (result.value) this.deletePayment(id)
				})
		}
	}
}
</script>

<style scoped>

</style>
