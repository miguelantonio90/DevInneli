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
            $vuetify.lang.t('$vuetify.menu.access'),
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
              md="3"
            >
              <v-select
                v-model="newAccess.key"
                :items="keys"
                item-text="key"
                :label="$vuetify.lang.t('$vuetify.access.key')"
                requiered
                return-object
                :rules="formRule.key"
                @change="updateAccessPermit"
              >
                <template v-slot:append-outer>
                  <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                      <v-icon
                        v-bind="attrs"
                        v-on="on"
                        @click="$store.dispatch('keys/toogleNewModal',true)"
                      >
                        mdi-plus
                      </v-icon>
                    </template>
                    <span>{{ $vuetify.lang.t('$vuetify.titles.newAction') }}</span>
                  </v-tooltip>
                </template>
              </v-select>
            </v-col>
            <v-col
              cols="12"
              md="3"
            >
              <v-text-field
                v-model="newAccess.name"
                :label="$vuetify.lang.t('$vuetify.access.name')"
                required
                :rules="formRule.position"
              />
            </v-col>
            <v-checkbox
              v-model="newAccess.accessEmail"
              class="md-3"
              :label="$vuetify.lang.t('$vuetify.access.accessEmail')"
            />
            <v-checkbox
              v-model="newAccess.accessPin"
              class="md-3"
              :label="$vuetify.lang.t('$vuetify.access.accessPin')"
            />
            <v-col
              cols="12"
              md="12"
            >
              <v-text-field
                v-model="newAccess.description"
                :label="$vuetify.lang.t('$vuetify.access.description')"
              />
            </v-col>
          </v-row>
          <v-row>
            <v-expansion-panels popout>
              <v-col
                v-for="(access,j) in access_permit"
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
          @click="createNewRole"
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
  data () {
    return {
      formValid: false,
      access_permit: [],
      formRule: this.$rules
    }
  },
  computed: {
    ...mapState('role', ['saved', 'newAccess', 'isActionInProgress']),
    ...mapState('keys', ['saved', 'keys', 'isActionInProgress'])
  },
  created () {
    this.getKeys().then(() => {
      this.newAccess.key = this.keys[0]
      this.access_permit = JSON.parse(this.keys[0].access_permit)
    })
  },
  mounted () {
    this.formValid = false
  },
  methods: {
    ...mapActions('role', ['createRole', 'toogleNewModal']),
    ...mapActions('keys', ['getKeys']),
    updateAccessPermit () {
      this.access_permit = JSON.parse(this.newAccess.key.access_permit)
    },
    async createNewRole () {
      if (this.$refs.form.validate()) {
        this.newAccess.access_permit = this.access_permit
        await this.createRole(this.newAccess)
      }
    }
  }
}
</script>

<style scoped>
</style>
