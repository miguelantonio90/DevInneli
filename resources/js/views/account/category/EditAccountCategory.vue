<template>
  <v-dialog
    v-model="toogleEditModal"
    max-width="600px"
    persistent
  >
    <v-card>
      <v-card-title>
        <span class="headline">{{
          $vuetify.lang.t("$vuetify.titles.edit", [
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
              v-if="editCategory.default_category"
              cols="12"
              md="12"
            >
              <v-text-field
                :label="$vuetify.lang.t('$vuetify.firstName')"
                :rules="formRule.firstName"
                :readonly="true"
                :value="$vuetify.lang.t('$vuetify.accounting_category.' + editCategory.name)"
                required
              />
            </v-col>
            <v-col
              v-else
              cols="12"
              md="12"
            >
              <v-text-field
                v-model="editCategory.name"
                :label="$vuetify.lang.t('$vuetify.firstName')"
                :rules="formRule.firstName"
                required
              />
            </v-col>
            <v-col
              cols="12"
              md="12"
            >
              <app-color-picker
                :value="editCategory.color"
                @input="inputColor"
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
          @click="toogleEditModal(false)"
        >
          <v-icon>mdi-close</v-icon>
          {{ $vuetify.lang.t("$vuetify.actions.cancel") }}
        </v-btn>
        <v-btn
          :disabled="!formValid || isActionInProgress"
          :loading="isActionInProgress"
          class="mb-2"
          color="primary"
          @click="updateCategoryHandler"
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
  name: 'EditAccountCategory',
  data () {
    return {
      formValid: false,
      errorPhone: null,
      formRule: this.$rules
    }
  },
  computed: {
    ...mapState('accountCategory', ['saved', 'editCategory', 'isActionInProgress'])
  },
  methods: {
    ...mapActions('accountCategory', ['updateCategory', 'toogleEditModal']),
    inputColor (color) {
      this.editCategory.color = color
    },
    async updateCategoryHandler () {
      if (this.$refs.form.validate()) {
        this.loading = true
        await this.updateCategory(this.editCategory)
      }
    }
  }
}
</script>

<style scoped></style>
