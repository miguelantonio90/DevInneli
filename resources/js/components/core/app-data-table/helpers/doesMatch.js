import * as _ from 'lodash'

export function doesItemMatch (
	item,
	filterFieldName,
	filter
) {
	const optionsParsed = (filter && filter.options) || {}
	const itemValue = _.get(item, filterFieldName)
	const filterValue = filter && filter.value
	if (optionsParsed.isManyFilter) {
		return doesManyMatch(itemValue, filterValue)
	}
	return doesStringMatch(itemValue, filterValue, optionsParsed)
}

function doesManyMatch (itemValue, filterValue) {
	const parsedValue = Array.isArray(itemValue) ? itemValue : []
	const parsedFilterValue = Array.isArray(filterValue) ? filterValue : []
	const parsedFilterValueKeys = new Set(parsedFilterValue)
	const matches = parsedValue.map((str) => parsedFilterValueKeys.has(str))
	return matches.reduce((acc, match) => acc || match, false)
}

function doesStringMatch (itemValue, filterValue, optionsParsed) {
	const parsedItemValueInput = parseItemValue(itemValue)
	const parsedFilterInput = parseFilterValue(filterValue)
	let parsedItemValue = parsedItemValueInput
	let parsedFilter = parsedFilterInput
	if (!optionsParsed.caseSensitive) {
		parsedItemValue = parsedItemValueInput.toLowerCase()
		parsedFilter = parsedFilterInput.map((i) =>
			(i + '').toString().toLowerCase()
		)
	}
	const matches = parsedFilter.map((str) => str === parsedItemValue)
	return matches.reduce((acc, match) => acc || match, false)
}

function parseItemValue (itemValue) {
	if (_.isNil(itemValue)) return ''
	if (typeof itemValue === 'string') return itemValue
	return typeof itemValue === 'number' ? itemValue.toString() : itemValue.toString()
}

function parseFilterValue (
	filterValue
) {
	if (_.isNil(filterValue)) {
		return []
	}
	if (_.isArray(filterValue)) {
		return filterValue.map(v => v + '')
	}
	if (typeof filterValue === 'string') {
		return [filterValue]
	}
	if (typeof filterValue === 'boolean') {
		return [filterValue.toString()]
	}
	return [filterValue]
}
