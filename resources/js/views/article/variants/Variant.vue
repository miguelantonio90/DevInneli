<template>
  <v-container>
    <v-dialog
      v-model="dialog"
      max-width="500px"
    >
      <v-card>
        <v-card-title>
          <span class="headline">{{
            $vuetify.lang.t(
              newVariant
                ? "$vuetify.titles.edit"
                : "$vuetify.titles.newF",
              [this.$vuetify.lang.t("$vuetify.variants.variant")]
            )
          }}</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col
                cols="12"
                sm="4"
                md="4"
              >
                <v-text-field
                  v-model="name"
                  :hint="hintText[0]"
                  persistent-hint
                  style="margin-top: 14px"
                  :label="
                    $vuetify.lang.t(
                      '$vuetify.variants.name'
                    )
                  "
                />
              </v-col>
              <v-col
                cols="12"
                sm="8"
                md="8"
              >
                <v-combobox
                  v-model="select"
                  multiple
                  value=""
                  :label="
                    $vuetify.lang.t(
                      '$vuetify.variants.options'
                    )
                  "
                  chips
                  deletable-chips
                  class="tag-input"
                />
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn
            class="mb-2"
            @click="closeDialog"
          >
            <v-icon>mdi-close</v-icon>
            {{ $vuetify.lang.t("$vuetify.actions.cancel") }}
          </v-btn>
          <v-btn
            class="mb-2"
            color="primary"
            :disabled="
              select.length === 0 || name === '' || hintText[1]
            "
            @click="saveVariant"
          >
            <v-icon>mdi-check</v-icon>
            {{ $vuetify.lang.t("$vuetify.actions.accept") }}
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <image-dialog
      :dialog="image"
      :images="itemImg.images"
      @saveImage="saveImage"
      @closeDialog="image = false"
    />
    <template>
      <v-chip
        v-for="variant in article.variants"
        :key="variant.name"
        close
        close-icon="mdi-delete"
        color="success"
        text-color="white"
        @click:close="removeVariant(variant)"
      >
        <v-icon
          left
          @click="editVariant(variant)"
        >
          mdi-pencil
        </v-icon>
        {{ variant.name }}
      </v-chip>
    </template>
    <app-data-table
      :view-show-filter="false"
      :view-edit-button="false"
      :manager="'article'"
      :headers="getHeader"
      :items="article.variant_values"
      @create-row="addVariant"
      @delete-row="deleteVariantValue($event)"
    >
      <template v-slot:item.images="{ item }">
        <template v-if="item.images.length === 0">
          <!--div
            id="multiple-image"
            style="display: flex; justify-content: center;"
          >
            <app-upload-multiple-image
              :data-images="item.images"
              :upload-success="uploadImage"
            />
          </div-->
          <div class="my-2">
            <v-btn
              color="primary"
              fab
              x-small
              dark
              @click="showImageDialog(item)"
            >
              <v-icon>mdi-plus</v-icon>
            </v-btn>
          </div>
        </template>
      </template>
      <template v-slot:item.price="{ item }">
        <v-edit-dialog
          :return-value.sync="item.price"
          large
          persistent
          :cancel-text="$vuetify.lang.t('$vuetify.actions.cancel')"
          :save-text="$vuetify.lang.t('$vuetify.actions.save')"
          @save="$emit('updateVariant')"
        >
          <div>
            {{ `${user.company.currency + " " + item.price}` }}
          </div>
          <template v-slot:input>
            <div class="mt-4 title">
              {{ $vuetify.lang.t("$vuetify.actions.edit") }}
            </div>
            <v-text-field-money
              v-model="item.price"
              :label="$vuetify.lang.t('$vuetify.actions.edit')"
              required
              :properties="{
                prefix: user.company.currency,
                clearable: true
              }"
              :options="{
                length: 15,
                precision: 2,
                empty: 0.0
              }"
            />
          </template>
        </v-edit-dialog>
      </template>
      <template v-slot:item.onlinePrice="{ item }">
        <v-edit-dialog
          :return-value.sync="item.onlinePrice"
          large
          persistent
          :cancel-text="$vuetify.lang.t('$vuetify.actions.cancel')"
          :save-text="$vuetify.lang.t('$vuetify.actions.save')"
          @save="$emit('updateVariant')"
        >
          <div>
            {{
              `${user.company.currency + " " + item.onlinePrice}`
            }}
          </div>
          <template v-slot:input>
            <div class="mt-4 title">
              {{ $vuetify.lang.t("$vuetify.actions.edit") }}
            </div>
            <v-text-field-money
              v-model="item.onlinePrice"
              :label="$vuetify.lang.t('$vuetify.actions.edit')"
              required
              :properties="{
                prefix: user.company.currency,
                clearable: true
              }"
              :options="{
                length: 15,
                precision: 2,
                empty: 0.0
              }"
            />
          </template>
        </v-edit-dialog>
      </template>
      <template v-slot:item.cost="{ item }">
        <v-edit-dialog
          :return-value.sync="item.cost"
          large
          persistent
          :cancel-text="$vuetify.lang.t('$vuetify.actions.cancel')"
          :save-text="$vuetify.lang.t('$vuetify.actions.save')"
        >
          <div>
            {{ `${user.company.currency + " " + item.cost}` }}
          </div>
          <template v-slot:input>
            <div class="mt-4 title">
              {{ $vuetify.lang.t("$vuetify.actions.edit") }}
            </div>
            <v-text-field-money
              v-model="item.cost"
              :label="$vuetify.lang.t('$vuetify.actions.edit')"
              required
              :properties="{
                prefix: user.company.currency,
                clearable: true
              }"
              :options="{
                length: 15,
                precision: 2,
                empty: 0.0
              }"
            />
          </template>
        </v-edit-dialog>
      </template>
      <template v-slot:item.ref="{ item }">
        <v-edit-dialog
          :return-value.sync="item.ref"
          large
          persistent
          :cancel-text="$vuetify.lang.t('$vuetify.actions.cancel')"
          :save-text="$vuetify.lang.t('$vuetify.actions.save')"
        >
          <div>{{ item.ref }}</div>
          <template v-slot:input>
            <div class="mt-4 title">
              {{ $vuetify.lang.t("$vuetify.actions.edit") }}
            </div>
            <v-text-field
              v-model="item.ref"
              :label="$vuetify.lang.t('$vuetify.articles.ref')"
              required
              @keypress="numbers"
            />
          </template>
        </v-edit-dialog>
      </template>
      <template v-slot:item.barCode="{ item }">
        <v-edit-dialog
          :return-value.sync="item.barCode"
          large
          persistent
          :cancel-text="$vuetify.lang.t('$vuetify.actions.cancel')"
          :save-text="$vuetify.lang.t('$vuetify.actions.save')"
        >
          <div>{{ item.barCode }}</div>
          <template v-slot:input>
            <div class="mt-4 title">
              {{ $vuetify.lang.t("$vuetify.actions.edit") }}
            </div>
            <v-text-field-simplemask
              v-model="item.barCode"
              :label="$vuetify.lang.t('$vuetify.barCode')"
              :properties="{
                clearable: true
              }"
              :options="{
                inputMask: '##-####-####-###',
                outputMask: '#############',
                empty: 0,
                alphanumeric: true
              }"
              :focus="focus"
              @focus="focus = false"
            />
          </template>
        </v-edit-dialog>
      </template>
    </app-data-table>
  </v-container>
