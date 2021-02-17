<template>
  <v-container>
    <v-row>
      <v-col
        class="py-0"
        cols="12"
      >
        <app-data-table
          :title="$vuetify.lang.t('$vuetify.menu.category_list')"
          csv-filename="Categories"
          :headers="getTableColumns"
          :is-loading="isTableLoading"
          :items="categories"
          :manager="'category'"
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
                    {{ item.company.country }}
                  </v-chip>
                </template>
                <span>{{ arrayCountry.filter(cou=>cou.id===item.company.country)[0].name }}</span>
              </v-tooltip>
            </template>
          </template>
          <template
            v-slot:[`item.color`]="{ item }"
          >
            <v-chip
              :color="item.color"
              dark
            >
              {{ item.color }}
            </v-chip>
          </template>
        </app-data-table>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapActions, mapGetters, mapState } from 'vuex'

export default {
	name: 'ListBoxAdmin',
	data () {
		return {
			search: ''
		}
	},
	computed: {
		...mapState('category', [
			'categories',
			'isTableLoading'
		]),
		getTableColumns () {
			return [
				{
					text: this.$vuetify.lang.t('$vuetify.company'),
					value: 'company.name'
				},
				{
					text: this.$vuetify.lang.t('$vuetify.firstName'),
					value: 'name',
					select_filter: true
				},
				{
					text: this.$vuetify.lang.t('$vuetify.color'),
					value: 'color'
				}
			]
		}
	},
	created () {
		this.getCategories()
	},
	methods: {
		...mapActions('category', [
			'getCategories'
		])
	}
}
</script>

<style scoped></style>
