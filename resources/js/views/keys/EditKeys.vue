<template>
  <v-dialog
    v-model="toogleEditModal"
    max-width="850px"
    persistent
  >
    <v-card>
      <v-card-title>
        <span class="headline">{{
          $vuetify.lang.t('$vuetify.titles.edit', [
            $vuetify.lang.t('$vuetify.menu.keys')
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
                v-model="editKey.key"
                :label="$vuetify.lang.t('$vuetify.first_name')"
                :rules="formRule.position"
                required
              />
            </v-col>
          </v-row>
          <v-row>
            <v-expansion-panels popout>
              <v-col
                v-for="(access, j) in access_permit"
                :key="j"
                md="6"
              >
                <v-expansion-panel>
                  <v-expansion-panel-header>
                    <v-switch
                      v-model="access.title.value"
                      :title="
                        $vuetify.lang.t(
                          '$vuetify.access.access.' +
                            access.title.name
                        )
                      "
                    >
                      <template v-slot:label>
                        <div>
                          <b>
                            {{
                              $vuetify.lang.t(
                                '$vuetify.access.access.' +
                                  access.title
                                    .name
                              )
                            }}
                          </b>
                          <v-tooltip
                            class="md-6"
                            right
                          >
                            <template
                              v-slot:activator="{
                                on,
                                attrs
                              }"
                            >
                              <v-icon
                                v-bind="attrs"
                                color="primary"
                                dark
                                v-on="on"
                              >
                                mdi-information-outline
                              </v-icon>
                            </template>
                            <span>{{
                              $vuetify.lang.t(
                                '$vuetify.access.access.manager_help'
                              )
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
                  <v-expansion-panel-content
                    v-show="access.title.value"
                  >
                    <v-switch
                      v-for="(item, i) in access.actions"
                      :key="i"
                      v-model="access.actions[i]"
                      :label="
                        $vuetify.lang.t(
                          '$vuetify.access.access.' +
                            i
                        )
                      "
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
          :disabled="isActionInProgress"
          class="mb-2"
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
          @click="updateKeyHandler"
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
  name: 'EditKeys',
  data () {
    return {
      formValid: false,
      access_permit: [],
      formRule: this.$rules
    }
  },
  computed: {
    ...mapState('keys', ['saved', 'editKey', 'isActionInProgress'])
  },
  created () {
    this.access_permit = this.editKey.access_permit
  },
  methods: {
    ...mapActions('keys', ['updateKey', 'toogleEditModal']),

    async updateKeyHandler () {
      if (this.$refs.form.validate()) {
        this.editKey.access_permit = this.access_permit
        await this.updateKey(this.editKey)
      }
    }
  }
}
</script>

<style scoped></style>
