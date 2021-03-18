<template>
  <v-container>
    <new-bank v-if="showNewModal" />
    <edit-bank v-if="showEditModal" />
    <v-card>
      <v-card-title>
        <v-list-item two-line>
          <v-list-item>
            <v-list-item-title>
              {{ $vuetify.lang.t('$vuetify.menu.bank_list') }}
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
      </v-card-title>
      <template v-if="banks.length === 0">
        <v-card-subtitle>
          <p>{{ $vuetify.lang.t('$vuetify.messages.empty_elements') }}</p>
        </v-card-subtitle>
      </template>
      <template v-else>
        <v-row>
          <v-col
            v-for="bank in banks"
            :key="bank.id"
            style="margin-bottom: 20px"
            class="py-0"
            cols="12"
            md="6"
          >
            <v-card
              class="mx-auto"
              :color="bank.color? bank.color :'#26c6da'"
              dark
              max-width="400"
            >
              <v-card-title>
                <v-list-item two-line>
                  <v-list-item-title class="headline font-weight-bold">
                    <span class="title font-weight-light">{{ bank.name }}</span>
                  </v-list-item-title>

                  <v-list-item-icon v-if="!bank.default_bank">
                    <v-spacer />
                    <v-tooltip top>
                      <template v-slot:activator="{ on, attrs }">
                        <v-icon
                          class="mr-2"
                          small
                          color="withe"
                          v-bind="attrs"
                          v-on="on"
                          @click="editBankHandler(bank.id)"
                        >
                          mdi-pencil
                        </v-icon>
                      </template>
                      <span>{{ $vuetify.lang.t('$vuetify.actions.edit') }}</span>
                    </v-tooltip>
                    <v-tooltip top>
                      <template v-slot:activator="{ on, attrs }">
                        <v-icon
                          class="mr-2"
                          color="withe"
                          small
                          v-bind="attrs"
                          v-on="on"
                          @click="deleteBankHandler(bank.id)"
                        >
                          mdi-delete
                        </v-icon>
                      </template>
                      <span>{{ $vuetify.lang.t('$vuetify.actions.delete') }}</span>
                    </v-tooltip>
                  </v-list-item-icon>
                </v-list-item>
              </v-card-title>
              <v-card-subtitle>
                <p>
                  {{ $vuetify.lang.t('$vuetify.bank.count_number') }}: {{ bank.count_number }} <br>
                  {{ $vuetify.lang.t('$vuetify.date') }}: {{ formatDate(bank.date) }}<br>
                  {{ $vuetify.lang.t('$vuetify.boxes.init') }}: {{ bank.currency_id ? bank.currency.currency : user.company.currency }} {{ bank.init_balance }}
                </p>
                <b>{{ $vuetify.lang.t('$vuetify.bank.type_operation') }}:</b> <br>
                <v-row>
                  <v-col
                    v-for="(paymentBank,i) in bank.payments_banks"
                    :key="i"
                    cols="12"
                    md="6"
                  >
                    <p>
                      <v-icon small>
                        mdi-check
                      </v-icon> {{ $vuetify.lang.t('$vuetify.payment.' + paymentBank.payment.method) }}
                    </p>
                  </v-col>
                </v-row>

                <v-list-item class="grow">
                  <v-row
                    align="center"
                    justify="end"
                  >
                    <v-tooltip top>
                      <template v-slot:activator="{ on, attrs }">
                        <v-icon
                          class="mr-2"
                          small
                          color="withe"
                          v-bind="attrs"
                          v-on="on"
                          @click="showBankHandler(bank.id)"
                        >
                          mdi-eye
                        </v-icon>
                      </template>
                      <span>{{ $vuetify.lang.t('$vuetify.actions.eye') }}</span>
                    </v-tooltip>
                    <span class="mr-1" />
                    <v-tooltip top>
                      <template v-slot:activator="{ on, attrs }">
                        <v-icon
                          class="mr-2"
                          small
                          color="withe"
                          v-bind="attrs"
                          v-on="on"
                          @click="editBankHandler(bank.id)"
                        >
                          mdi-chart-bar
                        </v-icon>
                      </template>
                    </v-tooltip>
                  </v-row>
                </v-list-item>
              </v-card-subtitle>
            </v-card>
          </v-col>
        </v-row>
      </template>
    </v-card>
  </v-container>
</template>
<script>
import { mapActions, mapGetters, mapState } from 'vuex'
import NewBank from './NewBank'
import EditBank from './EditBank'

export default {
  name: 'BankList',
  components: { NewBank, EditBank },
  computed: {
    ...mapState('bank', ['banks', 'showNewModal', 'showEditModal']),
    ...mapGetters('auth', ['user'])
  },
  created () {
    this.getBanks()
  },
  methods: {
    ...mapActions('bank', ['getBanks', 'toogleNewModal', 'openEditModal', 'deleteBank']),
    formatDate (date) {
      if (!date) return null
      const [year, month, day] = date.split('-')
      return `${day}/${month}/${year}`
    },
    newBankHandler () {

    },
    showBankHandler () {

    },
    editBankHandler (bankId) {
      this.openEditModal(bankId)
    },
    deleteBankHandler (bankId) {
      this.$Swal
        .fire({
          title: this.$vuetify.lang.t('$vuetify.titles.delete', [
            this.$vuetify.lang.t('$vuetify.menu.bank')
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
          if (result.value) this.deleteBank(bankId)
        })
    }
  }
}
</script>

<style scoped>

</style>
