<template>
  <v-dialog
    v-model="toogleTransferModal"
    max-width="600px"
    persistent
  >
    <v-card>
      <v-card-title>
        <span class="headline">{{
          $vuetify.lang.t('$vuetify.actions.transfer', [
            $vuetify.lang.t('$vuetify.articles.name'),
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
            <v-col md="6">
              <v-text-field-money
                v-model="editArticle.cost"
                :label="$vuetify.lang.t('$vuetify.articles.cost')"
                required
                :properties="{
                  clearable: true
                }"
                :options="{
                  length: 15,
                  precision: 2,
                  empty: 0.00,
                }"
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
          @click="toogleTransferModal(false)"
        >
          <v-icon>mdi-close</v-icon>
          {{ $vuetify.lang.t('$vuetify.actions.cancel') }}
        </v-btn>
        <v-btn
          :disabled="!formValid || isActionInProgress"
          :loading="isActionInProgress"
          class="mb-2"
          color="primary"
          @click="transferArticleHandler"
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
	name: 'Transfer',
	data () {
		return {}
	},
	computed: {
		...mapState('article', ['editArticle', 'isActionInProgress']),
		...mapState('shop', ['shops'])
	},
	created () {
		this.getShops()
	},
	methods: {
		...mapActions('article', ['toogleTransferModal']),
		...mapActions('shop', ['getShops'])
	}
}
</script>

<style scoped>

</style>
