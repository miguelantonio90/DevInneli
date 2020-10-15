<template>
  <v-card>
    <v-card-title>
      {{ title }}
    </v-card-title>
    <v-container fluid>
      <!-- SEARCH BAR -->
      <filter-header
        :has-csv-export="hasCsvExport"
        :select-many-filters="selectManyFilters"
        :select-filters="selectFilters"
        :checkbox-filters="checkboxFilters"
        :filters-enabled-count="filtersEnabledCount"
        :has-filters="hasFilters"
        :loading="isLoading"
        :header-choices="headerChoices"
        :headers-choosen="headersChoosen"
        @onClickExport="onClickExport"
        @clearFilters="clearFilters"
        @onChangedFilters="onChangedFilters"
        @resetColumns="resetColumns"
        @searchValueChanged="searchValueChanged"
        @showFilterMenuChanged="showFilterMenuChanged"
        @headersChoosenChanged="headersChoosenChanged"
      />
    </v-container>
    <v-data-table
      :class="getClassStyle"
      v-bind="$attrs"
      :loading="isLoading"
      :items="itemsFiltered || []"
      :headers="headersChoosenObjs || []"
      :search="searchValueDebounced"
      v-on="$listeners"
    >
      <template v-slot:top>
        <v-toolbar flat>
          <v-spacer />
          <v-btn
            class="mb-2"
            color="primary"
            @click="createButtonClicked(true)"
          >
            <v-icon>mdi-plus</v-icon>
            {{ $vuetify.lang.t('$vuetify.actions.new') }}
          </v-btn>
        </v-toolbar>
      </template>
      <!-- Pass on all named slots -->
      <slot
        v-for="slot in Object.keys($slots)"
        :slot="slot"
        :name="slot"
      />
      <!-- Pass on all scoped slots -->
      <template
        v-for="slot in Object.keys($scopedSlots)"
        :slot="slot"
        slot-scope="scope"
      >
        <slot
          :name="slot"
          v-bind="scope"
        />
      </template>
      <template v-slot:item.actions="{ item }">
        <v-icon
          v-if="viewShowButton"
          class="mr-2"
          small
          @click="showButtonClicked(item.id)"
        >
          mdi-eye
        </v-icon>
        <v-icon
          v-if="viewEditButton"
          class="mr-2"
          small
          @click="editButtonClicked(item.id)"
        >
          mdi-pencil
        </v-icon>
        <v-icon
          v-if="viewDeleteButton"
          small
          @click="deleteButtonClicked(item.id)"
        >
          mdi-delete
        </v-icon>
      </template>
    </v-data-table>
  </v-card>
</template>

<script>
import FilterHeader from './FilterHeader'
import FiltersHandler from './helpers/filter'
import { downloadAsJson } from './helpers/json-to-csv'
import { debounce } from './debounce'
import { BehaviorSubject, combineLatest, Subject } from 'rxjs'
import { filter, takeUntil } from 'rxjs/operators'
import * as _ from 'lodash'

