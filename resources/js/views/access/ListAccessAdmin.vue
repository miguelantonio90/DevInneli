<template>
  <v-container>
    <v-row>
      <v-col
        class="py-0"
        cols="12"
      >
        <v-card>
          <app-data-table
            :title="
              $vuetify.lang.t('$vuetify.titles.list', [
                $vuetify.lang.t('$vuetify.menu.access')
              ])
            "
            :is-loading="isTableLoading"
            csv-filename="Access"
            :headers="getTableColumns"
            :items="roles"
            :manager="'access'"
            :sort-by="['name']"
            :sort-desc="[false, true]"
            multi-sort
            @create-row="toogleNewModal(true)"
            @edit-row="openEditModal($event)"
            @delete-row="deleteRole($event)"
          >
            <template v-slot:[`item.company_name`]="{ item }">
              <template>
                {{ item.company_name }}
                <v-tooltip bottom>
                  <template v-slot:activator="{ on, attrs }">
                    <v-chip
                      v-bind="attrs"
                      v-on="on"
                    >
                      <v-avatar left>
                        {{
                          arrayCountry.filter(
                            cou =>
                              cou.id ===
                              item.country
                          )[0].emoji
                        }}
                      </v-avatar>
                      {{ item.country }}
                    </v-chip>
                  </template>
                  <span>{{
                    arrayCountry.filter(
                      cou => cou.id === item.country
                    )[0].name
                  }}</span>
                </v-tooltip>
              </template>
            </template>
            <template v-slot:[`item.name`]="{ item }">
              <v-icon>mdi-account-key</v-icon>
              {{ item.name }}
            </template>
            <template v-slot:[`item.accessEmail`]="{ item }">
              <v-icon
                v-if="item.accessEmail"
                small
              >
                mdi-check
              </v-icon>
              <v-icon
                v-else
                small
              >
                mdi-cancel
              </v-icon>
            </template>
            <template v-slot:[`item.accessPin`]="{ item }">
              <v-icon
                v-if="item.accessPin"
                small
              >
                mdi-check
              </v-icon>
              <v-icon
                v-else
                small
              >
                mdi-cancel
              </v-icon>
            </template>
          </app-data-table>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapActions, mapGetters, mapState } from 'vuex'

export default {
  name: 'ListAccessAdmin',
  data () {
    return {
      search: ''
    }
  },
  computed: {
    ...mapState('role', [
      'showNewModal',
      'showEditModal',
      'showShowModal',
      'roles',
      'isTableLoading'
    ]),
    ...mapGetters('statics', ['arrayCountry']),
    getTableColumns () {
      return [
        {
          text: this.$vuetify.lang.t('$vuetify.company'),
          value: 'company_name',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.access.name'),
          value: 'name',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.access.accessPin'),
          value: 'accessPin'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.access.accessEmail'),
          value: 'accessEmail'
        }
      ]
    }
  },
  created () {
    this.getRoles()
  },
  methods: {
    ...mapActions('role', [
      'toogleNewModal',
      'openEditModal',
      'openShowModal',
      'getRoles',
      'deleteRole'
    ]),
    deleteRoleHandler (roleId) {
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
          if (result.value) this.deleteRole(roleId)
        })
    }
  }
}
</script>

<style scoped></style>
