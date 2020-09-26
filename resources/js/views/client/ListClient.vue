<template>
    <v-container>
        <v-row>
            <v-col
                class="py-0"
                cols="12"
            >
                <new-client v-if="showNewModal"/>
                <edit-client v-if="showEditModal"/>
                <app-data-table
                    :title="$vuetify.lang.t('$vuetify.titles.list',
                                  [$vuetify.lang.t('$vuetify.menu.client'),])"
                    :columns="getUserTableColumns"
                    :rows="clients"
                    :is-loading="isTableLoading"
                    sort-options="firstName"
                    show-avatar
                    @create-row="toogleNewModal(true)"
                    @edit-row="editClientHandler($event)"
                    @delete-row="deleteClientHandler($event)"
                />
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import {mapActions, mapState} from 'vuex'
import EditClient from "../client/EditClient";
import NewClient from "../client/NewClient";

export default {
    components: {
        EditClient,
        NewClient
    },
    data() {
        return {
            search: ''
        }
    },
    computed: {
        ...mapState('client', [
            'showNewModal',
            'showEditModal',
            'showShowModal',
            'clients',
            'isTableLoading'
        ]),
        getUserTableColumns() {
            return [
                {
                    text: this.$vuetify.lang.t('$vuetify.firstName'),
                    value: 'firstName'
                },
                {
                    text: this.$vuetify.lang.t('$vuetify.lastName'),
                    value: 'lastName'
                },
                {
                    text: this.$vuetify.lang.t('$vuetify.email'),
                    value: 'email'
                },
                {
                    text: this.$vuetify.lang.t('$vuetify.country'),
                    value: 'country'
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
        this.getClients()
    },
    methods: {
        ...mapActions('client', [
            'toogleNewModal',
            'openEditModal',
            'openShowModal',
            'getClients',
            'deleteClient'
        ]),
        editClientHandler($event)
        {
            this.openEditModal($event)
        },
        deleteClientHandler(clientId) {
            this.$Swal
                .fire({
                    title: this.$vuetify.lang.t('$vuetify.titles.delete', [
                        this.$vuetify.lang.t('$vuetify.menu.client')
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
                    if (result.value) this.deleteClient(clientId)
                });

        }
    }
}
</script>

<style scoped></style>
