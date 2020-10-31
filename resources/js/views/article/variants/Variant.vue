<template>
    <v-data-table
        :headers="headers"
        :items="variantsValues"
        sort-by="calories"
        class="elevation-1"
    >
        <template v-slot:top>
            <v-toolbar flat>
                <v-chip
                    v-for="tag in variants"
                    :key="tag.name"
                    close
                    close-icon="mdi-delete"
                    color="success"
                    text-color="white"
                    @click:close="removeChips(tag)"
                >
                    <v-icon
                        left
                        @click="editChips(tag)"
                    >
                        mdi-pencil
                    </v-icon>
                    {{ tag.name }}
                </v-chip>
                <v-spacer/>
                <v-dialog
                    v-model="dialog"
                    max-width="500px"
                >
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn
                            color="primary"
                            dark
                            class="mb-2"
                            v-bind="attrs"
                            v-on="on"
                        >
                            {{ $vuetify.lang.t('$vuetify.actions.newF') }}
                        </v-btn>
                    </template>
                    <v-card>
                        <v-card-title>
                            <span class="headline">{{ formTitle }}</span>
                        </v-card-title>
                        <v-card-text>
                            <v-container>
                                <v-row>
                                    <v-col
                                        cols="12"
                                        sm="6"
                                        md="4"
                                    >
                                        <v-text-field
                                            v-model="editedItem.name"
                                            style="margin-top: 14px"
                                            :label="$vuetify.lang.t('$vuetify.variants.name')"
                                        />
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        sm="6"
                                        md="8"
                                    >
                                        <v-combobox
                                            v-model="select"
                                            multiple
                                            :label="$vuetify.lang.t('$vuetify.variants.options')"
                                            chips
                                            deletable-chips
                                            class="tag-input"
                                            @paste="updateTags"
                                        />
                                    </v-col>
                                </v-row>
                            </v-container>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer/>
                            <v-btn
                                class="mb-2"
                                @click="close"
                            >
                                <v-icon>mdi-close</v-icon>
                                {{ $vuetify.lang.t('$vuetify.actions.cancel') }}
                            </v-btn>
                            <v-btn
                                class="mb-2"
                                color="primary"
                                @click="save"
                            >
                                <v-icon>mdi-check</v-icon>
                                {{ $vuetify.lang.t('$vuetify.actions.accept') }}
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-toolbar>
        </template>
        <template v-slot:item.price="{ item }">
            <v-edit-dialog
                :return-value.sync="item.price"
                large
                persistent
                :cancel-text="$vuetify.lang.t('$vuetify.actions.cancel')"
                :save-text="$vuetify.lang.t('$vuetify.actions.edit')"
                @save="saveModalEdit"
                @cancel="cancelModalEdit"
                @open="openModalEdit"
                @close="closeModalEdit"
            >
                <div>{{ `${user.company.currency + ' ' + item.price}` }}</div>
                <template v-slot:input>
                    <div class="mt-4 title">
                        {{ $vuetify.lang.t('$vuetify.actions.edit') }}
                    </div>
                </template>
                <template v-slot:input>
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
              empty: 0.00,
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
                :save-text="$vuetify.lang.t('$vuetify.actions.edit')"
                @save="saveModalEdit"
                @cancel="cancelModalEdit"
                @open="openModalEdit"
                @close="closeModalEdit"
            >
                <div>{{ `${user.company.currency + ' ' + item.cost}` }}</div>
                <template v-slot:input>
                    <div class="mt-4 title">
                        {{ $vuetify.lang.t('$vuetify.actions.edit') }}
                    </div>
                </template>
                <template v-slot:input>
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
              empty: 0.00,
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
                :save-text="$vuetify.lang.t('$vuetify.actions.edit')"
                @save="saveModalEdit"
                @cancel="cancelModalEdit"
                @open="openModalEdit"
                @close="closeModalEdit"
            >
                <div>{{ item.ref }}</div>
                <template v-slot:input>
                    <div class="mt-4 title">
                        {{ $vuetify.lang.t('$vuetify.actions.edit') }}
                    </div>
                </template>
            </v-edit-dialog>
        </template>
        <template v-slot:item.barCode="item">
            <v-edit-dialog
                :return-value.sync="item.barCode"
                large
                persistent
                :cancel-text="$vuetify.lang.t('$vuetify.actions.cancel')"
                :save-text="$vuetify.lang.t('$vuetify.actions.edit')"
                @save="saveModalEdit"
                @cancel="cancelModalEdit"
                @open="openModalEdit"
                @close="closeModalEdit"
            >
                <div>{{ item.barCode }}</div>
                <template v-slot:input>
                    <div class="mt-4 title">
                        {{ $vuetify.lang.t('$vuetify.actions.edit') }}
                    </div>
                </template>
                <template v-slot:input>
                    <v-text-field-simplemask
                        v-model="item.barCode"
                        :label="$vuetify.lang.t('$vuetify.barCode')"
                        :properties="{
              clearable: true,
              singleLine:true,
              counter:true,
              autofocus:true,
            }"
                        :options="{
              inputMask: '##-####-####-###',
              outputMask: '##-####-####-###',
              empty: 0,
              alphanumeric: true,
            }"
                    />
                </template>
            </v-edit-dialog>
        </template>
        <template v-slot:item.actions="{ item }">
            <v-icon
                small
                @click="deleteItem(item)"
            >
                mdi-delete
            </v-icon>
        </template>
    </v-data-table>