const DEFAULT_ARRAY = []
export default {
  name: 'AppDataTable',
  components: { FilterHeader },
  props: {
    title: {
      type: String,
      default: '',
      required: false
    },
    isLoading: {
      type: Boolean,
      default: false,
      required: false
    },
    items: {
      type: Array,
      default: DEFAULT_ARRAY
    },
    headers: {
      type: Array,
      default: DEFAULT_ARRAY
    },
    csvFilename: {
      type: String,
      default: ''
    },
    viewShowButton: {
      type: Boolean,
      default: false
    },
    viewEditButton: {
      type: Boolean,
      default: true
    },
    viewDeleteButton: {
      type: Boolean,
      default: true
    }
  },
  data () {
    return {
      search: '',
      test: null,
      searchValue: '',
      searchValueDebounced: '',
      filterHandler: new FiltersHandler(),
      showFilterMenu: false,
      o$items: new BehaviorSubject(),
      o$headers: new BehaviorSubject(),
      o$destroyed: new Subject(),
      headersChoosen: [],
      selectFilters: [],
      selectManyFilters: [],
      headersAllMap: [],
      checkboxFilters: [],
      itemsFiltered: [],
      selectFiltersRegistered: {},
      selectManyFiltersRegistered: {},
      checkboxFiltersRegistered: {}
    }
  },
  computed: {
    hasCsvExport () {
      const has = this.csvFilename
      return !!has
    },
    filtersEnabledCount () {
      const enabledSelect = this.selectFilters.filter(
        (f) => !_.isEmpty(f.model)
      )
      const enabledCheckbox = this.checkboxFilters.filter((f) => !!f.model)
      return enabledSelect.length || enabledCheckbox.length
    },
    hasFilters () {
      return !!this.allFilters.length
    },
    allFilters () {
      return [
        this.selectFilters,
        this.selectManyFilters,
        this.checkboxFilters
      ].reduce((acc, cur) => acc.concat(cur), [])
    },
    headerChoices: function () {
      return Object.values(this.headersAllMap)
    },
    headersChoosenObjs: function () {
      return this.headersChoosen
        .map((h) => this.headersAllMap[h])
        .filter((h) => !!h)
    }
  },
  watch: {
    searchValue: debounce(function (newVal) {
      this.searchValueDebounced = newVal
    }, 300),
    headers: {
      immediate: true,
      handler (newVal) {
        this.o$headers.next(newVal)
      }
    },
    items: {
      immediate: true,
      handler (newVal) {
        this.o$items.next(newVal)
      }
    }
  },
  mounted () {
    // console.log({$attrs: this.$attrs, this: this})
    combineLatest([this.o$items, this.o$headers])
      .pipe(
        filter(
          ([items, headers]) => Array.isArray(items) && Array.isArray(headers)
        )
      )
      .pipe(takeUntil(this.o$destroyed))
      .subscribe(([items, headers]) => {
        this._processHeaders(headers)
        this._processItems(items)
      })
  },
  destroyed () {
    this.o$destroyed.next()
  },
  methods: {
    createButtonClicked () {
      this.$emit('create-row')
    },
    refreshButtonClicked () {
      this.$emit('refresh-table')
    },
    editButtonClicked (clickedRowId) {
      this.$emit('edit-row', clickedRowId)
    },
    showButtonClicked (clickedRowId) {
      this.$emit('show-row', clickedRowId)
    },
    deleteButtonClicked (clickedRowId) {
      this.$emit('delete-row', clickedRowId)
    },
    getClassStyle () {
      return 'elevation-1'
    },
    onClickExport () {
      const filtered = this.itemsFiltered
      const headerValues = this.headersChoosen
      const filenamePrefix = this.csvFilename || 'exported'
      downloadAsJson(filtered, headerValues, filenamePrefix)
    },
    searchValueChanged (e) {
      this.searchValue = e
    },
    showFilterMenuChanged (e) {
      this.showFilterMenu = e
    },
    headersChoosenChanged (e) {
      this.headersChoosen = e
    },
    clearFilters () {
      this.showFilterMenu = false
      this.itemsFiltered = this.items
      this.allFilters.map((f) => (f.model = null))
    },
    onChangedFilters () {
      this._setFiltersToHandler()
      this._filterItems()
    },
    resetColumns () {
      this.headersChoosen = this.headers
        .filter((h) => !h.hide)
        .map((h) => h.value)
    },
    _setFiltersToHandler () {
      this.allFilters.map((f) => {
        // console.log('updateFilterValue: ', f.name, { ...f })
        this.filterHandler.updateFilterValue(f.name, f.model)
      })
    },
    _filterItems () {
      this.itemsFiltered = this.items.filter((item) =>
        this.filterHandler.runFilter(item)
      )
    },
    _processHeaders (newHeaders) {
      const handler = this.filterHandler

      function addFilter (fieldName, label, registered, filters, options, model, items) {
        if (registered[fieldName]) {
          return
        }
        registered[fieldName] = true
        const filter = {
          name: fieldName,
          model: [],
          label: label,
          items: []
        }
        if (model !== undefined) {
          filter.model = model
        }
        if (items !== undefined) {
          filter.items = items
        }
        filters.push(filter)
        handler.registerFilter(fieldName, options)
      }

      newHeaders
        .filter((h) => h.select_filter)
        .map((h) => {
          addFilter(
            h.value,
                `${this.$vuetify.lang.t('$vuetify.component.select_one')} ` + h.text,
                this.selectFiltersRegistered,
                this.selectFilters,
                { caseSensitive: h.case_sensitive }
          )
        })
      newHeaders
        .filter((h) => h.select_filter_many)
        .map((h) => {
          addFilter(
            h.value,
                `${this.$vuetify.lang.t('$vuetify.component.many_filter')} ` + h.text,
                this.selectManyFiltersRegistered,
                this.selectManyFilters,
                {
                  caseSensitive: h.case_sensitive,
                  isManyFilter: true
                }
          )
        })
      newHeaders
        .filter((h) => h.checkbox_filter)
        .map((h) => {
          addFilter(
            h.value,
                `${this.$vuetify.lang.t('$vuetify.component.select_one')} ` + h.text,
                this.checkboxFiltersRegistered,
                this.checkboxFilters,
                { isCheckbox: true },
                false,
                [true, false]
          )
        })
      newHeaders.map((h) => {
        this._setHeaderMap(h.value, h)
      })
      this.clearFilters()
      this.resetColumns()
    },
    _processItems (newItems) {
      newItems.map((item) => {
        this.selectManyFilters.map((f) => {
          const fieldValue = _.get(item, f.name)
          if (!Array.isArray(fieldValue)) {
            return
          }
          f.items = f.items.concat(fieldValue)
        })
        // Set select filters
        this.selectFilters.map((f) => {
          const fieldValue = _.get(item, f.name)
          f.items.push(fieldValue)
        })
      })
      // Order Items
      this.selectFilters.map((f) => {
        f.items = _.sortedUniq(_.sortBy(f.items))
      })
      this.selectManyFilters.map((f) => {
        f.items = _.sortedUniq(_.sortBy(f.items))
      })
      const firstRow = newItems[0]
      if (!firstRow) {
        return
      }
      Object.keys(firstRow).map((itemFieldName) => {
        this._setHeaderMap(itemFieldName, {
          value: itemFieldName,
          text: itemFieldName.toUpperCase()
        })
      })
    },
    _setHeaderMap (fieldName, headerObj) {
      if (this.headersAllMap[fieldName]) {
        return
      }
      this.headersAllMap[fieldName] = headerObj
      this.headersAllMap = { ...this.headersAllMap }
    }
  }

}
</script>

<style scoped>

</style>
