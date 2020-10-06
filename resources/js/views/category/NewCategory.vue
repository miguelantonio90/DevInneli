<template>
  <v-dialog
    v-model="toogleNewModal"
    max-width="600px"
  >
    <v-card>
      <v-card-title>
        <span class="headline">{{
          $vuetify.lang.t('$vuetify.titles.newF', [
            $vuetify.lang.t('$vuetify.menu.category'),
          ])
        }}</span>
      </v-card-title>
      <v-card-text>
        <v-form
          ref="form"
          v-model="formValid"
          class="my-10"
          lazy-validation
        >
          <v-row>
            <v-col
              cols="12"
              md="12"
            >
              <v-text-field
                v-model="newCategory.name"
                :label="$vuetify.lang.t('$vuetify.firstName')"
                :rules="formRule.firstName"
                required
              />
            </v-col>
          </v-row>
        </v-form>
      </v-card-text>
      <v-card-actions>
        <v-spacer />
        <v-btn
          class="mb-2"
          color="error"
          @click="toogleNewModal(false)"
        >
          <v-icon>mdi-close</v-icon>
          {{ $vuetify.lang.t('$vuetify.actions.cancel') }}
        </v-btn>
        <v-btn
          :disabled="!formValid"
          :loading="isActionInProgress"
          class="mb-2"
          color="primary"
          @click="createNewCategory"
        >
          <v-icon>mdi-check</v-icon>
          {{ $vuetify.lang.t('$vuetify.actions.save') }}
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import { mapActions, mapState } from 'vuex'

export default {
  name: 'NewCategory',
  data () {
    return {
      formValid: false,
      hidePinCode1: true,
      hidePinCode2: true,
      errorPhone: null,
      formRule: {
        firstName: [
          (v) =>
            !!v ||
              this.$vuetify.lang.t('$vuetify.rule.required', [
                this.$vuetify.lang.t('$vuetify.name')
              ])
        ],
        email: [
          (v) =>
            !!v ||
              this.$vuetify.lang.t('$vuetify.rule.required', [
                this.$vuetify.lang.t('$vuetify.email')
              ]),
          (v) =>
            /.+@.+\..+/.test(v) ||
              this.$vuetify.lang.t('$vuetify.rule.bad_email', [
                this.$vuetify.lang.t('$vuetify.email')
              ])
        ],
        city: [
          (v) =>
            !!v ||
                  this.$vuetify.lang.t('$vuetify.rule.required', [
                    this.$vuetify.lang.t('$vuetify.city')
                  ])
        ],
        province: [
          (v) =>
            !!v ||
                  this.$vuetify.lang.t('$vuetify.rule.required', [
                    this.$vuetify.lang.t('$vuetify.province')
                  ])
        ],
        barCode: [
          (v) =>
            !!v ||
                  this.$vuetify.lang.t('$vuetify.rule.required', [
                    this.$vuetify.lang.t('$vuetify.barCode')
                  ])
        ]
      }
    }
  },
  computed: {
    ...mapState('category', ['saved', 'newCategory', 'isActionInProgress']),
  },
  created () {
    this.formValid = false
  },
  methods: {
    ...mapActions('category', ['createCategory', 'toogleNewModal']),
    lettersNumbers (event) {
      const regex = new RegExp('^[a-zA-Z0-9 ]+$')
      const key = String.fromCharCode(
        !event.charCode ? event.which : event.charCode
      )
      if (!regex.test(key)) {
        event.preventDefault()
        return false
      }
    },
    async createNewCategory () {
      if (this.$refs.form.validate()) {
        this.loading = true
        await this.createCategory(this.newCategory).then(() => {
          if (this.saved) {
            this.loading = false
            const msg = this.$vuetify.lang.t(
              '$vuetify.messages.success_profile'
            )
            this.$Toast.fire({
              icon: 'success',
              title: msg
            })
          }
        })
      }
    }
  }
}
</script>

<style scoped>
</style>
