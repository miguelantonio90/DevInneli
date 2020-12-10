<template>
  <v-container>
    <v-row>
      <v-col
        class="py-0"
        cols="12"
      >
        <new-supplier v-if="showNewModal" />
        <edit-supplier v-if="showEditModal" />
        <app-data-table
          :title="$vuetify.lang.t('$vuetify.titles.list',
                                  [$vuetify.lang.t('$vuetify.menu.supplier')])"
          csv-filename="Categories"
          :headers="getTableColumns"
          :items="suppliers"
          :manager="'supplier'"
          :sort-by="['firstName']"
          :sort-desc="[false, true]"
          multi-sort
          @create-row="toogleNewModal(true)"
          @edit-row="openEditModal($event)"
          @delete-row="deleteSupplierHandler($event)"
        >
          <template v-slot:[`item.nameCountry`]="{ item }">
            <v-tooltip bottom>
              <template v-slot:activator="{ on, attrs }">
                <v-chip
                  v-bind="attrs"
                  v-on="on"
                >
                  <v-avatar left>
                    {{ item.countryFlag }}
                  </v-avatar>
                  {{ item.country }}
                </v-chip>
              </template>
              <span>{{ item.nameCountry }}</span>
            </v-tooltip>
          </template>
        </app-data-table>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapActions, mapState } from 'vuex'
import NewSupplier from './NewSupplier'
import EditSupplier from './EditSupplier'

export default {
  name: 'ListSupplier',
  components: {
    EditSupplier,
    NewSupplier
  },
  data () {
    return {
      search: ''
    }
  },
  computed: {
    ...mapState('supplier', [
      'showNewModal',
      'showEditModal',
      'showShowModal',
      'suppliers',
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
          text: this.$vuetify.lang.t('$vuetify.email'),
          value: 'email'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.country'),
          value: 'nameCountry',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.phone'),
          value: 'phone',
          select_filter: true
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
    this.getSuppliers()
  },
  methods: {
    ...mapActions('supplier', [
      'toogleNewModal',
      'openEditModal',
      'openShowModal',
      'getSuppliers',
      'deleteSupplier'
    ]),
    deleteSupplierHandler (id) {
      this.$Swal
        .fire({
          title: this.$vuetify.lang.t('$vuetify.titles.delete', [
            this.$vuetify.lang.t('$vuetify.menu.supplier')
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
          if (result.value) this.deleteSupplier(id)
        })
    }
  }
}
</script>

<style scoped></style>
