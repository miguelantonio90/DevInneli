<template>
  <v-dialog
    v-model="toogleNewModal"
    max-width="450"
    persistent
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
            <v-col
              cols="12"
              md="12"
            >
              <v-text-field
                v-model="newCategory.description"
                :label="$vuetify.lang.t('$vuetify.access.description')"
              />
            </v-col>
          </v-row>
        </v-form>
      </v-card-text>
      <v-card-actions>
        <v-spacer />
        <v-btn
          class="mb-2"
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
          @click="handleCategory"
        >
          <v-icon>mdi-content-save</v-icon>
          {{ $vuetify.lang.t('$vuetify.actions.save') }}
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import { mapActions, mapState } from 'vuex'

export default {

  data () {
    return {
      formValid: false,
      errorPhone: null,
      formRule: this.$rules
    }
  },
  computed: {
    ...mapState('expenseCategory', ['newCategory', 'isActionInProgress'])
  },
  created () {
    this.formValid = false
  },
  methods: {
    ...mapActions('expenseCategory', ['createCategory', 'toogleNewModal']),
    async handleCategory () {
      if (this.$refs.form.validate()) {
        await this.createCategory(this.newCategory)
      }
    }
  }
}
</script>

<style scoped>
</style>
