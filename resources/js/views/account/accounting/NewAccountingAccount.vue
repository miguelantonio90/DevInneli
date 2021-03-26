<template>
  <v-dialog
    v-model="toogleNewAccountsModal"
    max-width="500"
    persistent
  >
    <app-loading v-if="loading" />
    <v-card v-if="!loading">
      <v-card-title>
        <span class="headline">{{
          $vuetify.lang.t("$vuetify.titles.newF", [
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
              v-if="!categoryId"
              cols="12"
              md="6"
            >
              <v-autocomplete
                v-model="category"
                :label="$vuetify.lang.t('$vuetify.accounting_category.name')"
                :items="categories"
                return-object
                :rules="formRule.firstName"
                auto-select-first
                item-text="name"
                required
              >
                <template v-slot:selection="data">
                  {{ data.item.default_category ? $vuetify.lang.t('$vuetify.accounting_category.' + data.item.name) : data.item.name }}
                </template>
                <template v-slot:item="data">
                  <template v-if="typeof data.item !== 'object'">
                    <v-list-item-content>
                      {{ data.item.default_category ? $vuetify.lang.t('$vuetify.accounting_category.' + data.item.name) : data.item.name }}
                    </v-list-item-content>
                  </template>
                  <template v-else>
                    <v-list-item-content>
                      <v-list-item-title>
                        {{ data.item.default_category ? $vuetify.lang.t('$vuetify.accounting_category.' + data.item.name) : data.item.name }}
                      </v-list-item-title>
                    </v-list-item-content>
                  </template>
                </template>
              </v-autocomplete>
            </v-col>
            <v-col
              cols="12"
              md="6"
            >
              <v-text-field
                v-model="newAccount.name"
                :label="$vuetify.lang.t('$vuetify.firstName')"
                :rules="formRule.firstName"
                required
              />
            </v-col>
            <v-col
              cols="12"
              md="6"
            >
              <v-text-field
                v-model="newAccount.code"
                :label="$vuetify.lang.t('$vuetify.accounting_category.code')"
                :rules="formRule.required"
                required
              />
            </v-col>
            <v-col
              cols="12"
              md="6"
            >
              <v-text-field-money
                v-model="newAccount.init_balance"
                :label="$vuetify.lang.t('$vuetify.boxes.init')"
                :options="{
                  length: 15,
                  precision: 2,
                  empty: 0.00
                }"
                :properties="{
                  clearable: false
                }"
              />
            </v-col>
            <v-col
              cols="12"
              md="12"
            >
              <v-text-field
                v-model="newAccount.description"
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
          @click="toogleNewAccountsModal(false)"
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
  name: 'NewAccountingAccount',
  props: {
    categoryId: {
      type: String,
      default: ''
    }
  },
  data () {
    return {
      category: {},
      loading: false,
      formValid: false,
      hidePinCode1: true,
      hidePinCode2: true,
      errorPhone: null,
      formRule: this.$rules
    }
  },
  computed: {
    ...mapState('account', ['saved', 'newAccount', 'isActionInProgress']),
    ...mapState('accountCategory', ['saved', 'categories'])
  },
  watch: {
    category: function () {
      this.newAccount.category_id = this.category.id
    }
  },
  created () {
    this.loading = true
    this.formValid = false
    if (this.categoryId) {
      this.newAccount.category_id = this.categoryId
    } else {
      this.getCategories().then(() => {
        this.category = this.categories[0]
      })
    }
    this.loading = false
  },
  methods: {
    ...mapActions('account', ['createAccount', 'toogleNewAccountsModal']),
    ...mapActions('accountCategory', ['getCategories']),
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
        await this.createAccount(this.newAccount)
      }
    }
  }
}
</script>

<style scoped></style>
