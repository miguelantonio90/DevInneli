<template>
  <v-dialog
    v-model="toogleNewModal"
    max-width="450"
    persistent
  >
    <v-card>
      <v-card-title>
        <span class="headline">{{
          $vuetify.lang.t("$vuetify.titles.new", [
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
                v-model="newModifier.name"
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
                v-model="newModifier.shops"
                :items="shops"
                :label="$vuetify.lang.t('$vuetify.menu.shop')"
                item-text="name"
                :loading="isShopLoading"
                :disabled="!!isShopLoading"
                multiple
                :rules="formRule.required"
                required
                return-object
                @change="setModifiers($event)"
              />
            </v-col>
            <v-col
              cols="12"
              md="4"
            >
              <v-switch
                v-model="newModifier.percent"
                inset
                :label="
                  `${
                    newModifier.percent
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
                v-model="newModifier.value"
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
          @click="toogleNewModal(false)"
        >
          <v-icon>mdi-close</v-icon>
          {{ $vuetify.lang.t("$vuetify.actions.cancel") }}
        </v-btn>
        <v-btn
          :disabled="!formValid || isActionInProgress || newModifier.shops.length === 0"
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
    ...mapState('modifiers', ['newModifier', 'isActionInProgress']),
    ...mapState('shop', ['shops', 'isShopLoading'])
  },
  created () {
    this.formValid = false
    this.getShops().then(() => {
      this.newModifier.shops = this.shops
    })
  },
  methods: {
    ...mapActions('modifiers', ['createModifiers', 'toogleNewModal']),
    ...mapActions('shop', ['getShops']),
    setModifiers (shops) {
      this.newModifier.shops = shops
    },
    async handleSubmit () {
      if (this.$refs.form.validate()) {
        await this.createModifiers(this.newModifier)
      }
    }
  }
}
</script>

<style scoped></style>
