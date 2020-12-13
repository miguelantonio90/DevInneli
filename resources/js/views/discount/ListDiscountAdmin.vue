<template>
  <v-container>
    <v-row>
      <v-col
        class="py-0"
        cols="12"
      >
        <app-data-table
          :title="$vuetify.lang.t('$vuetify.titles.list',
                                  [$vuetify.lang.t('$vuetify.menu.discount'),])"
          csv-filename="Discounts"
          :headers="getTableColumns"
          :is-loading="isTableLoading"
          :items="discounts"
          :manager="'discount'"
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
import { mapActions, mapGetters, mapState } from 'vuex'

export default {
  name: 'ListDiscountAdmin',
  data () {
    return {
      search: ''
    }
  },
  computed: {
    ...mapState('discount', [
      'discounts',
      'isTableLoading'
    ]),
    ...mapGetters('statics', ['arrayCountry']),
    getTableColumns () {
      return [
        {
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
          text: this.$vuetify.lang.t('$vuetify.tax.value'),
          value: 'value'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.tax.rate'),
          value: 'percent'
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
            this.$vuetify.lang.t('$vuetify.menu.tax')
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
          if (result.value) this.deleteDiscount(taxId)
        })
    }
  }
}
</script>

<style scoped></style>
