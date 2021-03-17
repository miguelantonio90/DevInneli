<template>
  <v-container>
    <v-row>
      <v-col
        class="py-0"
        cols="12"
      >
        <new-box v-if="showNewModal" />
        <edit-box v-if="showEditModal" />
        <open-close-box v-if="opencloseBox" />
        <v-card>
          <v-card-title>
            <v-list-item two-line>
              <v-list-item>
                <v-list-item-title>
                  {{ $vuetify.lang.t('$vuetify.menu.boxes_list') }}
                </v-list-item-title>
                <v-list-item-icon>
                  <v-btn
                    class="mb-2"
                    color="primary"
                    @click="toogleNewModal(true)"
                  >
                    <v-icon>mdi-plus</v-icon>
                    {{ $vuetify.lang.t('$vuetify.actions.new') }}
                  </v-btn>
                </v-list-item-icon>
              </v-list-item>
            </v-list-item>
            <v-card-text>
              <v-row>
                <v-col
                  v-for="(shop,i) in boxes"
                  :key="i"
                  cols="12"
                  md="6"
                >
                  <v-card>
                    <v-card-actions>
                      <v-list-item two-line>
                        <v-list-item>
                          <v-list-item-title>
                            {{ $vuetify.lang.t('$vuetify.menu.shop') }}: {{ shop['shop']['name'] }}
                          </v-list-item-title>
                        </v-list-item>
                      </v-list-item>
                    </v-card-actions>
                    <v-divider />
                    <v-list class="transparent">
                      <app-data-table
                        csv-filename="Boxes"
                        :headers="getTableColumns"
                        :view-new-button="false"
                        :view-tour-button="false"
                        :view-show-filter="false"
                        :view-show-button="true"
                        :is-loading="isTableLoading"
                        :items="shop['boxes']"
                        :manager="'boxes'"
                        :sort-by="['name']"
                        :sort-desc="[false, true]"
                        multi-sort
                        @create-row="toogleNewModal(true)"
                        @edit-row="editBoxesHandler($event)"
                        @show-row="showBoxOperation($event)"
                        @delete-row="deleteBoxesHandler($event)"
                      >
                        <template v-slot:[`item.state`]="{ item }">
                          <template v-if="!item.digital">
                            <v-tooltip top>
                              <template v-slot:activator="{ on, attrs }">
                                <v-icon
                                  v-if="item.state === 'open'"
                                  class="mr-2"
                                  small
                                  color="primary"
                                  v-bind="attrs"
                                  v-on="on"
                                  @click="openBox(item.id)"
                                >
                                  mdi-lock
                                </v-icon>
                              </template>
                              <span>{{ $vuetify.lang.t('$vuetify.access.access.boxes_close') }}</span>
                            </v-tooltip>
                            <v-tooltip top>
                              <template v-slot:activator="{ on, attrs }">
                                <v-icon
                                  v-if="item.state !== 'open'"
                                  class="mr-2"
                                  color="primary"
                                  small
                                  v-bind="attrs"
                                  v-on="on"
                                  @click="openBox(item.id)"
                                >
                                  mdi-lock-open
                                </v-icon>
                              </template>
                              <span>{{ $vuetify.lang.t('$vuetify.access.access.boxes_open') }}</span>
                            </v-tooltip>
                          </template>
                          {{ $vuetify.lang.t('$vuetify.sale.state.' + item.state) }}
                        </template>
                      </app-data-table>
                    </v-list>
                  </v-card>
                </v-col>
              </v-row>
            </v-card-text>
          </v-card-title>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapActions, mapState } from 'vuex'
import NewBox from './NewBox'
import EditBox from './EditBox'
import OpenCloseBox from './OpenCloseBox'

export default {
  name: 'ListBoxes',
  components: {
    NewBox,
    EditBox,
    OpenCloseBox
  },
  data () {
    return {
      labels: ['SU', 'MO', 'TU', 'WED', 'TH', 'FR', 'SA'],
      time: 0,
      forecast: [
        { day: 'Tuesday', icon: 'mdi-white-balance-sunny', temp: '24\xB0/12\xB0' },
        { day: 'Wednesday', icon: 'mdi-white-balance-sunny', temp: '22\xB0/14\xB0' },
        { day: 'Thursday', icon: 'mdi-cloud', temp: '25\xB0/15\xB0' }
      ],
      search: ''
    }
  },
  computed: {
    ...mapState('boxes', [
      'opencloseBox',
      'showNewModal',
      'showEditModal',
      'showShowModal',
      'boxes',
      'isTableLoading'
    ]),
    getTableColumns () {
      return [
        {
          text: this.$vuetify.lang.t('$vuetify.firstName'),
          value: 'name',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.state'),
          value: 'state'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.actions.actions'),
          value: 'actions',
          sortable: false
        }
      ]
    }
  },
  async created () {
    await this.getBoxes()
  },
  methods: {
    ...mapActions('boxes', [
      'toogleNewModal',
      'openEditModal',
      'openCloseModal',
      'getBoxes',
      'deleteBox'
    ]),
    isDigital ($event) {
      let digital = false
      Object.values(this.boxes).forEach((shop) => {
        if (shop.boxes.filter(bx => bx.id === $event).length > 0) { digital = shop.boxes.filter(bx => bx.id === $event)[0].digital }
      })
      console.log(digital)
      return digital
    },
    editBoxesHandler ($event) {
      !this.isDigital($event) ? this.openEditModal($event)
        : this.showMessage(
          this.$vuetify.lang.t('$vuetify.titles.edit', [
            this.$vuetify.lang.t('$vuetify.menu.box')
          ])
        )
    },
    showBoxOperation ($event) {

    },
    openBox ($event) {
      this.openCloseModal($event)
    },
    showMessage (title) {
      this.$Swal.fire({
        title: title,
        text: this.$vuetify.lang.t(
          '$vuetify.messages.warning_digital_box'
        ),
        icon: 'info',
        showCancelButton: false,
        confirmButtonText: this.$vuetify.lang.t(
          '$vuetify.actions.accept'
        ),
        confirmButtonColor: 'red'
      })
    },
    deleteBoxesHandler ($event) {
      this.isDigital($event)
        ? this.showMessage(
          this.$vuetify.lang.t('$vuetify.titles.delete', [
            this.$vuetify.lang.t('$vuetify.menu.box')
          ]))
        : this.$Swal
          .fire({
            title: this.$vuetify.lang.t('$vuetify.titles.delete', [
              this.$vuetify.lang.t('$vuetify.menu.box')
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
          .then(result => {
            if (result.value) this.deleteBox($event)
          })
    }
  }
}
</script>

<style scoped></style>
