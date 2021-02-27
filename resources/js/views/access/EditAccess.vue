<template>
  <v-dialog
    v-model="toogleEditModal"
    max-width="850px"
    persistent
  >
    <v-card>
      <v-card-title>
        <span class="headline">{{
          $vuetify.lang.t("$vuetify.titles.edit", [
            $vuetify.lang.t("$vuetify.menu.access")
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
          <v-row
            cols="12"
            justify="space-around"
          >
            <v-col md="4">
              <v-text-field
                v-model="editAccess.name"
                :label="$vuetify.lang.t('$vuetify.access.name')"
                required
                :rules="formRule.position"
              />
            </v-col>
            <v-col md="4">
              <v-checkbox
                v-model="editAccess.accessEmail"
                :label="
                  $vuetify.lang.t(
                    '$vuetify.access.accessEmail'
                  )
                "
              />
            </v-col>
            <v-col md="4">
              <v-checkbox
                v-model="editAccess.accessPin"
                :label="
                  $vuetify.lang.t('$vuetify.access.accessPin')
                "
              />
            </v-col>
            <v-col md="12">
              <v-text-field
                v-model="editAccess.description"
                :label="
                  $vuetify.lang.t(
                    '$vuetify.access.description'
                  )
                "
              />
            </v-col>
          </v-row>
          <v-row>
            <v-expansion-panels popout>
              <v-col
                v-for="(access, j) in access_permit"
                :key="j"
                cols="12"
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
                                "$vuetify.access.access." +
                                  access.title
                                    .name
                              )
                            }}
                          </b>
                          <v-tooltip
                            right
                            class="md-6"
                          >
                            <template
                              v-slot:activator="{
                                on,
                                attrs
                              }"
                            >
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
                              $vuetify.lang.t(
                                "$vuetify.access.access.manager_help"
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
                    <v-row>
                      <v-col
                        v-for="(item,
                                i) in access.actions"
                        :key="i"
                      >
                        <v-switch
                          v-model="item[i]"
                          :label="
                            $vuetify.lang.t(
                              '$vuetify.access.access.' +
                                i
                            )
                          "
                        />
                      </v-col>
                    </v-row>
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
          @click="toogleEditModal(false)"
        >
          <v-icon>mdi-close</v-icon>
          {{ $vuetify.lang.t("$vuetify.actions.cancel") }}
        </v-btn>
        <v-btn
          :disabled="!formValid || isActionInProgress"
          class="mb-2"
          color="primary"
          :loading="isActionInProgress"
          @click="updateRoleHandler"
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
      access_permit: [],
      key: {},
      formRule: this.$rules
    }
  },
  computed: {
    ...mapState('role', [
      'saved',
      'editAccess',
      'keys',
      'isActionInProgress'
    ]),
    ...mapState('keys', ['keys'])
  },
  created () {
    this.access_permit = []
    this.access_permit = JSON.parse(this.editAccess.access_permit)
  },
  methods: {
    ...mapActions('role', ['updateRole', 'toogleEditModal']),
    updateAccessPermit () {
      this.access_permit = []
      this.access_permit = JSON.parse(this.newAccess.key.access_permit)
    },

    async updateRoleHandler () {
      if (this.$refs.form.validate()) {
        this.editAccess.access_permit = this.access_permit
        await this.updateRole(this.editAccess)
      }
    }
  }
}
</script>

<style scoped></style>
