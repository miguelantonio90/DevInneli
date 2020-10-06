<template>
    <div>
        <v-data-table
            :headers="headers"
            :items="shopData"
        >
            <template v-slot:item.checked="{ item }">
                <v-simple-checkbox
                    v-model="item.checked"
                ></v-simple-checkbox>
            </template>
            <template v-slot:item.price="props">
                <v-edit-dialog
                    :return-value.sync="props.item.price"
                    large
                    persistent
                    @save="save"
                    @cancel="cancel"
                    @open="open"
                    @close="close"
                >
                    <div>{{ props.item.price }}</div>
                    <template v-slot:input>
                        <div class="mt-4 title">
                            Update Iron
                        </div>
                    </template>
                    <template v-slot:input>
                        <v-text-field
                            v-model="props.item.price"
                            :rules="[max25chars]"
                            label="Edit"
                            single-line
                            counter
                            autofocus
                        ></v-text-field>
                    </template>
                </v-edit-dialog>
            </template>
            <template v-slot:item.stock="props">
                <v-edit-dialog
                    :return-value.sync="props.item.stock"
                    large
                    persistent
                    @save="save"
                    @cancel="cancel"
                    @open="open"
                    @close="close"
                >
                    <div>{{ props.item.stock }}</div>
                    <template v-slot:input>
                        <div class="mt-4 title">
                            Update Iron
                        </div>
                    </template>
                    <template v-slot:input>
                        <v-text-field
                            v-model="props.item.stock"
                            :rules="[max25chars]"
                            label="Edit"
                            single-line
                            counter
                            autofocus
                        ></v-text-field>
                    </template>
                </v-edit-dialog>
            </template>
            <template v-slot:item.under_inventory="props">
                <v-edit-dialog
                    :return-value.sync="props.item.under_inventory"
                    large
                    persistent
                    @save="save"
                    @cancel="cancel"
                    @open="open"
                    @close="close"
                >
                    <div>{{ props.item.under_inventory }}</div>
                    <template v-slot:input>
                        <div class="mt-4 title">
                            Update Iron
                        </div>
                    </template>
                    <template v-slot:input>
                        <v-text-field
                            v-model="props.item.under_inventory"
                            :rules="[max25chars]"
                            label="Edit"
                            single-line
                            counter
                            autofocus
                        ></v-text-field>
                    </template>
                </v-edit-dialog>
            </template>
        </v-data-table>
        <v-snackbar
            v-model="snack"
            :timeout="3000"
            :color="snackColor"
        >
            {{ snackText }}

            <template v-slot:action="{ attrs }">
                <v-btn
                    v-bind="attrs"
                    text
                    @click="snack = false"
                >
                    Close
                </v-btn>
            </template>
        </v-snackbar>
    </div>
</template>
<script>
import {mapState} from "vuex";

export default {
    name: "ShopsArticles",
    props: ["shopData", "variantsData"],
    data() {
        return {
            snack: false,
            snackColor: '',
            snackText: '',
            max25chars: v => v.length <= 25 || 'Input too long!',
            pagination: {},
            headers: [],
        }
    },
    computed: {},
    watch() {
        if (this.variantsData.length > 0)
            this.initialize()
        else
            this.initialize()
    },
    created() {
        this.initialize()
    },
    mounted() {
    },
    methods: {
        initialize() {
            this.headers = [];
            this.headers = [
                {
                    text: this.$vuetify.lang.t('$vuetify.shop_article.enabled'),
                    value: 'checked'
                },
                {
                    text: this.$vuetify.lang.t('$vuetify.menu.shop'),
                    value: 'name'
                }
            ];
            if (this.variantsData.length > 0) {
                this.headers.push({
                    text: this.$vuetify.lang.t('$vuetify.variants.variant'),
                    value: 'variant'
                })
            }
            this.headers.push({
                text: this.$vuetify.lang.t('$vuetify.variants.price'),
                value: 'price'
            })
            if (this.variantsData.length > 0) {
                this.headers.push({
                    text: this.$vuetify.lang.t('$vuetify.shop_article.stock'),
                    value: 'stock'
                })
                this.headers.push({
                    text: this.$vuetify.lang.t('$vuetify.shop_article.under_inventory'),
                    value: 'under_inventory'
                })
            }
        },
        save() {
            this.snack = true
            this.snackColor = 'success'
            this.snackText = 'Data saved'
        },
        cancel() {
            this.snack = true
            this.snackColor = 'error'
            this.snackText = 'Canceled'
        },
        open() {
            this.snack = true
            this.snackColor = 'info'
            this.snackText = 'Dialog opened'
        },
        close() {
            console.log('Dialog closed')
        },
    },
}
</script>
<style scoped>

</style>
