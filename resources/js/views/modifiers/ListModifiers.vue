<template>
  <v-container>
    <v-row>
      <v-col
        class="py-0"
        cols="12"
      >
        <new-modifiers v-if="showNewModal" />
        <edit-modifiers v-if="showEditModal" />
        <app-data-table
          :title="
            $vuetify.lang.t('$vuetify.titles.list', [
              $vuetify.lang.t('$vuetify.menu.modifiers_list')
            ])
          "
          csv-filename="Modifiers"
          :headers="getTableColumns"
          :is-loading="isTableLoading"
          :items="modifiers"
          :manager="'mod'"
          :sort-by="['name']"
          :sort-desc="[false, true]"
          multi-sort
          @create-row="toogleNewModal(true)"
          @edit-row="openEditModal($event)"
          @delete-row="deleteModifiersHandler($event)"
        >
          <template v-slot:[`item.percent`]="{ item }">
            {{
              item.percent === "true"
                ? $vuetify.lang.t("$vuetify.tax.percent")
                : $vuetify.lang.t("$vuetify.tax.permanent")
            }}
          </template>
          <template v-slot:item.shopsNames="{ item }">
            <v-chip
              v-for="(shop, i) of item.shopsNames"
              :key="i"
            >
              {{ shop }}
            </v-chip>
          </template>
        </app-data-table>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapActions, mapState } from 'vuex'
import NewModifiers from './NewModifiers'
import EditModifiers from './EditModifiers'

export default {
  name: 'ListModifiers',
  components: { NewModifiers, EditModifiers },
  data () {
    return {
      search: ''
    }
  },
  computed: {
    ...mapState('modifiers', [
      'showNewModal',
      'showEditModal',
      'showShowModal',
      'modifiers',
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
          value: 'shopsNames',
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
    this.getModifiers()
  },
  methods: {
    ...mapActions('modifiers', [
      'toogleNewModal',
      'openEditModal',
      'openShowModal',
      'getModifiers',
      'deleteModifiers'
    ]),
    deleteModifiersHandler (modifierId) {
      this.$Swal
        .fire({
          title: this.$vuetify.lang.t('$vuetify.titles.delete', [
            this.$vuetify.lang.t('$vuetify.menu.modifiers')
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
          if (result.value) this.deleteModifiers(modifierId)
        })
    }
  }
}
</script>

<style scoped></style>
