<template>
    <div class="page-add-product">
        <v-container>
            <v-card>
                <v-card-title>
          <span class="headline">{{
                  $vuetify.lang.t('$vuetify.titles.new', [
                      $vuetify.lang.t('$vuetify.articles.name'),
                  ])
              }}</span>
                </v-card-title>
                <v-card-text>
                    <v-form v-if="validShow" ref="form" v-model="formValid"
                            style="padding: 0" lazy-validation>
                        <v-expansion-panels v-model="panel" style="margin: 0"
                                            multiple>
                            <v-expansion-panel style="margin: 0">
                                <v-expansion-panel-header><h3>{{
                                        $vuetify.lang.t('$vuetify.panel.basic')
                                    }}</h3>
                                </v-expansion-panel-header>
                                <v-expansion-panel-content>
                                    <v-row>
                                        <v-col
                                            cols="12"
                                            md="4"
                                        >
                                            <v-text-field
                                                v-model="newArticle.name"
                                                :label="$vuetify.lang.t('$vuetify.firstName')"
                                                :rules="formRule.firstName"
                                                required
                                            />
                                        </v-col>
                                        <v-col
                                            cols="12"
                                            md="4"
                                        >
                                            <v-text-field
                                                v-model="newArticle.barCode"
                                                :label="$vuetify.lang.t('$vuetify.barCode')"
                                                required
                                            />
                                        </v-col>
                                        <v-col
                                            cols="12"
                                            md="4"
                                        >
                                            <v-text-field
                                                v-model="newArticle.price"
                                                :label="$vuetify.lang.t('$vuetify.price')"
                                                autocomplete="off"
                                                required
                                            />
                                        </v-col>
                                        <v-col
                                            cols="12"
                                            md="4"
                                        >
                                            <v-text-field
                                                v-model="newArticle.cost"
                                                :label="$vuetify.lang.t('$vuetify.articles.cost')"
                                                autocomplete="off"
                                                required
                                            />
                                        </v-col>
                                        <v-col
                                            cols="12"
                                            md="4"
                                        >
                                            <v-select
                                                v-model="newArticle.category"
                                                :items="categories"
                                                :label="$vuetify.lang.t('$vuetify.menu.category')"
                                                item-text="name"
                                                :loading="isShopLoading"
                                                :disabled="!!isShopLoading"
                                                return-object
                                            />
                                        </v-col>
                                    </v-row>
                                </v-expansion-panel-content>
                            </v-expansion-panel>
                            <v-expansion-panel>
                                <v-expansion-panel-header>
                                    <h3>{{ $vuetify.lang.t('$vuetify.articles.inventory') }}</h3>
                                </v-expansion-panel-header>
                                <v-expansion-panel-content>
                                    <v-row>
                                        <v-col cols="12" md="4" :disabled="articles.length === 0" style="margin: 0">
                                            <v-checkbox v-model="newArticle.composite"
                                                        :title="$vuetify.lang.t('$vuetify.articles.composite_text')"
                                                        :label="$vuetify.lang.t('$vuetify.articles.composite')"
                                                        @change="changeComposite"></v-checkbox>
                                        </v-col>
                                        <v-col>
                                            <v-tooltip right>
                                                <template v-slot:activator="{ on, attrs }">
                                                    <v-icon color="primary" dark v-bind="attrs"
                                                            v-on="on">
                                                        mdi-information-outline
                                                    </v-icon>
                                                </template>
                                                <span>{{ $vuetify.lang.t('$vuetify.articles.composite_text') }}</span>
                                            </v-tooltip>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col cols="12" md="12" style="margin: 0">
                                            <v-checkbox style="margin: 0; padding:0"
                                                        v-model="newArticle.track_inventory"
                                                        class="md-6"
                                                        :label="$vuetify.lang.t('$vuetify.articles.track_inventory')"
                                            />
                                        </v-col>
                                    </v-row>
                                    <v-row v-show="newArticle.composite">
                                        <v-col cols="12" md="12">
                                            <v-select
                                                :items="articles"
                                                :label="$vuetify.lang.t('$vuetify.rule.select')"
                                                item-text="name"
                                                :loading="isShopLoading"
                                                :disabled="!!isShopLoading"
                                                return-object
                                            />
                                        </v-col>
                                    </v-row>
                                </v-expansion-panel-content>
                            </v-expansion-panel>
                            <v-expansion-panel v-show="!newArticle.composite">
                                <v-expansion-panel-header><h3>{{
                                        $vuetify.lang.t('$vuetify.panel.variant')
                                    }}</h3>
                                </v-expansion-panel-header>
                                <v-expansion-panel-content>
                                    <v-row>
                                        <v-col
                                            cols="12"
                                            md="12"
                                        >
                                            <v-row>
                                                <v-col
                                                    cols="12"
                                                    md="12"
                                                >
                                                    <variant @updateVariants="updateVariant" :updated="updated"/>
                                                </v-col>
                                            </v-row>
                                        </v-col>
                                    </v-row>
                                </v-expansion-panel-content>
                            </v-expansion-panel>
                            <v-expansion-panel>
                                <v-expansion-panel-header><h3>{{
                                        $vuetify.lang.t('$vuetify.menu.shop')
                                    }}</h3>
                                </v-expansion-panel-header>
                                <v-expansion-panel-content>
                                    <v-row>
                                        <v-col
                                            cols="12"
                                            md="12"
                                        >
                                            <v-row>
                                                <v-col
                                                    cols="12"
                                                    md="12"
                                                >
                                                    <shops-articles
                                                        :shop-data="shopData"
                                                        :variants-data="variantData"
                                                    />
                                                </v-col>
                                            </v-row>
                                        </v-col>
                                    </v-row>
                                </v-expansion-panel-content>
                            </v-expansion-panel>
                        </v-expansion-panels>
                    </v-form>
                    <div v-if="!validShow">
                        <div v-if="shops.length === 0">
                            <p>
                                {{
                                    this.$vuetify.lang.t(
                                        '$vuetify.messages.warning_create', [
                                            this.$vuetify.lang.t('$vuetify.menu.shop')
                                        ]
                                    )
                                }}
                            </p>
                        </div>
                    </div>
                </v-card-text>
                <v-card-actions>
                    <v-spacer/>
                    <v-btn
                        class="mb-2"
                        color="error"
                        @click="$router.push({name:'product_list'})"
                    >
                        <v-icon>mdi-close</v-icon>
                        {{ $vuetify.lang.t('$vuetify.actions.cancel') }}
                    </v-btn>
                    <v-btn
                        class="mb-2"
                        color="primary"
                    >
                        <v-icon>mdi-check</v-icon>
                        {{ $vuetify.lang.t('$vuetify.actions.save') }}
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-container>
    </div>
