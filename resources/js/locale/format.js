import { format } from 'date-fns'
import { enUS, es } from 'date-fns/locale'
import localStorage from '../config/localStorage'

const locales = {
  en: enUS,
  es: es
}

// by providing a default string of 'PP' or any of its variants for `formatStr`
// it will format dates in whichever way is appropriate to the locale
export default function (date, formatStr = 'PP') {
  return format(date, formatStr, {
    locale: locales[localStorage.getLanguage() ||
    window.navigator.language.split('-')[0]] // or global.__localeId__
  })
}
