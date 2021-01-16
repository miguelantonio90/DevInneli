<template>
  <v-card>
    <v-card-title>
      <v-app-bar
        flat
        dense
        color="rgba(0, 0, 0, 0)"
      >
        {{ title }}
        <v-spacer />
        <v-tooltip
          v-if="viewTourButton"
          bottom
        >
          <template v-slot:activator="{ on, attrs }">
            <v-btn
              color="teal darken-2"
              dark
              v-bind="attrs"
              icon
              medium
              v-on="on"
              @click="tourButtonClicked"
            >
              <v-icon>mdi-help</v-icon>
            </v-btn>
          </template>
          <span>{{ $vuetify.lang.t('$vuetify.guide') }}</span>
        </v-tooltip>
      </v-app-bar>
    </v-card-title>
    <v-container
      v-if="viewShowFilter"
      fluid
    >
      <!-- SEARCH BAR -->
      <filter-header
        :id="`filter_header_`+id"
        :has-csv-export="hasCsvExport"
        :has-csv-import="hasCsvImport"
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
        @onClickImport="onClickImport"
      />
    </v-container>
    <v-data-table
      :id="id"
      :class="getClassStyle"
      v-bind="options"
      :loading="isLoading"
      :items="itemsFiltered || []"
      :headers="headersChoosenObjs || []"
      :search="searchValueDebounced"
      :hide-default-footer="hideFooter"
      v-on="$listeners"
      @click:row="handleClick"
    >
      <template
        v-if="accessNewButton"
        v-slot:top
      >
        <v-toolbar flat>
          <v-spacer />
          <v-btn
            :id="`btnAdd_`+id"
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
      <template v-slot:[`item.actions`]="{ item }">
        <v-tooltip top>
          <template v-slot:activator="{ on, attrs }">
            <v-icon
              v-if="viewShowButton"
              :id="`btnShow_`+id"
              class="mr-2"
              small
              v-bind="attrs"
              v-on="on"
              @click="showButtonClicked(item.id)"
            >
              mdi-eye
            </v-icon>
          </template>
          <span>{{ $vuetify.lang.t('$vuetify.actions.show') }}</span>
        </v-tooltip>
        <v-tooltip top>
          <template v-slot:activator="{ on, attrs }">
            <v-icon
              v-if="accessEditButton"
              :id="`btnEdit_`+id"
              class="mr-2"
              color="warning"
              small
              v-bind="attrs"
              v-on="on"
              @click="editButtonClicked(item.id)"
            >
              mdi-pencil
            </v-icon>
          </template>
          <span>{{ $vuetify.lang.t('$vuetify.actions.edit') }}</span>
        </v-tooltip>
        <v-tooltip top>
          <template v-slot:activator="{ on, attrs }">
            <v-icon
              v-if="viewDiscountButton"
              :id="`btnDiscount_`+id"
              class="mr-2"
              color="success"
              small
              v-bind="attrs"
              v-on="on"
              @click="discountButtonClicked(item)"
            >
              mdi-currency-usd-off
            </v-icon>
          </template>
          <span>{{ $vuetify.lang.t('$vuetify.access.access.manager_discount') }}</span>
        </v-tooltip>
        <v-tooltip top>
          <template v-slot:activator="{ on, attrs }">
            <v-icon
              v-if="viewModButton"
              :id="`btnMod_`+id"
              class="mr-2"
              color="primary"
              small
              v-bind="attrs"
              v-on="on"
              @click="modButtonClicked(item)"
            >
              mdi-table-edit
            </v-icon>
          </template>
          <span>{{ $vuetify.lang.t('$vuetify.access.access.manager_mod') }}</span>
        </v-tooltip>
        <v-tooltip top>
          <template v-slot:activator="{ on, attrs }">
            <v-icon
              v-if="accessTransportButton"
              :id="`btnTransport_`+id"
              class="mr-2"
              color="success"
              small
              v-bind="attrs"
              v-on="on"
              @click="transferButtonClicked(item.id)"
            >
              mdi-truck
            </v-icon>
          </template>
          <span>{{ $vuetify.lang.t('$vuetify.actions.transfer') }}</span>
        </v-tooltip>
        <v-tooltip top>
          <template v-slot:activator="{ on, attrs }">
            <v-icon
              v-if="accessDeleteButton"
              :id="`btnDelete_`+id"
              class="mr-2"
              color="error"
              small
              v-bind="attrs"
              v-on="on"
              @click="deleteButtonClicked(item.id?item.id: item)"
            >
              mdi-delete
            </v-icon>
          </template>
          <span>{{ $vuetify.lang.t('$vuetify.actions.delete') }}</span>
        </v-tooltip>
      </template>
    </v-data-table>
    <import-article v-if="showImportModal" />
  </v-card>
