<template>
  <v-container>
    <v-row>
      <v-col
        class="py-0"
        cols="12"
      >
        <new-tax v-if="showNewModal" />
        <edit-tax v-if="showEditModal" />
        <app-data-table
          :title="$vuetify.lang.t('$vuetify.titles.list',
                                  [$vuetify.lang.t('$vuetify.menu.tax_list'),])"
          csv-filename="Taxes"
          :headers="getTableColumns"
          :is-loading="isTableLoading"
          :items="taxes"
          :manager="'tax'"
          :sort-by="['name']"
          :sort-desc="[false, true]"
          multi-sort
          @create-row="toogleNewModal(true)"
          @edit-row="editTaxHandler($event)"
          @delete-row="deleteTaxHandler($event)"
        >
          <template
            v-slot:[`item.percent`]="{ item }"
          >
            {{ item.percent ==="true" ? $vuetify.lang.t('$vuetify.tax.percent'):$vuetify.lang.t('$vuetify.tax.permanent') }}
          </template>
        </app-data-table>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapActions, mapState } from 'vuex'
import NewTax from './NewTax'
import EditTax from './EditTax'

export default {
  name: 'ListTax',
  components: {
    EditTax,
    NewTax
  },
  data () {
    return {
      search: ''
    }
  },
  computed: {
    ...mapState('tax', [
      'showNewModal',
      'showEditModal',
      'showShowModal',
      'taxes',
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
    this.getTaxes()
  },
  methods: {
    ...mapActions('tax', [
      'toogleNewModal',
      'openEditModal',
      'openShowModal',
      'getTaxes',
      'deleteTax'
    ]),
    editTaxHandler ($event) {
      this.openEditModal($event)
    },
    deleteTaxHandler (taxId) {
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
        .then((result) => {
          if (result.value) this.deleteTax(taxId)
        })
    }
  }
}
</script>

<style scoped></style>
