<template>
  <v-card>
    <v-card-title>
      <span class="headline">
        {{ $vuetify.lang.t("$vuetify.menu.shop_online") }}
      </span>
    </v-card-title>
    <v-card-text>
      <v-stepper v-model="step">
        <v-stepper-header>
          <v-stepper-step
            :complete="step > 1"
            :editable="formValid"
            step="1"
          >
            {{ $vuetify.lang.t("$vuetify.panel.basic") }}
          </v-stepper-step>
          <v-divider />
          <v-stepper-step
            :step="2"
            editable
          >
            {{
              $vuetify.lang.t(
                "$vuetify.representation.representation"
              )
            }}
          </v-stepper-step>
          <v-divider />
          <v-stepper-step
            :step="3"
            editable
          >
            {{ $vuetify.lang.t("$vuetify.online.credits_pay") }}
          </v-stepper-step>
        </v-stepper-header>

        <v-stepper-items>
          <v-stepper-content step="1">
            <v-card>
              <v-card-text>
                <v-form
                  ref="form"
                  v-model="formValid"
                  style="padding: 0"
                  lazy-validation
                >
                  <v-row>
                    <v-col
                      class="py-0"
                      cols="12"
                      md="4"
                    >
                      <v-select
                        v-model="config.shop"
                        chips
                        rounded
                        :disabled="managerConfig"
                        :items="shopsNoConfig"
                        :label="
                          $vuetify.lang.t(
                            '$vuetify.menu.shop'
                          )
                        "
                        item-text="name"
                        :loading="isShopLoading"
                        return-object
                        required
                        :rules="formRule.country"
                      />
                    </v-col>
                    <v-col
                      class="py-0"
                      cols="12"
                      md="4"
                    >
                      <v-select
                        v-model="config.template"
                        rounded
                        :items="templates"
                        :label="
                          $vuetify.lang.t(
                            '$vuetify.online.template'
                          )
                        "
                        item-text="name"
                        :loading="isShopLoading"
                        return-object
                        required
                        :rules="formRule.country"
                      />
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col
                      v-for="(n, i) in templateView[0]"
                      :key="i"
                      class="d-flex child-flex"
                      cols="6"
                    >
                      <v-img
                        :src="n.src"
                        :lazy-src="n.src"
                        aspect-ratio="1"
                        class="grey lighten-2"
                      >
                        <template v-slot:placeholder>
                          <v-row
                            class="fill-height ma-0"
                            align="center"
                            justify="center"
                          >
                            <v-progress-circular
                              indeterminate
                              color="grey lighten-5"
                            />
                          </v-row>
                        </template>
                      </v-img>
                    </v-col>
                  </v-row>
                </v-form>
              </v-card-text>
              <v-card-actions>
                <v-btn
                  color="primary"
                  :disabled="!formValid || isShopLoading"
                  @click="step = 2"
                >
                  {{
                    $vuetify.lang.t("$vuetify.actions.next")
                  }}
                  <v-icon>mdi-chevron-right</v-icon>
                </v-btn>
                <v-spacer />

                <v-btn
                  class="mb-2"
                  :disabled="isShopLoading"
                  @click="
                    $router.push({ name: 'product_list' })
                  "
                >
                  <v-icon>mdi-close</v-icon>
                  {{
                    $vuetify.lang.t(
                      "$vuetify.actions.cancel"
                    )
                  }}
                </v-btn>
                <v-btn
                  class="mb-2"
                  color="primary"
                  :disabled="!formValid || isShopLoading"
                  :loading="isShopLoading"
                  @click="managerConfigHandler"
                >
                  {{
                    $vuetify.lang.t("$vuetify.actions.save")
                  }}
                  <v-icon>mdi-check</v-icon>
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-stepper-content>
          <v-stepper-content :step="2">
            <v-card>
              <v-card-text>
                <app-upload-multiple-image
                  style="width: 100%"
                  :data-images="config.images"
                  :upload-success="uploadImage"
                />
              </v-card-text>
              <v-card-actions>
                <v-btn
                  text
                  @click="step = 2"
                >
                  <v-icon>mdi-chevron-left</v-icon>
                  {{
                    $vuetify.lang.t("$vuetify.actions.back")
                  }}
                </v-btn>
                <v-btn
                  color="primary"
                  :disabled="!formValid || isShopLoading"
                  @click="step = 3"
                >
                  {{
                    $vuetify.lang.t("$vuetify.actions.next")
                  }}
                  <v-icon>mdi-chevron-right</v-icon>
                </v-btn>
                <v-spacer />
                <v-btn
                  class="mb-2"
                  :disabled="isShopLoading"
                  @click="
                    $router.push({ name: 'product_list' })
                  "
                >
                  <v-icon>mdi-close</v-icon>
                  {{
                    $vuetify.lang.t(
                      "$vuetify.actions.cancel"
                    )
                  }}
                </v-btn>
                <v-btn
                  class="mb-2"
                  color="primary"
                  :disabled="!formValid || isShopLoading"
                  :loading="isShopLoading"
                  @click="managerConfigHandler"
                >
                  <v-icon>mdi-check</v-icon>
                  {{
                    $vuetify.lang.t("$vuetify.actions.save")
                  }}
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-stepper-content>
          <v-stepper-content :step="3">
            <v-card>
              <v-card-text>
                <v-row>
                  <v-col
                    cols="12"
                    md="4"
                  >
                    <v-text-field
                      v-model="
                        config.credentials.paypal
                          .client_id
                      "
                      :label="
                        $vuetify.lang.t(
                          '$vuetify.online.paypal_client_id'
                        )
                      "
                      :rules="formRule.required"
                      required
                      autofocus
                    />
                  </v-col>
                  <v-col
                    cols="12"
                    md="4"
                  >
                    <v-text-field
                      v-model="
                        config.credentials.paypal
                          .paypal_secret
                      "
                      :label="
                        $vuetify.lang.t(
                          '$vuetify.online.paypal_secret'
                        )
                      "
                      :rules="formRule.required"
                      required
                      autofocus
                    />
                  </v-col>
                </v-row>
              </v-card-text>
              <v-card-actions>
                <v-btn
                  text
                  @click="step = 1"
                >
                  <v-icon>mdi-chevron-left</v-icon>
                  {{
                    $vuetify.lang.t("$vuetify.actions.back")
                  }}
                </v-btn>
                <v-spacer />
                <v-btn
                  class="mb-2"
                  :disabled="isShopLoading"
                  @click="
                    $router.push({ name: 'product_list' })
                  "
                >
                  <v-icon>mdi-close</v-icon>
                  {{
                    $vuetify.lang.t(
                      "$vuetify.actions.cancel"
                    )
                  }}
                </v-btn>
                <v-btn
                  class="mb-2"
                  color="primary"
                  :disabled="!formValid || isShopLoading"
                  :loading="isShopLoading"
                  @click="managerConfigHandler"
                >
                  <v-icon>mdi-check</v-icon>
                  {{
                    $vuetify.lang.t("$vuetify.actions.save")
                  }}
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-stepper-content>
        </v-stepper-items>
      </v-stepper>
    </v-card-text>
  </v-card>
