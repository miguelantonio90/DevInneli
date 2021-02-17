<template>
  <v-container>
    <v-row>
      <v-col
        class="py-0"
        cols="12"
      >
        <new-expense-category v-if="showNewModal" />
        <edit-expense-category v-if="showEditModal" />
        <app-data-table
          :title="$vuetify.lang.t('$vuetify.menu.expense_category_list')"
          csv-filename="ExpenseCategories"
          :headers="getTableColumns"
          :is-loading="isTableLoading"
          :items="categories"
          :manager="'expense_category'"
          :sort-by="['name']"
          :sort-desc="[false, true]"
          multi-sort
          @create-row="toogleNewModal(true)"
          @edit-row="openEditModal($event)"
          @delete-row="deleteCategoryHandler($event)"
        />
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapActions, mapState } from 'vuex'
import NewExpenseCategory from './New'
import EditExpenseCategory from './Edit'

export default {
	name: 'ListExpenseCategory',
	components: {
		NewExpenseCategory,
		EditExpenseCategory
	},
	data () {
		return {
			search: ''
		}
	},
	computed: {
		...mapState('expenseCategory', [
			'showNewModal',
			'showEditModal',
			'showShowModal',
			'categories',
			'isTableLoading'
		]),
		getTableColumns () {
			return [
				{
					text: this.$vuetify.lang.t('$vuetify.firstName'),
					value: 'name',
					select_filter: true
				},
				{
					text: this.$vuetify.lang.t('$vuetify.access.description'),
					value: 'description'
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
		this.getExpenseCategories()
	},
	methods: {
		...mapActions('expenseCategory', [
			'toogleNewModal',
			'openEditModal',
			'openShowModal',
			'getExpenseCategories',
			'deleteCategory'
		]),
		deleteCategoryHandler (categoryId) {
			this.$Swal
				.fire({
					title: this.$vuetify.lang.t('$vuetify.titles.delete', [
						this.$vuetify.lang.t('$vuetify.menu.expense_category')
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
					if (result.value) this.deleteCategory(categoryId)
				})
		}
	}
}
</script>

<style scoped></style>
