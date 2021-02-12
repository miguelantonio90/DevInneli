<template>
  <v-container>
    <v-row>
      <v-col
        class="py-0"
        cols="12"
      >
        <new-box v-if="showNewModal" />
        <edit-box v-if="showEditModal" />
        <open-close-box v-if="opencloseBox" />
        <app-data-table
          :title="$vuetify.lang.t('$vuetify.menu.boxes_list')"
          csv-filename="Boxes"
          :headers="getTableColumns"
          :is-loading="isTableLoading"
          :items="boxes"
          :manager="'boxes'"
          :sort-by="['name']"
          :sort-desc="[false, true]"
          multi-sort
          @create-row="toogleNewModal(true)"
          @edit-row="editBoxesHandler($event)"
          @delete-row="deleteBoxesHandler($event)"
        >
          <template v-slot:[`item.state`]="{ item }">
            <v-tooltip top>
              <template v-slot:activator="{ on, attrs }">
                <v-icon
                  v-if="item.state === 'open'"
                  class="mr-2"
                  small
                  color="primary"
                  v-bind="attrs"
                  v-on="on"
                  @click="openBox(item.id)"
                >
                  mdi-lock
                </v-icon>
              </template>
              <span>{{ $vuetify.lang.t('$vuetify.access.access.boxes_close') }}</span>
            </v-tooltip>
            <v-tooltip top>
              <template v-slot:activator="{ on, attrs }">
                <v-icon
                  v-if="item.state !== 'open'"
                  class="mr-2"
                  color="primary"
                  small
                  v-bind="attrs"
                  v-on="on"
                  @click="openBox(item.id)"
                >
                  mdi-lock-open
                </v-icon>
              </template>
              <span>{{ $vuetify.lang.t('$vuetify.access.access.boxes_open') }}</span>
            </v-tooltip>
            {{ $vuetify.lang.t('$vuetify.sale.state.' + item.state) }}
          </template>
        </app-data-table>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapActions, mapState } from 'vuex'
import NewBox from './NewBox'
import EditBox from './EditBox'
import OpenCloseBox from './OpenCloseBox'

export default {
	name: 'ListBoxes',
	components: {
		NewBox,
		EditBox,
		OpenCloseBox
	},
	data () {
		return {
			search: ''
		}
	},
	computed: {
		...mapState('boxes', [
			'opencloseBox',
			'showNewModal',
			'showEditModal',
			'showShowModal',
			'boxes',
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
					text: this.$vuetify.lang.t('$vuetify.menu.shop'),
					value: 'shop.name'
				},
				{
					text: this.$vuetify.lang.t('$vuetify.state'),
					value: 'state'
				},
				{
					text: this.$vuetify.lang.t('$vuetify.actions.actions'),
					value: 'actions',
					sortable: false
				}
			]
		}
	},
	async created () {
		await this.getBoxes()
	},
	methods: {
		...mapActions('boxes', [
			'toogleNewModal',
			'openEditModal',
			'openCloseModal',
			'getBoxes',
			'deleteBox'
		]),
		editBoxesHandler ($event) {
			this.openEditModal($event)
		},
		openBox ($event) {
			this.openCloseModal($event)
		},
		deleteBoxesHandler (categoryId) {
			this.$Swal
				.fire({
					title: this.$vuetify.lang.t('$vuetify.titles.delete', [
						this.$vuetify.lang.t('$vuetify.menu.category')
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
					if (result.value) this.deleteBox(categoryId)
				})
		}
	}
}
</script>

<style scoped></style>
