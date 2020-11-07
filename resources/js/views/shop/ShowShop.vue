<template>
  <v-dialog
    v-model="toogleShowModal"
    max-width="600px"
  >
    <v-card>
      <v-card-title>
        <span class="headline">{{
          $vuetify.lang.t('$vuetify.titles.show', [
            $vuetify.lang.t('$vuetify.menu.shop'),
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
                v-model="editShop.name"
                prepend-icon="mdi-home-variant"
                :label="$vuetify.lang.t('$vuetify.menu.shop')"
                readonly
              />
            </v-col>
            <v-col>
              <vue-tel-input-vuetify
                v-model="editShop.phone"
                :placeholder="$vuetify.lang.t('$vuetify.phone_holder')"
                :label=" $vuetify.lang.t('$vuetify.phone')"
                :select-label="$vuetify.lang.t('$vuetify.country')"
                v-bind="bindProps"
                readonly
              />
            </v-col>
          </v-row>
          <v-row>
            <v-col
              cols="12"
              md="12"
            >
              <v-text-field
                v-model="editShop.address"
                prepend-icon="mdi-home-map-marker"
                :label="$vuetify.lang.t('$vuetify.address')"
                required
                readonly
              />
            </v-col>
            <v-col
              cols="12"
              md="12"
            >
              <v-text-field
                v-model="editShop.description"
                prepend-icon="mdi-file-document"
                :label="$vuetify.lang.t('$vuetify.access.description')"
                required
                readonly
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
          @click="toogleShowModal(false)"
        >
          <v-icon>mdi-close</v-icon>
          {{ $vuetify.lang.t('$vuetify.actions.cancel') }}
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import { mapActions, mapState } from 'vuex'

export default {
  name: 'ShowShop',
  data () {
    return {
      errorPhone: null,
      formValid: false,
      formRule: {}
    }
  },
  computed: {
    ...mapState('shop', ['saved', 'editShop', 'isActionInProgress']),
    bindProps () {
      return {
        mode: 'international',
        defaultCountry: this.editShop.country ? this.editShop.country : 'US',
        disabledFetchingCountry: false,
        autocomplete: 'off',
        dropdownOptions: {
          disabledDialCode: false
        },
        inputOptions: {
          showDialCode: false
        }
      }
    }
  },
  methods: {
    ...mapActions('shop', ['updateShop', 'toogleShowModal'])
  }
}
</script>

<style scoped></style>
