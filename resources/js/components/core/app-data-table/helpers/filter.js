import * as _ from 'lodash'
import { doesItemMatch } from './doesMatch'

class FilterArraysHandler {
  constructor () {
    this.filters = []
    this.activeFilters = []
  }

  updateFilterValue (filterFieldName, filterValue) {
    if (!this.filters[filterFieldName]) {
      this.filters[filterFieldName] = {}
    }
    this.filters[filterFieldName].value = filterValue
    this.activeFilters[filterFieldName] = IsValidValueOrArray(filterValue)
  }

  registerFilter (filterFieldName, options) {
    this.filters[filterFieldName] = {
      options: options,
      value: null
    }
  }

  runFilter (item) {
    const activeFilters = Object.entries(this.activeFilters).filter(
      (a) => a[1]
    )
    if (!activeFilters.length) {
      return true
    }
    const results = activeFilters.map(([filterFieldName]) => {
      const filterObject = this.filters[filterFieldName]
      return doesItemMatch(item, filterFieldName, filterObject)
    })
    return results.reduce((acc, match) => acc || match, false)
  }
}

export function IsValidValueOrArray (input) {
  if (_.isNil(input)) {
    return false
  }
  if (!_.isArray(input)) {
    return true
  }
  const someValues = input.some((v) => !_.isNil(v))
  return !!someValues
}

export default FilterArraysHandler
