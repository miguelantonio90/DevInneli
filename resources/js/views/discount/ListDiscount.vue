<template>
  <v-container>
    <v-row>
      <v-col
        class="py-0"
        cols="12"
      >
        <new-discount v-if="showNewModal" />
        <edit-discount v-if="showEditModal" />
        <app-data-table
          :title="
            $vuetify.lang.t('$vuetify.titles.list', [
              $vuetify.lang.t('$vuetify.menu.discount')
            ])
          "
          csv-filename="Discounts"
          :headers="getTableColumns"
          :is-loading="isTableLoading"
          :items="discounts"
          :manager="'discount'"
          :sort-by="['name']"
          :sort-desc="[false, true]"
          multi-sort
          @create-row="toogleNewModal(true)"
          @edit-row="editDiscountHandler($event)"
          @delete-row="deleteDiscountHandler($event)"
        >
          <template v-slot:[`item.value`]="{ item }">
            {{ parseFloat(item.value).toFixed(2) }}
          </template>
          <template v-slot:[`item.percent`]="{ item }">
            {{
              item.percent === "true"
                ? $vuetify.lang.t("$vuetify.tax.percent")
                : $vuetify.lang.t("$vuetify.tax.permanent")
            }}
          </template>
        </app-data-table>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapActions, mapState } from 'vuex'
import NewDiscount from './NewDiscount'
import EditDiscount from './EditDiscount'

export default {
  name: 'ListDiscount',
  components: { NewDiscount, EditDiscount },
  data () {
    return {
      search: ''
    }
  },
  computed: {
    ...mapState('discount', [
      'showNewModal',
      'showEditModal',
      'showShowModal',
      'discounts',
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
          text: this.$vuetify.lang.t('$vuetify.tax.value'),
          value: 'value'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.tax.rate'),
          value: 'percent'
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
    this.getDiscounts()
  },
  methods: {
    ...mapActions('discount', [
      'toogleNewModal',
      'openEditModal',
      'openShowModal',
      'getDiscounts',
      'deleteDiscount'
    ]),
    editDiscountHandler ($event) {
      this.openEditModal($event)
    },
    deleteDiscountHandler (taxId) {
      this.$Swal
        .fire({
          title: this.$vuetify.lang.t('$vuetify.titles.delete', [
            this.$vuetify.lang.t('$vuetify.articles.tax')
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
        .then(result => {
          if (result.value) this.deleteDiscount(taxId)
        })
    }
  }
}
</script>

<style scoped></style>
