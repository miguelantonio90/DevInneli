<template>
  <v-dialog
    v-model="toogleEditModal"
    max-width="600px"
  >
    <v-card>
      <v-card-title>
        <span class="headline">{{
          $vuetify.lang.t('$vuetify.titles.edit', [
            $vuetify.lang.t('$vuetify.menu.employment'),
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
              md="4"
            >
              <v-text-field
                v-model="editEmployment.firstName"
                :counter="10"
                :label="$vuetify.lang.t('$vuetify.firstName')"
                :rules="formRule.firstName"
                required
                @keypress="letters"
              />
            </v-col>
            <v-col
              cols="12"
              md="4"
            >
              <v-text-field
                v-model="editEmployment.lastName"
                :counter="10"
                :label="$vuetify.lang.t('$vuetify.lastName')"
                required
                @keypress="letters"
              />
            </v-col>
            <v-col
              cols="12"
              md="4"
            >
              <v-text-field
                v-model="editEmployment.email"
                :label="$vuetify.lang.t('$vuetify.email')"
                :rules="formRule.email"
                autocomplete="off"
                required
              />
            </v-col>
            <v-col
              cols="12"
              md="4"
            >
              <v-text-field
                v-model="editEmployment.username"
                :counter="8"
                :label="$vuetify.lang.t('$vuetify.username')"
                autocomplete="off"
                required
                value=""
                @keypress="lettersNumbers"
              />
            </v-col>
            <v-col
              cols="12"
              md="4"
            >
              <v-text-field
                v-model="editEmployment.password"
                :label="$vuetify.lang.t('$vuetify.password')"
                :rules="formRule.password"
                autocomplete="off"
                name="password"
                required
                type="password"
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
          @click="toogleEditModal(false)"
        >
          <v-icon>mdi-close</v-icon>
          {{ $vuetify.lang.t('$vuetify.actions.cancel') }}
        </v-btn>
        <v-btn
          :disabled="!formValid"
          class="mb-2"
          color="primary"
          @click="updateEmploymentHandler"
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
  name: 'EditUser',
  data () {
    return {
      formValid: false,
      formRule: {
        firstName: [
          (v) =>
            !!v ||
              this.$vuetify.lang.t('$vuetify.rule.required', [
                this.$vuetify.lang.t('$vuetify.name')
              ])
        ],
        company: [
          (v) =>
            !!v ||
              this.$vuetify.lang.t('$vuetify.rule.required', [
                this.$vuetify.lang.t('$vuetify.company')
              ])
        ],
        email: [
          (v) =>
            !!v ||
              this.$vuetify.lang.t('$vuetify.rule.required', [
                this.$vuetify.lang.t('$vuetify.email')
              ]),
          (v) =>
            /.+@.+/.test(v) ||
              this.$vuetify.lang.t('$vuetify.rule.bad_email', [
                this.$vuetify.lang.t('$vuetify.email')
              ])
        ]
      }
    }
  },
  computed: {
    ...mapState('employment', ['saved', 'editEmployment']),
    ...mapState('statics', ['arrayCountry'])
  },
  methods: {
    ...mapActions('employment', ['updateEmployment', 'toogleEditModal']),
    onCountry (digit) {
      this.editEmployment.country = this.arrayCountry.filter((c) => c.code === digit)[0]
    },
    letters (event) {
      const regex = new RegExp('^[A-Za-z ]+$')
      const key = String.fromCharCode(
        !event.charCode ? event.which : event.charCode
      )
      if (!regex.test(key)) {
        event.preventDefault()
        return false
      }
    },
    lettersNumbers (event) {
      const regex = new RegExp('^[0-9]+$')
      const key = String.fromCharCode(
        !event.charCode ? event.which : event.charCode
      )
      if (!regex.test(key)) {
        event.preventDefault()
        return false
      }
    },
    async updateEmploymentHandler () {
      if (this.$refs.form.validate()) {
        this.loading = true
        await this.updateEmployment(this.editEmployment).then(() => {
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

<style scoped></style>
