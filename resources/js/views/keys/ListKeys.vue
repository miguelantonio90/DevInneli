<template>
  <v-container>
    <v-row>
      <v-col
        class="py-0"
        cols="12"
      >
        <new-keys v-if="showNewModal" />
        <edit-keys v-if="showEditModal" />
        <v-card>
          <app-data-table
            :headers="getTableColumns"
            :is-loading="isTableLoading"
            :items="keys"
            :manager="'key'"
            :sort-by="['key']"
            :sort-desc="[false, true]"
            :title="
              $vuetify.lang.t('$vuetify.titles.list', [
                $vuetify.lang.t('$vuetify.menu.keys')
              ])
            "
            csv-filename="Keys"
            multi-sort
            @create-row="toogleNewModal(true)"
            @edit-row="openEditModal($event)"
            @delete-row="deleteKeyHandler($event)"
          >
            <template v-slot:[`item.key`]="{ item }">
              <v-icon>mdi-account-key</v-icon>
              {{ item.key }}
            </template>
          </app-data-table>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapActions, mapState } from 'vuex'
import NewKeys from './NewKeys'
import EditKeys from './EditKeys'

export default {
  name: 'ListKeys',
  components: {
    NewKeys,
    EditKeys
  },
  data () {
    return {
      search: ''
    }
  },
  computed: {
    ...mapState('keys', [
      'showNewModal',
      'showEditModal',
      'showShowModal',
      'keys',
      'isTableLoading'
    ]),
    getTableColumns () {
      return [
        {
          text: this.$vuetify.lang.t('$vuetify.access.name'),
          value: 'key',
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
    this.getKeys()
  },
  methods: {
    ...mapActions('keys', [
      'toogleNewModal',
      'openEditModal',
      'openShowModal',
      'getKeys',
      'deleteKey'
    ]),
    deleteKeyHandler (keyId) {
      this.$Swal
        .fire({
          title: this.$vuetify.lang.t('$vuetify.titles.delete', [
            this.$vuetify.lang.t('$vuetify.menu.access')
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
          if (result.value) this.deleteKey(keyId)
        })
    }
  }
}
</script>

<style scoped></style>
