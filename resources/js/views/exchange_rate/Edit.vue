<template>
  <v-dialog
    v-model="toogleEditModal"
    max-width="600"
    persistent
  >
    <v-card>
      <v-card-title>
        <span class="headline">{{
          $vuetify.lang.t('$vuetify.titles.edit', [
            $vuetify.lang.t('$vuetify.menu.exchange_rate'),
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
              <v-combobox
                v-model="editChange.country"
                :items="arrayCurrency"
                :label="
                  $vuetify.lang.t('$vuetify.country')
                "
                :rules="formRule.country"
                :loading="isCountryLoading"
                :disabled="!!isCountryLoading"
                item-text="name"
                clearable
                required
                return-object
              />
            </v-col>
          </v-row>
        </v-form>
      </v-card-text>
      <v-card-actions>
        <v-spacer />
        <v-btn
          class="mb-2"
          @click="toogleEditModal(false)"
        >
          <v-icon>mdi-close</v-icon>
          {{ $vuetify.lang.t('$vuetify.actions.cancel') }}
        </v-btn>
        <v-btn
          :disabled="!formValid"
          :loading="isActionInProgress"
          class="mb-2"
          color="primary"
          @click="handleSubmit"
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

  data () {
    return {
      formValid: false,
      errorPhone: null,
      formRule: this.$rules
    }
  },
  computed: {
    ...mapState('exchangeRate', ['editChange', 'isActionInProgress']),
    ...mapState('statics', ['arrayCurrency', 'isCountryLoading'])
  },
  created () {
    this.formValid = false
    this.getArrayCurrency()
  },
  methods: {
    ...mapActions('exchangeRate', ['updateChange', 'toogleEditModal']),
    ...mapActions('statics', ['getArrayCurrency']),
    async handleSubmit () {
      if (this.$refs.form.validate()) {
        await this.updateChange(this.editChange)
      }
    }
  }
}
</script>

<style scoped>
</style>