</template>
<script>
import { mapActions, mapState } from 'vuex'

export default {
  name: 'ManagerOnlineConfig',
  data () {
    return {
      formValid: false,
      step: 1,
      formRule: this.$rules,
      templateView: [
        [
          {
            src: require('../shops-templates/shipit/assets/template/1.jpg')
          },
          {
            src: require('../shops-templates/shipit/assets/template/2.jpg')
          }
        ]
      ],
      templates: [
        {
          name: 'ShipIT'
        }
      ],
      config: {}
    }
  },
  computed: {
    ...mapState('shop', ['shopsNoConfig', 'isShopLoading']),
    ...mapState('online', ['newConfig', 'editConfig', 'managerConfig'])
  },
  async created () {
    if (!this.managerConfig) {
      await this.getShopsNoConfig()
      this.config = this.newConfig
      this.config.shop = this.shopsNoConfig[0]
      this.config.template = this.templates[0]
    } else {
      this.config = this.editConfig
    }
  },
  methods: {
    ...mapActions('shop', ['getShopsNoConfig']),
    ...mapActions('online', ['createConfig', 'updateConfig']),
    uploadImage (formData, index, fileList) {
      this.config.images = fileList
    },
    managerConfigHandler () {
      if (this.managerConfig) {
        this.updateConfig(this.config)
      } else {
        this.createConfig(this.config)
      }
    }
  }
}
</script>

<style scoped></style>
