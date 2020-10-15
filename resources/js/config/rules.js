import Vuetify from '../plugins/vuetify'
const language = Vuetify.framework.lang

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
    language.t('$vuetify.rule.bad_email', [
      language.t('$vuetify.email')
    ])
]
const password = [
  (v) => !!v || language.t('$vuetify.rule.required', [
    language.t('$vuetify.password')
  ]),
  (v) => (v || '').length >= 8 || language.t('$vuetify.rule.min', ['8'])
]
const passwordConfirmation = [
  (v) => !!v || language.t('$vuetify.rule.required', [
    language.t('$vuetify.confirm_password')
  ]),
  (v) => (!!v && v) === this.formRegister.password ||
    language.t(
      '$vuetify.rule.match',
      [language.t('$vuetify.password')],
      [language.t('$vuetify.confirm_password')]
    )
]
const country = [
  v => !!v || language.t('$vuetify.rule.select')
]
const pinCode = [
  (v) =>
    !!v || language.t('$vuetify.rule.required', [
      language.t('$vuetify.pinCode')
    ]),
  (v) => (v && v.length >= 4) || language.t('$vuetify.rule.pin.min', ['4']),
  (v) => (v && v.length <= 6) || language.t('$vuetify.rule.pin.max', ['6'])
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
    ])
]
const city = [
  (v) =>
    !!v ||
    language.t('$vuetify.rule.required', [
      language.t('$vuetify.city')
    ])
]
const province = [
  (v) =>
    !!v ||
    language.t('$vuetify.rule.required', [
      language.t('$vuetify.province')
    ])
]
const barCode = [
  (v) =>
    !!v ||
    language.t('$vuetify.rule.required', [
      language.t('$vuetify.barCode')
    ])
]
const description = [
  (v) =>
    !!v ||
    language.t('$vuetify.rule.required', [
      language.t('$vuetify.access.description')
    ])
]
const phone = [
  (v) =>
    !!v ||
    language.t('$vuetify.rule.required', [
      language.t('$vuetify.phone')
    ])
]
const address = [
  (v) =>
    !!v ||
    language.t('$vuetify.rule.required', [
      language.t('$vuetify.address')
    ])
]
const key = [
  (v) => !!v || language.t('$vuetify.rule.required', [
    language.t('$vuetify.access.key')
  ])
]

export default {
  firstName,
  lastName,
  email,
  password,
  passwordConfirmation,
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
  required
}

