<template>
  <v-dialog
    v-model="toogleNewModal"
    max-width="450"
    persistent
  >
    <v-card>
      <v-card-title>
        <span class="headline">{{
          $vuetify.lang.t("$vuetify.titles.newF", [
            $vuetify.lang.t("$vuetify.menu.exchange_rate")
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
              <v-autocomplete
                v-model="newChange.country"
                :items="arrayCurrency"
                :label="$vuetify.lang.t('$vuetify.country')"
                :rules="formRule.country"
                clearable
                item-text="name"
                required
                return-object
              >
                <template
                  slot="item"
                  slot-scope="data"
                >
                  <template
                    v-if="typeof data.item !== 'object'"
                  >
                    <v-list-item-content
                      v-text="data.item"
                    />
                  </template>
                  <template v-else>
                    <v-list-item-avatar>
                      {{ data.item.emoji }}
                    </v-list-item-avatar>
                    <v-list-item-content>
                      <v-list-item-title>
                        {{
                          data.item.name
                        }}
                      </v-list-item-title>
                    </v-list-item-content>
                  </template>
                </template>
              </v-autocomplete>
            </v-col>
            <v-col
              cols="12"
              md="12"
            >
              <v-text-field-money
                v-model="newChange.change"
                :label="$vuetify.lang.t('$vuetify.change')"
                required
                :rules="formRule.change"
                :properties="{
                  clearable: true
                }"
                :options="{
                  length: 15,
                  precision: 2,
                  empty: 0
                }"
              />
            </v-col>
          </v-row>
        </v-form>
      </v-card-text>
      <v-card-actions>
        <v-spacer />
        <v-btn
          :disabled="isActionInProgress"
          @click="toogleNewModal(false)"
        >
          <v-icon>mdi-close</v-icon>
          {{ $vuetify.lang.t("$vuetify.actions.cancel") }}
        </v-btn>
        <v-btn
          :disabled="!formValid || isActionInProgress"
          :loading="isActionInProgress"
          color="primary"
          @click="handleSubmit"
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
  data () {
    return {
      formValid: false,
      errorPhone: null,
      formRule: this.$rules
    }
  },
  computed: {
    ...mapState('exchangeRate', ['newChange', 'isActionInProgress']),
    ...mapState('statics', ['arrayCurrency', 'isCountryLoading'])
  },
  created () {
    this.formValid = false
  },
  methods: {
    ...mapActions('exchangeRate', ['createChange', 'toogleNewModal']),
    async handleSubmit () {
      if (this.$refs.form.validate()) {
        await this.createChange(this.newChange)
      }
    }
  }
}
</script>

<style scoped></style>
