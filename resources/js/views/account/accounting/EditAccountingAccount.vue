<template>
  <v-dialog
    v-model="toogleEditAccountsModal"
    max-width="500"
    persistent
  >
    <v-card>
      <v-card-title>
        <span class="headline">{{
          $vuetify.lang.t("$vuetify.titles.edit", [
            $vuetify.lang.t("$vuetify.menu.accounting")
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
              md="8"
            >
              <v-text-field
                v-model="name"
                :label="$vuetify.lang.t('$vuetify.firstName')"
                :rules="formRule.firstName"
                required
              />
            </v-col>
            <v-col
              cols="12"
              md="4"
            >
              <v-text-field
                v-model="code"
                :label="$vuetify.lang.t('$vuetify.accounting_category.code')"
                :rules="formRule.required"
                required
              />
            </v-col>
            <v-col
              cols="12"
              md="12"
            >
              <v-textarea
                v-model="description"
                :label="$vuetify.lang.t('$vuetify.description')"
              />
            </v-col>
          </v-row>
        </v-form>
      </v-card-text>
      <v-card-actions>
        <v-spacer />
        <v-btn
          class="mb-2"
          :disabled="isActionInProgress"
          @click="toogleEditAccountsModal(false)"
        >
          <v-icon>mdi-close</v-icon>
          {{ $vuetify.lang.t("$vuetify.actions.cancel") }}
        </v-btn>
        <v-btn
          :disabled="!formValid || isActionInProgress"
          :loading="isActionInProgress"
          class="mb-2"
          color="primary"
          @click="createNewAccount"
        >
          <v-icon>mdi-content-save</v-icon>
          {{ $vuetify.lang.t("$vuetify.actions.save") }}
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import { mapActions, mapState } from 'vuex'

export default {
  name: 'EditAccountingAccount',
  data () {
    return {
      name: '',
      code: '',
      description: '',
      formValid: false,
      hidePinCode1: true,
      hidePinCode2: true,
      errorPhone: null,
      formRule: this.$rules
    }
  },
  computed: {
    ...mapState('account', ['saved', 'editAccount', 'isActionInProgress'])
  },
  created () {
    this.formValid = false
    this.name = this.editAccount.name
    this.code = this.editAccount.code
    this.description = this.editAccount.description
  },
  methods: {
    ...mapActions('account', ['updateAccount', 'toogleEditAccountsModal', 'getAccounts']),
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
    async createNewAccount () {
      if (this.$refs.form.validate()) {
        this.loading = true
        await this.updateAccount({
          id: this.editAccount.id,
          name: this.name,
          code: this.code,
          description: this.description
        })
      }
    }
  }
}
</script>

<style scoped></style>