</template>

<script>
import { mapGetters } from 'vuex'
import ImageDialog from './imageDialog'

export default {
  name: 'Variant',
  components: { ImageDialog },
  props: {
    article: {
      type: Object,
      default: function () {
        return {}
      }
    }
  },
  data () {
    return {
      image: false,
      cell: -1,
      focus: false,
      itemImg: {},
      ref: '10000',
      name: '',
      editedIndex: -1,
      variantManager: {
        name: '',
        value: []
      },
      hintText: ['', false],
      select: [],
      newVariant: true,
      dialog: false
    }
  },
  computed: {
    ...mapGetters('auth', ['user', 'userPin']),
    getHeader () {
      return [
        {
          text: this.$vuetify.lang.t('$vuetify.representation.image'),
          value: 'images'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.variants.name'),
          value: 'name'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.variants.price'),
          value: 'price'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.onlinePrice'),
          value: 'onlinePrice'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.variants.cost'),
          value: 'cost'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.variants.ref'),
          value: 'ref'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.variants.barCode'),
          value: 'barCode'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.actions.actions'),
          value: 'actions',
          sortable: false
        }
      ]
    }
  },
  watch: {
    name: function () {
      this.hintText = ['', false]
      if (
        this.article.variants.filter(art => art.name === this.name)
          .length > 0
      ) {
        if (
          this.article.variants.filter(
            art => art.name === this.name
          )[0] !== this.variantManager
        ) {
          this.hintText = [
            this.$vuetify.lang.t('$vuetify.messages.warning_exist'),
            true
          ]
        }
      }
    }
  },
  created () {
    this.ref = parseFloat(this.article.ref) + 1
  },
  methods: {
    showImageDialog (item) {
      this.image = true
      this.item = item
    },
    saveImage (images) {
      this.item.images = images
    },
    uploadImage (formData, index, fileList) {
      console.log(index, fileList, formData)
    },
    numbers (event) {
      const regex = new RegExp('^\\d+(.\\d{1,2})?$')
      const key = String.fromCharCode(
        !event.charCode ? event.which : event.charCode
      )
      if (!regex.test(key)) {
        event.preventDefault()
        return false
      }
    },
    addVariant () {
      this.variantManager = {
        name: '',
        value: []
      }
      this.newVariant = true
      this.select = []
      this.dialog = true
    },
    saveVariant () {
      if (this.newVariant) {
        this.article.variants.push({
          name: this.name,
          value: this.select
        })
      } else {
        this.article.variants[this.editedIndex] = {
          name: this.name,
          value: this.select
        }
      }
      this.myComb()
      this.closeDialog()
    },
    closeDialog () {
      this.dialog = false
      this.name = ''
      this.select = []
    },
    myComb () {
      const data = this.article.variants
      let result = []
      let localResult = []
      data.forEach((value, index) => {
        if (index === 0) {
          value.value.forEach(localValue => {
            const localArticle = this.article.variant_values.filter(
              sh => sh.name === localValue
            )
            if (localArticle.length === 0) {
              this.ref = parseInt(this.ref) + 1
            }
            result.push({
              articles_shops:
                                localArticle.length > 0
                                  ? localArticle[0].articles_shops
                                  : [],
              images: [],
              name: localValue.toString(),
              price:
                                localArticle.length > 0
                                  ? parseFloat(localArticle[0].price).toFixed(
                                    2
                                  )
                                  : parseFloat(this.article.price).toFixed(2),
              onlinePrice:
                                localArticle.length > 0
                                  ? parseFloat(
                                    localArticle[0].onlinePrice
                                  ).toFixed(2)
                                  : parseFloat(
                                    this.article.onlinePrice
                                  ).toFixed(2),
              cost:
                                localArticle.length > 0
                                  ? parseFloat(localArticle[0].cost).toFixed(
                                    2
                                  )
                                  : parseFloat(this.article.cost).toFixed(2),
              ref:
                                localArticle.length > 0
                                  ? localArticle[0].ref
                                  : this.ref,
              barCode:
                                localArticle.length > 0
                                  ? localArticle[0].barCode
                                  : this.barCode
            })
            if (localArticle.length > 0) {
              result[result.length - 1].id = localArticle[0].id
            }
          })
        } else {
          value.value.forEach(localValue => {
            localResult.forEach(v => {
              const localArticle = this.article.variant_values.filter(
                sh =>
                  sh.name ===
                                        localValue.toString() +
                                            '/' +
                                            v.name.toString() ||
                                    sh.name ===
                                        v.name.toString() +
                                            '/' +
                                            localValue.toString()
              )
              if (localArticle.length === 0) {
                this.ref = parseInt(this.ref) + 1
              }
              result.push({
                articles_shops:
                                    localArticle.length > 0
                                      ? localArticle[0].articles_shops
                                      : [],
                name:
                                    localArticle.length > 0
                                      ? localArticle[0].name
                                      : localValue.toString() +
                                          '/' +
                                          v.name.toString(),
                price:
                                    localArticle.length > 0
                                      ? parseFloat(
                                        localArticle[0].price
                                      ).toFixed(2)
                                      : parseFloat(
                                        this.article.price
                                      ).toFixed(2),
                onlinePrice:
                                    localArticle.length > 0
                                      ? parseFloat(
                                        localArticle[0].onlinePrice
                                      ).toFixed(2)
                                      : parseFloat(
                                        this.article.onlinePrice
                                      ).toFixed(2),
                cost:
                                    localArticle.length > 0
                                      ? parseFloat(
                                        localArticle[0].cost
                                      ).toFixed(2)
                                      : parseFloat(
                                        this.article.cost
                                      ).toFixed(),
                ref:
                                    localArticle.length > 0
                                      ? localArticle[0].ref
                                      : this.ref,
                barCode:
                                    localArticle.length > 0
                                      ? localArticle[0].barCode
                                      : this.barCode
              })
              if (localArticle.length > 0) {
                result[result.length - 1].id =
                                    localArticle[0].id
              }
            })
          })
        }
        localResult = result
        if (index + 1 !== data.length) {
          result = []
        }
      })
      this.article.variant_values = result
    },
    removeVariant (tag) {
      this.article.variants.splice(tag, 1)
      this.myComb()
    },
    editVariant (variant) {
      this.variantManager = variant
      this.editedIndex = this.article.variants.indexOf(variant)
      this.name = variant.name
      this.select = variant.value
      this.newVariant = false
      this.dialog = true
    },
    deleteVariantValue (item) {
      this.$Swal
        .fire({
          title: this.$vuetify.lang.t('$vuetify.titles.delete', [
            this.$vuetify.lang.t('$vuetify.variants.variant')
          ]),
          text: this.$vuetify.lang.t('$vuetify.messages.sure_delete'),
          icon: 'warning',
          showCancelButton: true,
          cancelButtonText: this.$vuetify.lang.t(
            '$vuetify.actions.cancel'
          ),
          confirmButtonText: this.$vuetify.lang.t(
            '$vuetify.actions.delete'
          ),
          confirmButtonColor: 'red'
        })
        .then(result => {
          if (result.value) {
            if (!item.id) {
              this.article.variant_values.splice(item, 1)
            }
            if (this.article.variants.length === 1) {
              this.article.variants[0].value.length === 1
                ? this.removeVariant(this.article.variants[0])
                : this.article.variants[0].value.splice(
                  item.name,
                  1
                )
            }
          }
        })
    }
  }
}
</script>

<style scoped>
.tag-input span {
    background-color: #1976d2;
    color: #fff;
    font-size: 1em;
}

.tag-input span {
    background-color: #1976d2;
    color: #fff;
    font-size: 1em;
    padding-left: 7px;
}

.tag-input span::before {
    content: "label";
    font-family: "Material Icons", serif;
    font-weight: normal;
    font-style: normal;
    font-size: 20px;
    line-height: 1;
    letter-spacing: normal;
    text-transform: none;
    display: inline-block;
    white-space: nowrap;
    word-wrap: normal;
    direction: ltr;
    font-feature-settings: "liga";
    -webkit-font-smoothing: antialiased;
}
</style>
