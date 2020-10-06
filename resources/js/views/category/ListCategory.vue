<template>
    <v-container>
        <v-row>
            <v-col
                class="py-0"
                cols="12"
            >
                <new-category v-if="showNewModal"/>
                <edit-category v-if="showEditModal"/>
                <app-data-table
                    :title="$vuetify.lang.t('$vuetify.menu.category_list')"
                    :columns="getUserTableColumns"
                    :rows="categories"
                    :is-loading="isTableLoading"
                    sort-options="firstName"
                    show-avatar
                    @create-row="toogleNewModal(true)"
                    @edit-row="editCategoryHandler($event)"
                    @delete-row="deleteCategoryHandler($event)"
                />
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import {mapActions, mapState} from 'vuex'
import NewCategory from "./NewCategory";
import EditCategory from "./EditCategory";

export default {
    name: 'LisCategory',
    components: {
        NewCategory,
        EditCategory
    },
    data() {
        return {
            search: ''
        }
    },
    computed: {
        ...mapState('category', [
            'showNewModal',
            'showEditModal',
            'showShowModal',
            'categories',
            'isTableLoading'
        ]),
        getUserTableColumns() {
            return [
                {
                    text: this.$vuetify.lang.t('$vuetify.firstName'),
                    value: 'name'
                },
                {
                    text: this.$vuetify.lang.t('$vuetify.actions.actions'),
                    value: 'actions',
                    sortable: false
                }
            ]
        }
    },
    created() {
        this.getCategories()
    },
    methods: {
        ...mapActions('category', [
            'toogleNewModal',
            'openEditModal',
            'openShowModal',
            'getCategories',
            'deleteCategory'
        ]),
        editCategoryHandler($event) {
            this.openEditModal($event)
        },
        deleteCategoryHandler(categoryId) {
            this.$Swal
                .fire({
                    title: this.$vuetify.lang.t('$vuetify.titles.delete', [
                        this.$vuetify.lang.t('$vuetify.menu.category')
                    ]),
                    text: this.$vuetify.lang.t(
                        '$vuetify.messages.warning_delete'
                    ),
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
                    if (result.value) this.deleteCategory(categoryId)
                })
        }
    }
}
</script>

<style scoped></style>
