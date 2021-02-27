<template>
  <div :id="id">
    <div class="d-flex flex-row justify-space-between align-center">
      <div class="d-flex flex-row align-center">
        <v-text-field
          v-model="searchValue"
          :label="$vuetify.lang.t('$vuetify.actions.search')"
          append-icon="mdi-magnify"
        />
        <v-menu
          v-model="showFilterMenu"
          :close-on-content-click="false"
          :nudge-width="300"
          offset-x
          transition="scale-transition"
        >
          <template v-slot:activator="{ on: onMenu }">
            <v-tooltip bottom>
              <template v-slot:activator="{ on: onTooltip }">
                <v-badge
                  :value="filtersEnabledCount > 0"
                  color="accent"
                  :content="filtersEnabledCount"
                  overlap
                >
                  <div v-on="onTooltip">
                    <v-btn
                      :disabled="!hasFilters || loading"
                      fab
                      x-small
                      v-on="onMenu"
                    >
                      <v-icon>mdi-filter</v-icon>
                    </v-btn>
                  </div>
                </v-badge>
              </template>
              <span v-if="loading">{{ $vuetify.lang.t('$vuetify.component.loading') }}</span>
              <div v-else>
                <span v-if="hasFilters">{{ $vuetify.lang.t('$vuetify.component.filter_data') }}</span>
                <span v-if="!hasFilters">{{ $vuetify.lang.t('$vuetify.component.filter_disabled') }}</span>
              </div>
            </v-tooltip>
          </template>

          <v-card>
            <v-card-title>
              {{ $vuetify.lang.t('$vuetify.component.filter_data') }}
              <v-spacer />
              <v-btn
                small
                dark
                @click="clearFilters()"
              >
                <v-icon left>
                  mdi-close
                </v-icon>
                {{ $vuetify.lang.t('$vuetify.component.clear_filters') }}
              </v-btn>
            </v-card-title>
            <v-divider />
            <v-card-subtitle v-if="!hasFilters">
              {{ $vuetify.lang.t('$vuetify.component.no_filters') }}
            </v-card-subtitle>

            <v-card-subtitle v-if="$options.filters.haslength(selectManyFilters)">
              {{ $vuetify.lang.t('$vuetify.component.active_many_filter') }}
            </v-card-subtitle>

            <v-card-text>
              <v-list dense>
                <v-list-item
                  v-for="(f, i) in selectManyFilters"
                  :key="i"
                  dense
                  class="ma-0 pa-0"
                >
                  <v-select
                    v-model="f.model"
                    :label="f.label"
                    multiple
                    chips
                    dense
                    :items="f.items"
                    @change="onChangedFilters()"
                  />
                </v-list-item>
              </v-list>
            </v-card-text>

            <v-card-subtitle v-if="$options.filters.haslength(selectFilters)">
              {{ $vuetify.lang.t('$vuetify.component.active_select_filter') }}
            </v-card-subtitle>

            <v-card-text>
              <v-list dense>
                <v-list-item
                  v-for="(f, i) in selectFilters"
                  :key="i"
                  dense
                  class="ma-0 pa-0"
                >
                  <v-select
                    v-model="f.model"
                    :label="f.label"
                    multiple
                    chips
                    dense
                    :items="f.items"
                    @change="onChangedFilters()"
                  />
                </v-list-item>
              </v-list>
            </v-card-text>

            <v-card-subtitle v-if="$options.filters.haslength(checkboxFilters)">
              {{ $vuetify.lang.t('$vuetify.component.active_switch_filter') }}
            </v-card-subtitle>

            <v-card-text v-if="$options.filters.haslength(checkboxFilters)">
              <v-list dense>
                <v-list-item
                  v-for="(f, i) in checkboxFilters"
                  :key="i"
                  dense
                  class="ma-0 pa-0"
                >
                  <v-checkbox
                    v-model="f.model"
                    :label="f.label"
                    @change="onChangedFilters()"
                  />
                </v-list-item>
              </v-list>
            </v-card-text>
          </v-card>
        </v-menu>
      </div>

      <div class="d-flex flex-row">
        <v-tooltip
          v-if="hasCsvImport"
          bottom
        >
          <template v-slot:activator="{ on: onTooltip }">
            <div
              class="mr-1"
              v-on="onTooltip"
            >
              <v-btn
                fab
                x-small
                @click="onClickImport()"
              >
                <v-icon>mdi-file-upload</v-icon>
              </v-btn>
            </div>
          </template>
          <span>{{ $vuetify.lang.t('$vuetify.component.upload_csv') }}</span>
        </v-tooltip>
        <v-tooltip
          v-if="hasCsvExport"
          bottom
        >
          <template v-slot:activator="{ on: onTooltip }">
            <div
              class="mr-1"
              v-on="onTooltip"
            >
              <v-btn
                fab
                x-small
                @click="onClickExport()"
              >
                <v-icon>mdi-file-download</v-icon>
              </v-btn>
            </div>
          </template>
          <span>{{ $vuetify.lang.t('$vuetify.component.download_csv') }}</span>
        </v-tooltip>
        <v-menu
          :close-on-content-click="false"
          offset-x
          transition="scale-transition"
        >
          <template v-slot:activator="{ on: onMenu }">
            <v-tooltip bottom>
              <template v-slot:activator="{ on: onTooltip }">
                <div v-on="onTooltip">
                  <v-btn
                    fab
                    x-small
                    v-on="onMenu"
                  >
                    <v-icon>mdi-view-column</v-icon>
                  </v-btn>
                </div>
              </template>
              <span>{{ $vuetify.lang.t('$vuetify.component.choose_columns') }}</span>
            </v-tooltip>
          </template>

          <v-card>
            <v-card-title>
              {{ $vuetify.lang.t('$vuetify.component.choose_columns') }}
              <v-spacer />
              <v-btn
                dark
                small
                @click="resetColumns()"
              >
                <v-icon left>
                  mdi-close
                </v-icon>
                {{ $vuetify.lang.t('$vuetify.component.reset_columns') }}
              </v-btn>
            </v-card-title>
            <v-divider />

            <v-card-text>
              <!-- Categories -->
              <v-list-item-content>
                <v-list-item-action class="pa-0 ma-0">
                  <v-select
                    :label="$vuetify.lang.t('$vuetify.component.select')"
                    multiple
                    chips
                    :value="headersChoosen"
                    :items="headerChoices"
                    @change="e => headersChoosenChanged(e)"
                  />
                </v-list-item-action>
              </v-list-item-content>
            </v-card-text>
          </v-card>
        </v-menu>
      </div>
    </div>
  </div>
