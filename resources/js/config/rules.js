import vuetify from '../plugins/vuetify'

// const country = [
//   (v) =>
//     !!v ||
//     this.$vuetify.lang.t('$vuetify.rule.required', [
//       this.$vuetify.lang.t('$vuetify.country')
//     ])
// ]
const firstName = [(v) => !!v || vuetify.lang.t('firstName')]
// const username = [
//   (v) =>
//     !!v ||
//     this.$vuetify.lang.t('$vuetify.rule.required', [
//       this.$vuetify.lang.t('$vuetify.username')
//     ])
// ]
// const lastName = [
//   (v) =>
//     !!v ||
//     this.$vuetify.lang.t('$vuetify.rule.required', [
//       this.$vuetify.lang.t('$vuetify.lastName')
//     ])
// ]
// const email = [
//   (v) =>
//     !!v ||
//     this.$vuetify.lang.t('$vuetify.rule.required', [
//       this.$vuetify.lang.t('$vuetify.email')
//     ]),
//   (v) =>
//     /.+@.+/.test(v) ||
//     this.$vuetify.lang.t('$vuetify.rule.bad_email', [
//       this.$vuetify.lang.t('$vuetify.email')
//     ])
// ]
// const company = [
//   (v) => !!v || this.$vuetify.lang.t('$vuetify.rule.required', [this.$vuetify.lang.t('$vuetify.company')])
// ]
// const password = [
//   (v) =>
//     !!v ||
//     this.$vuetify.lang.t('$vuetify.rule.required', [
//       this.$vuetify.lang.t('$vuetify.password')
//     ]),
//   (v) =>
//     (v || '').length >= 8 ||
//     this.$vuetify.lang.t('$vuetify.rule.min', ['8'])
// ]
const password_confirmation = [
  (v) =>
    !!v ||
    this.$vuetify.lang.t('$vuetify.rule.required', [
      this.$vuetify.lang.t('$vuetify.confirm_password')
    ]),
  (v) =>
    (!!v && v) === this.formRegister.password ||
    this.$vuetify.lang.t(
      '$vuetify.rule.match',
      [this.$vuetify.lang.t('$vuetify.password')],
      [this.$vuetify.lang.t('$vuetify.confirm_password')]
    )
]

export default {
  // company: company,
  // country: country,
  firstName: firstName
  // username: username,
  // lastName: lastName,
  // email: email,
  // password: password,
  // password_confirmation: password_confirmation
}
