<template>
  <v-dialog
    v-model="toogleEditModal"
    max-width="450"
    persistent
  >
    <v-card>
      <v-card-title>
        <span class="headline">{{
          $vuetify.lang.t('$vuetify.titles.newF', [$vuetify.lang.t('$vuetify.menu.box')])
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
                v-model="editBox.name"
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
                v-model="editBox.shop"
                readonly
                style="margin-top: 15px"
                :items="shops"
                :label="$vuetify.lang.t('$vuetify.menu.shop')"
                item-text="name"
                disabled
                return-object
                required
                :rules="formRule.country"
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
          {{ $vuetify.lang.t('$vuetify.actions.cancel') }}
        </v-btn>
        <v-btn
          :disabled="!formValid || isActionInProgress"
          :loading="isActionInProgress"
          class="mb-2"
          color="primary"
          @click="editBoxHandler"
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
	name: 'EditBox',
	data () {
		return {
			formValid: false,
			errorPhone: null,
			formRule: this.$rules
		}
	},
	computed: {
		...mapState('boxes', ['saved', 'editBox', 'isActionInProgress']),
		...mapState('shop', ['saved', 'shops'])
	},
	async created () {
		this.formValid = false
		await this.getShops()
	},
	methods: {
		...mapActions('boxes', ['updateBox', 'toogleEditModal']),
		...mapActions('shop', ['getShops']),
		inputColor (color) {
			this.editCategory.color = color
		},
		async editBoxHandler () {
			if (this.$refs.form.validate()) {
				this.loading = true
				await this.updateBox(this.editBox)
			}
		}
	}
}
</script>

<style scoped></style>