</template>

<script>
/* eslint-disable vue/require-prop-types */
export default {
  filters: {
    haslength (val) {
      return Array.isArray(val) && !!val.length
    }
  },
  props: [
    'id',
    'hasCsvImport',
    'hasCsvExport',
    'headersChoosen',
    'headerChoices',
    'selectManyFilters',
    'selectFilters',
    'checkboxFilters',
    'filtersEnabledCount',
    'hasFilters',
    'loading'
  ],
  data () {
    return {
      searchValue: null,
      showFilterMenu: null
    }
  },
  watch: {
    searchValue (newValue) {
      this.$emit('searchValueChanged', newValue)
    },
    showFilterMenu (newValue) {
      this.$emit('showFilterMenuChanged', newValue)
    }
  },
  methods: {
    clearFilters () {
      this.$emit('clearFilters')
    },
    onChangedFilters () {
      this.$emit('onChangedFilters')
    },
    resetColumns () {
      this.$emit('resetColumns')
    },
    headersChoosenChanged (e) {
      this.$emit('headersChoosenChanged', e)
    },
    onClickExport () {
      this.$emit('onClickExport')
    },
    onClickImport () {
      this.$emit('onClickImport')
    }
  }
}
</script>

<style scoped>
.align-center {
  align-items: center;
}
.d-flex {
  display: flex;
}
.flex-row {
  flex-direction: row;
}
.justify-space-between {
  justify-content: space-between;
}
.ma-0 {
  margin: 0;
}
.pa-0 {
  padding: 0;
}
.mr-1 {
  margin-right: 1em;
}
</style>
