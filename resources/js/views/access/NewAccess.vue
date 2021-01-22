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
          <v-row
            cols="12"
            justify="space-around"
          >
            <v-col
              md="4"
            >
              <v-text-field
                v-model="newAccess.name"
                :label="$vuetify.lang.t('$vuetify.access.name')"
                required
                :rules="formRule.position"
              />
            </v-col>
            <v-col md="4">
              <v-checkbox
                v-model="newAccess.accessEmail"
                :label="$vuetify.lang.t('$vuetify.access.accessEmail')"
              />
            </v-col>
            <v-col md="4">
              <v-checkbox
                v-model="newAccess.accessPin"
                :label="$vuetify.lang.t('$vuetify.access.accessPin')"
              />
            </v-col>
            <v-col
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
                cols="12"
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
                    <v-row>
                      <v-col
                        v-for="(item,i) in access.actions"
                        :key="i"
                      >
                        <v-switch
                          v-model="item[i]"
                          :label="$vuetify.lang.t('$vuetify.access.access.' + i)"
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
    this.formValid = false
    this.access_permit = [
      {
        title: {
          name: 'manager_article',
          value: true
        },
        actions: { list: true, create: false, edit: false, delete: false, import: false, export: false }

      },
      {
        title: { name: 'manager_refunds', value: false },
        actions: { list: true, create: false, edit: false, delete: false, import: false, export: false }
      },
      {
        title: { name: 'manager_boxes', value: false },
        actions: { list: true, create: false, edit: false, delete: false, import: false, export: false, boxes_open: false, boxes_close: false }
      },
      {
        title: { name: 'manager_vending', value: false },
        actions: { list: true, create: false, edit: false, delete: false, import: false, export: false }
      },
      {
        title: {
          name: 'manager_category',
          value: false
        },
        actions: { list: true, create: false, edit: false, delete: false, import: false, export: false }
      },
      {
        title: {
          name: 'manager_mod',
          value: false
        },
        actions: { list: true, create: false, edit: false, delete: false, import: false, export: false }
      },
      {
        title: {
          name: 'manager_supplier',
          value: false
        },
        actions: { list: true, create: false, edit: false, delete: false, import: false, export: false }
      },
      {
        title: {
          name: 'manager_buy',
          value: false
        },
        actions: { list: true, create: false, edit: false, delete: false, import: false, export: false }
      },
      {
        title: {
          name: 'manager_sell',
          value: false
        },
        actions: { list: true, create: false, edit: false, delete: false, import: false, export: false }
      },
      {
        title: {
          name: 'manager_employer',
          value: false
        },
        actions: { list: true, create: false, edit: false, delete: false, import: false, export: false }
      },
      {
        title: {
          name: 'manager_assistence',
          value: false
        },
        actions: { list: true, create: false, edit: false, delete: false, import: false, export: false }
      },
      {
        title: {
          name: 'manager_client',
          value: false
        },
        actions: { list: true, create: false, edit: false, delete: false, import: false, export: false }
      },
      {
        title: {
          name: 'manager_shop',
          value: false
        },
        actions: { list: true, create: false, edit: false, delete: false, import: false, export: false }
      },
      {
        title: {
          name: 'manager_access',
          value: false
        },
        actions: { list: true, create: false, edit: false, delete: false, import: false, export: false }
      },
      {
        title: {
          name: 'manager_payment',
          value: false
        },
        actions: { list: true, create: false, edit: false, delete: false, import: false, export: false }
      },
      {
        title: {
          name: 'manager_expense_category',
          value: false
        },
        actions: { list: true, create: false, edit: false, delete: false, import: false, export: false }
      },
      {
        title: {
          name: 'manager_exchange_rate',
          value: false
        },
        actions: { list: true, create: false, edit: false, delete: false, import: false, export: false }
      },
      {
        title: {
          name: 'manager_type_of_order',
          value: false
        },
        actions: { list: true, create: false, edit: false, delete: false, import: false, export: false }
      },
      {
        title: {
          name: 'manager_tax',
          value: false
        },
        actions: { list: true, create: false, edit: false, delete: false, import: false, export: false }
      },
      {
        title: {
          name: 'manager_discount',
          value: false
        },
        actions: { list: true, create: false, edit: false, delete: false, import: false, export: false }
      }
    ]
  },
  methods: {
    ...mapActions('role', ['createRole', 'toogleNewModal']),
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
