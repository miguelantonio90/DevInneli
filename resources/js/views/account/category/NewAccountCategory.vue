<template>
  <v-dialog
    v-model="toogleNewModal"
    max-width="500"
    persistent
  >
    <v-card>
      <v-card-title>
        <span class="headline">{{
          $vuetify.lang.t("$vuetify.titles.newF", [
            $vuetify.lang.t("$vuetify.accounting_category.name")
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
              :md="!newCategory.parent_id? 6: 12"
            >
              <v-text-field
                v-model="newCategory.name"
                :label="$vuetify.lang.t('$vuetify.firstName')"
                :rules="formRule.firstName"
                required
              />
            </v-col>

            <v-col
              v-if="!newCategory.parent_id"
              cols="12"
              md="6"
            >
              <v-autocomplete
                v-model="newCategory.nature"
                :label="$vuetify.lang.t('$vuetify.accounting_category.nature')"
                :items="getNatures"
                :rules="formRule.required"
                auto-select-first
                item-text="text"
                item-value="value"
                required
              />
            </v-col>
            <v-col
              cols="12"
              md="12"
            >
              <app-color-picker
                :value="newCategory.color"
                @input="inputColor"
              />
            </v-col>
            <v-col
              cols="12"
              md="12"
            >
              <v-textarea
                v-model="newCategory.description"
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
          @click="toogleNewModal(false)"
        >
          <v-icon>mdi-close</v-icon>
          {{ $vuetify.lang.t("$vuetify.actions.cancel") }}
        </v-btn>
        <v-btn
          :disabled="!formValid || isActionInProgress"
          :loading="isActionInProgress"
          class="mb-2"
          color="primary"
          @click="createNewCategory"
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
  name: 'NewAccountCategory',
  props: {
    parentId: {
      type: String,
      default: ''
    }
  },
  data () {
    return {
      formValid: false,
      hidePinCode1: true,
      hidePinCode2: true,
      errorPhone: null,
      formRule: this.$rules
    }
  },
  computed: {
    ...mapState('accountCategory', ['saved', 'newCategory', 'isActionInProgress']),
    getNatures () {
      return [
        {
          text: this.$vuetify.lang.t('$vuetify.accounting_category.creditor'),
          value: 'creditor'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.accounting_category.debtor'),
          value: 'debtor'
        }
      ]
    }
  },
  created () {
    this.formValid = false
    this.newCategory.parent_id = this.parentId
  },
  methods: {
    ...mapActions('accountCategory', ['createCategory', 'toogleNewModal']),
    inputColor (color) {
      this.newCategory.color = color
    },
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
        await this.createCategory(this.newCategory)
      }
    }
  }
}
</script>

<style scoped></style>