</template>

<script>
import ShopsArticles from "./shop/ShopsArticles";
import Variant from "./variants/Variant";
import {mapActions, mapState} from "vuex";

export default {
    name: 'NewArticlePage',
    components: {ShopsArticles, Variant},
    data() {
        return {
            variants: [
                {
                    name: '',
                    price: '',
                    cost: '',
                    ref: '',
                    barCode: ''
                }
            ],
            panel: [0, 1, 2],
            formValid: false,
            formRule: {
                firstName: [
                    (v) =>
                        !!v ||
                        this.$vuetify.lang.t('$vuetify.rule.required', [
                            this.$vuetify.lang.t('$vuetify.name')
                        ])
                ]
            },
            shopData: [],
            variantData: [],
            updated: true
        }
    },
    computed: {
        ...mapState('article', ['saved', 'newArticle', 'articles']),
        ...mapState('category', ['categories', 'isActionInProgress']),
        ...mapState('shop', ['shops', 'isShopLoading']),
        validShow() {
            return (this.shops.length > 0)
        },
    },
    mounted() {
        this.getCategories()
        this.getShops().then(() => {
            this.shops.forEach((shop) => {
                this.shopData.push({
                    shop_id: shop.id,
                    name: shop.name,
                    checked: true,
                    variant: '',
                    price: '0.00',
                    stock: '',
                    under_inventory: ''
                })
            })
        })
        this.getArticles()

    },
    created() {
        this.formValid = false
        this.getCategories()
        this.getShops()
    },
    methods: {
        ...mapActions('article', ['createArticle', 'toogleNewModal', 'getArticles']),
        ...mapActions('category', ['getCategories']),
        ...mapActions('shop', ['getShops']),
        changeComposite() {
            if (this.newArticle.composite) {
                this.variantData = []
            } else {
                this.updated = false
            }
        },
        updateVariant(variants, dataUpdated) {
            this.variantData = dataUpdated
            this.variants = variants
            this.shopData = []
            this.shops.forEach((shop) => {
                console.log(variants.length)
                if (variants.length > 0) {
                    this.variantData.forEach((v) => {
                        this.shopData.push({
                            shop_id: shop.id,
                            name: shop.name,
                            checked: true,
                            variant: v.name,
                            price: '0.00',
                            stock: '',
                            under_inventory: ''
                        })
                    })
                } else {
                    this.shopData.push({
                        shop_id: shop.id,
                        name: shop.name,
                        checked: true,
                        variant: '',
                        price: '0.00',
                        stock: '',
                        under_inventory: ''
                    })
                }
            })
            this.updated = true
        },
        numbers(event) {
            const regex = new RegExp('^[0-9]+$')
            const key = String.fromCharCode(
                !event.charCode ? event.which : event.charCode
            )
            if (!regex.test(key)) {
                event.preventDefault()
                return false
            }
        },
        lettersNumbers(event) {
            const regex = new RegExp('^[a-zA-Z0-9 ]+$')
            const key = String.fromCharCode(
                !event.charCode ? event.which : event.charCode
            )
            if (!regex.test(key)) {
                event.preventDefault()
                return false
            }
        },
        async createNewArticle() {
            if (this.validShow) {
                if (this.$refs.form.validate()) {
                    this.loading = true
                    await this.createArticle(this.newArticle).then(() => {
                        if (this.saved) {
                            this.loading = false
                            const msg = this.$vuetify.lang.t(
                                '$vuetify.messages.success_profile'
                            )
                            this.$Toast.fire({
                                icon: 'success',
                                title: msg
                            })
                        }
                    })
                }
            }
        }
    }
}
</script>

<style scoped>

</style>
