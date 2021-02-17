<template>
  <v-dialog
    v-model="toogleNewModal"
    max-width="850px"
    persistent
  >
    <v-card>
      <v-card-title>
        <span class="headline">{{
          $vuetify.lang.t('$vuetify.titles.new', [
            $vuetify.lang.t('$vuetify.menu.keys'),
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
          <v-row justify="space-around">
            <v-col
              cols="12"
              md="6"
            >
              <v-text-field
                v-model="newKey.key"
                :label="$vuetify.lang.t('$vuetify.first_name')"
                required
                :rules="formRule.position"
              />
            </v-col>
          </v-row>
          <v-row>
            <v-expansion-panels popout>
              <v-col
                v-for="(access,j) in newKey.access_permit"
                :key="j"
                md="6"
              >
                <v-expansion-panel>
                  <v-expansion-panel-header>
                    <v-switch
                      v-model="access.title.value"
                      :title="$vuetify.lang.t('$vuetify.access.access.' + access.title.name)"
                    >
                      <template v-slot:label>
                        <div>
                          <b>
                            {{ $vuetify.lang.t('$vuetify.access.access.' + access.title.name) }}
                          </b>
                          <v-tooltip
                            right
                            class="md-6"
                          >
                            <template v-slot:activator="{ on, attrs }">
                              <v-icon
                                color="primary"
                                dark
                                v-bind="attrs"
                                v-on="on"
                              >
                                mdi-information-outline
                              </v-icon>
                            </template>
                            <span>{{
                              $vuetify.lang.t('$vuetify.access.access.manager_help')
                            }}</span>
                          </v-tooltip>
                        </div>
                      </template>
                    </v-switch>
                    <template v-slot:actions>
                      <v-icon
                        v-show="access.title.value"
                        color="error"
                      >
                        mdi-key-plus
                      </v-icon>
                    </template>
                  </v-expansion-panel-header>
                  <v-expansion-panel-content v-show="access.title.value">
                    <v-switch
                      v-for="(item,i) in access.actions"
                      :key="i"
                      v-model="access.actions[i]"
                      :label="$vuetify.lang.t('$vuetify.access.access.' + i)"
                    />
                  </v-expansion-panel-content>
                </v-expansion-panel>
              </v-col>
            </v-expansion-panels>
          </v-row>
        </v-form>
      </v-card-text>
      <v-card-actions>
        <v-spacer />
        <v-btn
          class="mb-2"
          :disabled="isActionInProgress"
          @click="toogleNewModal(false)"
        >
          <v-icon>mdi-close</v-icon>
          {{ $vuetify.lang.t('$vuetify.actions.cancel') }}
        </v-btn>
        <v-btn
          :disabled="!formValid || isActionInProgress"
          class="mb-2"
          color="primary"
          :loading="isActionInProgress"
          @click="createNewKey"
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
	name: 'NewKeys',
	data () {
		return {
			formValid: false,
			formRule: this.$rules
		}
	},
	computed: {
		...mapState('keys', ['saved', 'newKey', 'isActionInProgress'])
	},
	mounted () {
		this.formValid = false
	},
	methods: {
		...mapActions('keys', ['createKey', 'toogleNewModal']),
		async createNewKey () {
			if (this.$refs.form.validate()) {
				this.newKey.access_permit = this.access_permit
				await this.createKey(this.newKey)
			}
		}
	}
}
</script>

<style scoped>
</style>
