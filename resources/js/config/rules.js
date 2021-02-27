import Vuetify from '../plugins/vuetify'
import { isNumeric } from 'rxjs/internal-compatibility'
const language = Vuetify.framework.lang

const company = [
  (v) =>
    !!v || this.$vuetify.lang.t('$vuetify.rule.required', [
      this.$vuetify.lang.t('$vuetify.company')
    ])
]

const firstName = [
  (v) => !!v || language.t('$vuetify.rule.required', [
    language.t('$vuetify.name')
  ])
]
const required = [
  (v) => !!v || language.t('$vuetify.rule.general_required')
]

const position = [
  (v) => !!v || language.t('$vuetify.rule.required', [
    language.t('$vuetify.access.name')
  ])
]

const lastName = [
  (v) => !!v || language.t('$vuetify.rule.required', [
    language.t('$vuetify.name')
  ])
]
const email = [
  (v) => !!v || language.t('$vuetify.rule.required', [
    language.t('$vuetify.email')
  ]),
  (v) => /.+@.+\..+/.test(v) ||
    language.t('$vuetify.rule.bad_email', [language.t('$vuetify.email')])
]
const password = [
  (v) => !!v || language.t('$vuetify.rule.required', [
    language.t('$vuetify.password')
  ]),
  (v) => (v || '').length >= 8 || language.t('$vuetify.rule.min', ['8'])
]
const country = [
  v => !!v || language.t('$vuetify.rule.select')
]
const pinCode = [
  (v) =>
    !!v || language.t('$vuetify.rule.required', [
      language.t('$vuetify.pinCode')
    ]),
  (v) => (v && v.length >= 7) || language.t('$vuetify.rule.pin.min', ['6'])
]
const access = [
  (v) =>
    !!v || language.t('$vuetify.rule.required', [
      language.t('$vuetify.menu.access')
    ])
]
const shops = [
  (v) =>
    !!v || language.t('$vuetify.rule.required', [
      language.t('$vuetify.menu.shop')
    ]),
  (v) => (v && v.length >= 10) || language.t('$vuetify.rule.pin.min', ['10'])
]
const city = [
  (v) =>
    !!v ||
    language.t('$vuetify.rule.required', [language.t('$vuetify.city')])
]
const province = [
  (v) =>
    !!v ||
    language.t('$vuetify.rule.required', [language.t('$vuetify.province')])
]
const barCode = [
  (v) =>
    !!v ||
    language.t('$vuetify.rule.required', [language.t('$vuetify.barCode')])
]
const description = [
  (v) =>
    !!v ||
    language.t('$vuetify.rule.required', [language.t('$vuetify.access.description')]),
  (v) => (v && v.length >= 120) || language.t('$vuetify.rule.pin.min', ['120'])
]
const phone = [
  (v) =>
    !!v ||
    language.t('$vuetify.rule.required', [language.t('$vuetify.phone')]),
  (v) => !!isNumeric(v) || language.t('$vuetify.rule.bad_numeric', [
    language.t('$vuetify.phone')
  ])
]
const address = [
  (v) =>
    !!v ||
    language.t('$vuetify.rule.required', [language.t('$vuetify.address')]),
  (v) => (v && v.length >= 120) || language.t('$vuetify.rule.pin.min', ['120'])
]
const contract = [
  (v) =>
    !!v ||
    language.t('$vuetify.rule.required', [language.t('$vuetify.supplier.contract')])
]
const identity = [
  (v) =>
    !!v ||
    language.t('$vuetify.rule.required', [language.t('$vuetify.supplier.identity')])
]
const key = [
  (v) => !!v || language.t('$vuetify.rule.required', [
    language.t('$vuetify.access.key')
  ])
]

const change = [
  (v) => !!v || language.t('$vuetify.rule.required', [
    language.t('$vuetify.change')
  ]),
  (v) => !!isNumeric(v) || language.t('$vuetify.rule.bad_numeric', [
    language.t('$vuetify.change')
  ])
]

export default {
  company,
  firstName,
  identity,
  contract,
  lastName,
  email,
  password,
  country,
  pinCode,
  access,
  shops,
  city,
  province,
  barCode,
  description,
  phone,
  address,
  key,
  position,
  required,
  change
}
