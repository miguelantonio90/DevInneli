<template>
  <v-container>
    <v-row>
      <v-col
        class="py-0"
        cols="12"
      >
        <app-data-table
          :headers="getTableColumns"
          :is-loading="isTableLoading"
          :items="users"
          :manager="'employer'"
          :sort-by="['firstName']"
          :sort-desc="[false, true]"
          :title="
            $vuetify.lang.t('$vuetify.titles.list', [
              $vuetify.lang.t('$vuetify.menu.user')
            ])
          "
          csv-filename="Employees"
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
                      {{
                        arrayCountry.filter(
                          cou =>
                            cou.id ===
                            item.company.country
                        )[0].emoji
                      }}
                    </v-avatar>
                    {{ item.company.country }}
                  </v-chip>
                </template>
                <span>{{
                  arrayCountry.filter(
                    cou => cou.id === item.company.country
                  )[0].name
                }}</span>
              </v-tooltip>
            </template>
          </template>
          <template v-slot:[`item.firstName`]="{ item }">
            <v-avatar>
              <v-img
                :src="
                  item.avatar ||
                    `/assets/avatar/avatar-undefined.jpg`
                "
              />
            </v-avatar>
            {{ item.firstName }}
          </template>
          <template v-slot:[`item.shopsNames`]="{ item }">
            <v-chip
              v-for="(shop, i) of item.shopsNames"
              :key="i"
            >
              {{ shop }}
            </v-chip>
          </template>
          <template v-slot:[`item.country`]="{ item }">
            <v-tooltip bottom>
              <template v-slot:activator="{ on, attrs }">
                <v-chip
                  v-bind="attrs"
                  v-on="on"
                >
                  <v-avatar left>
                    {{
                      arrayCountry.filter(
                        cou => cou.id === item.country
                      )[0].emoji
                    }}
                  </v-avatar>
                  {{ item.country }}
                </v-chip>
              </template>
              <span>{{
                arrayCountry.filter(
                  cou => cou.id === item.company.country
                )[0].name
              }}</span>
            </v-tooltip>
          </template>
        </app-data-table>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapActions, mapGetters, mapState } from 'vuex'

export default {
  data () {
    return {
      search: ''
    }
  },
  computed: {
    ...mapState('user', ['showShowModal', 'users', 'isTableLoading']),
    ...mapGetters('statics', ['arrayCountry']),
    getTableColumns () {
      return [
        {
          text: this.$vuetify.lang.t('$vuetify.company'),
          value: 'company.name'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.firstName'),
          value: 'firstName',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.lastName'),
          value: 'lastName'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.country'),
          value: 'country'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.email'),
          value: 'email'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.position'),
          value: 'position.name',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.menu.shop'),
          value: 'shopsNames',
          select_filter_many: true
        }
      ]
    }
  },
  created () {
    this.getUsers()
  },
  methods: {
    ...mapActions('user', [
      'toogleNewModal',
      'openEditModal',
      'openShowModal',
      'getUsers',
      'deleteUser'
    ])
  }
}
</script>

<style scoped></style>