</template>

<script>
/* eslint-disable vue/require-default-prop */
import {mapGetters} from 'vuex'

export default {
    name: 'Variant',
    props: {
        isUpdated: {
            type: Boolean,
            default: true
        },
        variantsParent: {
            type: Array
        },
        variantsValuesParent: {
            type: Array
        },
        refParent: {
        }
    },
    data() {
        return {
            ref: '10001',
            updated: true,
            variants: [],
            variantsValues: [],
            dialog: false,
            headers: [],
            editedIndex: -1,
            editedItem: {
                name: '',
                valueVariant: ''
            },
            defaultItem: {
                name: '',
                valueVariant: ''
            },
            select: [],
            items: [],
            search: '' // sync search
        }
    },
    computed: {
        ...mapGetters('auth', ['user']),
        formTitle() {
            return this.editedIndex === -1
                ? this.$vuetify.lang.t('$vuetify.titles.newF', [
                    this.$vuetify.lang.t('$vuetify.variants.variant')
                ])
                : this.$vuetify.lang.t('$vuetify.titles.edit', [
                    this.$vuetify.lang.t('$vuetify.variants.variant')
                ])
        }
    },
    watch: {
        isUpdated: val => {
            this.updated = val
            if (this.updated === false) {
                this.$emit('updateVariants', this.variants, this.variantsValues)
            }
        },
        variantsValuesParent() {
            this.variantsValues = this.variantsValuesParent
        },
        refParent() {
            this.ref = this.refParent
        },
        variantsParent() {
            this.variants = this.variantsParent
        },
        dialog(val) {
            val || this.close()
        }
    },
    created() {
        this.ref = parseFloat(this.refParent) + 1
        this.initialize()
    },
    mounted() {
        this.variantsValues = this.variantsValuesParent
        this.variants = this.variantsParent
    },
    methods: {
        initialize() {
            this.headers = [
                {
                    text: this.$vuetify.lang.t('$vuetify.variants.name'),
                    value: 'name'
                },
                {
                    text: this.$vuetify.lang.t('$vuetify.variants.price'),
                    value: 'price'
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
        },
        editChips(tag) {
            this.editedItem = tag
            this.editedIndex = this.variants.indexOf(tag)
            this.select = tag.value
            this.dialog = true
        },
        removeChips(tag) {
            this.variants.splice(tag, 1)
            this.myComb()
        },
        updateTags() {
            this.$nextTick(() => {
                this.select.push(...this.search.split(','))
                this.$nextTick(() => {
                    this.search = ''
                })
            })
        },
        deleteItem(item) {
            this.editedIndex = this.variantsValues.indexOf(item)
            this.editedItem = Object.assign({}, item)
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
                .then((result) => {
                    if (result.value) {
                        this.deleteItemConfirm()
                    }
                })
        },
        deleteItemConfirm() {
            if (this.variants.length === 1 && this.variants[0].value.length === 1) {
                this.removeChips(this.variants[0])
            } else {
                this.variantsValues.splice(this.editedIndex, 1)
            }
            this.updateVariants()
        },
        close() {
            this.dialog = false
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem)
                this.editedIndex = -1
            })
        },
        saveModalEdit() {
            this.updateVariants()
        },
        openModalEdit() {
            this.snack = true
            this.snackColor = 'info'
            this.snackText = 'Dialog opened'
        },
        cancelModalEdit() {
            this.snack = true
            this.snackColor = 'error'
            this.snackText = 'Canceled'
        },
        closeModalEdit() {
            console.log('Dialog closed')
        },
        save() {
            if (this.editedIndex > -1) {
                Object.assign(this.variants[this.editedIndex], {
                    name: this.editedItem.name,
                    value: this.select
                })
                this.select = []
            } else {
                this.variants.push({
                    name: this.editedItem.name,
                    value: this.select
                })
                this.select = []
            }
            this.myComb()
            this.close()
        },
        myComb() {
            const data = this.variants
            let result = []
            let localResult = []
            data.forEach((value, index) => {
                if (index === 0) {
                    value.value.forEach(localValue => {
                        let localArticle = this.variantsValues.filter(sh => sh.name === localValue)
                        if (localArticle.length === 0)
                            this.ref = parseInt(this.ref) + 1
                        result.push({
                            articles_shop: localArticle.length > 0 ? localArticle[0].articles_shop : [],
                            name: localValue.toString(),
                            price: localArticle.length > 0 ? localArticle[0].price : '0.00',
                            cost: localArticle.length > 0 ? localArticle[0].cost : '0.00',
                            ref: localArticle.length > 0 ? localArticle[0].ref : this.ref,
                            barCode: localArticle.length > 0 ? localArticle[0].barCode : this.barCode
                        })
                        if(localArticle.length > 0)
                            result[result.length-1].id =localArticle[0].id;
                    })
                } else {
                    value.value.forEach(localValue => {
                        localResult.forEach(v => {
                            let localArticle = this.variantsValues.filter(sh => sh.name === localValue.toString() + '/' + v.name.toString() ||
                                sh.name === v.name.toString() + '/' + localValue.toString())
                            if (localArticle.length === 0)
                                this.ref = parseInt(this.ref) + 1
                            result.push({
                                articles_shop: localArticle.length > 0 ? localArticle[0].articles_shop : [],
                                name: localArticle.length > 0 ? localArticle[0].name : localValue.toString() + '/' + v.name.toString(),
                                price: localArticle.length > 0 ? localArticle[0].price : '0.00',
                                cost: localArticle.length > 0 ? localArticle[0].cost : '0.00',
                                ref: localArticle.length > 0 ? localArticle[0].ref : this.ref,
                                barCode: localArticle.length > 0 ? localArticle[0].barCode : this.barCode
                            })
                            if(localArticle.length > 0)
                                result[result.length-1].id =localArticle[0].id;
                        })
                    })
                }
                localResult = result
                index + 1 !== data.length ? (result = []) : ''
            })
            this.variantsValues = result
            this.updateVariants()
        },
        updateVariants() {
            this.$emit('updateVariants', this.variants, this.variantsValues)
        }
    }
}
</script>

<style scoped>
.tag-input span.chip {
    background-color: #1976d2;
    color: #fff;
    font-size: 1em;
}

.tag-input span.v-chip {
    background-color: #1976d2;
    color: #fff;
    font-size: 1em;
    padding-left: 7px;
}

.tag-input span.v-chip::before {
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
    -webkit-font-feature-settings: "liga";
    -webkit-font-smoothing: antialiased;
}
</style>
