import { Parser } from 'json2csv'
import moment from 'moment'

export function downloadAsJson (rows, fields, filenamePrefix) {
  const dateStr = moment().format('YYYY-MM-DD_HH:mm')
  const filename = `${filenamePrefix}-${dateStr}.csv`
  const json2csvParser = new Parser({ fields, delimiter: ',' })
  const csv = json2csvParser.parse(rows)

  const a = document.createElement('a')
  a.textContent = 'download'
  a.download = filename
  a.href = 'data:text/csv;charset=utf-8,' + escape(csv)
  document.body.appendChild(a)
  a.click()
}