</template>

<script>
import FilterHeader from './FilterHeader'
import FiltersHandler from './helpers/filter'
import { downloadAsJson } from './helpers/json-to-csv'
import { debounce } from './debounce'
import { BehaviorSubject, combineLatest, Subject } from 'rxjs'
import { filter, takeUntil } from 'rxjs/operators'
import { mapActions, mapState, mapGetters } from 'vuex'
import ImportArticle from '../../../views/article/ImportArticle'
import * as _ from 'lodash'

const DEFAULT_ARRAY = []
export default {
  name: 'AppDataTable',
  components: { FilterHeader, ImportArticle },
  inheritAttrs: false,
  props: {
    id: {
      type: String,
      default: '',
      required: false
    },
    title: {
      type: String,
      default: '',
      required: false
    },
    manager: {
      type: String,
      default: '',
      required: false
    },
    subtitle: {
      type: String,
      default: '',
      required: false
    },
    options: {
      type: [Object, Array],
      default: () => {
        return []
      }
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
    viewTourButton: {
      type: Boolean,
      default: true
    },
    viewNewButton: {
      type: Boolean,
      default: true
    },
    viewModButton: {
      type: Boolean,
      default: false
    },
    viewDiscountButton: {
      type: Boolean,
      default: false
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
    },
    removeById: {
      type: Boolean,
      default: true
    },
    viewTransferButton: {
      type: Boolean,
      default: false
    },
    viewShowFilter: {
      type: Boolean,
      default: true
    },
    hideFooter: {
      type: Boolean,
      default: false
    },
    hasCsvImport: {
      type: Boolean,
      default: false
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
      checkboxFiltersRegistered: {},
      accessNewButton: false,
      accessEditButton: false,
      accessTransportButton: false,
      accessOpen: false,
      accessDeleteButton: true
    }
  },
  computed: {
    ...mapState('article', [
      'showImportModal'
    ]),
    ...mapGetters('auth', ['access_permit']),
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
    access_permit () {
      this.showButtons()
    },
    manager () {
      this.showButtons()
    },
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
    this.showButtons()
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
  created () {
    this.showButtons()
  },
  destroyed () {
    this.o$destroyed.next()
  },
  methods: {
    ...mapActions('article', ['importArticles', 'toogleImportModal']),
    showButtons () {
      if (this.access_permit.length > 0 && this.manager !== '') {
        this.access_permit.forEach((v) => {
          if ('manager_' + this.manager === v.title.name && !Array.isArray(v.actions)) {
            this.accessNewButton = this.viewNewButton && v.actions.create
            this.accessEditButton = this.viewEditButton && v.actions.edit
            this.accessDeleteButton = this.viewDeleteButton && v.actions.delete
            this.accessTransportButton = this.viewTransferButton && v.actions.transfer
            this.accessOpen = v.actions.boxes_open
          }
        })
      }
    },
    createButtonClicked () {
      this.$emit('create-row')
    },
    tourButtonClicked () {
      this.$emit('init-tour')
    },
    handleClick (row) {
      this.$emit('rowClick', row)
    },
    refreshButtonClicked () {
      this.$emit('refresh-table')
    },
    editButtonClicked (clickedRowId) {
      this.$emit('edit-row', clickedRowId)
    },
    modButtonClicked (clickedRowId) {
      this.$emit('manager-modify-row', clickedRowId)
    },
    discountButtonClicked (clickedRowId) {
      this.$emit('manager-discount-row', clickedRowId)
    },
    openButtonClicked (clickedRowId) {
      this.$emit('open-row', clickedRowId)
    },
    showButtonClicked (clickedRowId) {
      this.$emit('show-row', clickedRowId)
    },
    deleteButtonClicked (clickedRowId) {
      this.$emit('delete-row', clickedRowId)
    },
    transferButtonClicked (clickedRowId) {
      this.$emit('transfer-row', clickedRowId)
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
    onClickImport () {
      this.toogleImportModal(true)
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
