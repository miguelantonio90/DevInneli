<template>
  <v-dialog
    v-model="toogleEditModal"
    max-width="450"
    persistent
  >
    <v-card>
      <v-card-title>
        <span class="headline">{{
          $vuetify.lang.t("$vuetify.titles.edit", [
            $vuetify.lang.t("$vuetify.menu.modifiers")
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
                v-model="editModifier.name"
                :label="$vuetify.lang.t('$vuetify.name')"
                :rules="formRule.firstName"
                required
              />
            </v-col>
            <v-col
              cols="12"
              md="12"
            >
              <v-select
                v-model="editModifier.shops"
                :items="shops"
                :label="$vuetify.lang.t('$vuetify.menu.shop')"
                item-text="name"
                :loading="isShopLoading"
                :disabled="!!isShopLoading"
                multiple
                :rules="formRule.shops"
                required
                return-object
              >
                <template v-slot:append-outer>
                  <v-tooltip bottom>
                    <template
                      v-slot:activator="{ on, attrs }"
                    >
                      <v-icon
                        v-bind="attrs"
                        v-on="on"
                        @click="
                          $store.dispatch(
                            'shop/toogleNewModal',
                            true
                          )
                        "
                      >
                        mdi-plus
                      </v-icon>
                    </template>
                    <span>{{
                      $vuetify.lang.t(
                        "$vuetify.titles.newAction"
                      )
                    }}</span>
                  </v-tooltip>
                </template>
              </v-select>
            </v-col>
            <v-col
              cols="12"
              md="4"
            >
              <v-switch
                v-model="editModifier.percent"
                inset
                :label="
                  `${
                    editModifier.percent
                      ? $vuetify.lang.t(
                        '$vuetify.tax.percent'
                      )
                      : $vuetify.lang.t(
                        '$vuetify.tax.permanent'
                      )
                  }`
                "
              />
            </v-col>
            <v-col
              cols="12"
              md="8"
            >
              <v-text-field-money
                v-model="editModifier.value"
                :label="$vuetify.lang.t('$vuetify.tax.value')"
                :rules="formRule.required"
                required
                :properties="{
                  clearable: true
                }"
                :options="{
                  length: 15,
                  precision: 2,
                  empty: 0.0
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
          @click="toogleEditModal(false)"
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
      formRule: this.$rules
    }
  },
  computed: {
    ...mapState('modifiers', ['editModifier', 'isActionInProgress']),
    ...mapState('shop', ['shops', 'isShopLoading'])
  },
  created () {
    this.formValid = false
    this.getShops()
  },
  methods: {
    ...mapActions('modifiers', ['updateModifiers', 'toogleEditModal']),
    ...mapActions('shop', ['getShops']),
    async handleSubmit () {
      if (this.$refs.form.validate()) {
        await this.updateModifiers(this.editModifier)
      }
    }
  }
}
</script>

<style scoped></style>
