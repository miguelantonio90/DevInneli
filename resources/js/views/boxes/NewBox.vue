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
              $vuetify.lang.t('$vuetify.menu.box')
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
                md="6"
            >
              <v-text-field
                  v-model="newBox.name"
                  :label="$vuetify.lang.t('$vuetify.firstName')"
                  :rules="formRule.firstName"
                  required
              />
            </v-col>
            <v-col
                class="py-0"
                cols="12"
                md="6"
            >
              <v-select
                  v-model="newBox.shop"
                  :clearable="shops.length > 1"
                  :disabled="!!isShopLoading"
                  :items="shops"
                  :label="$vuetify.lang.t('$vuetify.menu.shop')"
                  :loading="isShopLoading"
                  :rules="formRule.country"
                  item-text="name"
                  required
                  return-object
                  style="margin-top: 15px"
              />
            </v-col>
          </v-row>
        </v-form>
      </v-card-text>
      <v-card-actions>
        <v-spacer/>
        <v-btn
            :disabled="isActionInProgress"
            class="mb-2"
            @click="toogleNewModal(false)"
        >
          <v-icon>mdi-close</v-icon>
          {{ $vuetify.lang.t('$vuetify.actions.cancel') }}
        </v-btn>
        <v-btn
            :disabled="!formValid || isActionInProgress"
            :loading="isActionInProgress"
            class="mb-2"
            color="primary"
            @click="createNewBox"
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
  name: 'NewBox',
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
    ...mapState('boxes', ['saved', 'newBox', 'isActionInProgress']),
    ...mapState('shop', ['shops', 'isShopLoading'])
  },
  async created () {
    this.formValid = false
    await this.getShops().then(s => {
      this.newBox.shop = this.shops[0]
    })
  },
  methods: {
    ...mapActions('boxes', ['createBox', 'toogleNewModal']),
    ...mapActions('shop', ['getShops']),
    inputColor (color) {
      this.newBox.color = color
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
    async createNewBox () {
      if (this.$refs.form.validate()) {
        this.loading = true
        await this.createBox(this.newBox)
      }
    }
  }
}
</script>

<style scoped></style>
