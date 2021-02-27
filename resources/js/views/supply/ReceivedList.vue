<template>
  <app-data-table
    :view-new-button="false"
    :title="
      $vuetify.lang.t('$vuetify.titles.list', [
        $vuetify.lang.t('$vuetify.supply.received')
      ])
    "
    :headers="getTableColumns"
    csv-filename="SupplyProducts"
    :manager="'vending'"
    :items="localReceived"
    :options="vBindOption"
    :sort-by="['no_facture']"
    :sort-desc="[false, true]"
    multi-sort
    :is-loading="isTableLoading"
    @edit-row="$emit('editSupplyHandler', $event)"
    @delete-row="$emit('deleteSupplyHandler', $event)"
  >
    <template v-slot:[`item.state.name`]="{ item }">
      <template v-if="item.state.name !== 'cancelled'">
        <v-autocomplete
          v-model="item.state"
          chips
          item-text="name"
          :items="item.state.next"
          :style="{ width: '160px' }"
          auto-select-first
          return-object
          @input="$emit('changeStateHandler', item)"
        >
          <template v-slot:selection="data">
            <v-chip
              v-bind="data.attrs"
              :input-value="data.item.value"
              @click="data.select"
            >
              <i>
                {{
                  $vuetify.lang.t(
                    "$vuetify.supply_state.state." +
                      data.item.name
                  )
                }}</i>
            </v-chip>
          </template>
          <template v-slot:item="data">
            <template v-if="typeof data.item !== 'object'">
              <v-list-item-content
                v-text="
                  $vuetify.lang.t(
                    '$vuetify.supply_state.state.' +
                      data.item.name
                  )
                "
              />
            </template>
            <template v-else>
              <v-list-item-content>
                <v-list-item-title>
                  {{
                    $vuetify.lang.t(
                      "$vuetify.supply_state.state." +
                        data.item.name
                    )
                  }}
                </v-list-item-title>
              </v-list-item-content>
            </template>
          </template>
        </v-autocomplete>
      </template>
      <template v-else>
        <v-chip>
          {{
            $vuetify.lang.t(
              "$vuetify.supply_state.state." + item.state.name
            )
          }}
        </v-chip>
      </template>
    </template>
    <template v-slot:[`item.to`]="{ item }">
      {{ item.to.name }} <br>({{ item.to.email }})
    </template>
    <template v-slot:[`item.sale.shop.name`]="{ item }">
      {{ item.sale.shop.name }}
    </template>
    <template v-slot:[`item.sale.totalCost`]="{ item }">
      <template>
        <v-tooltip bottom>
          <template v-slot:activator="{ on, attrs }">
            <b><v-icon
              :style="
                item.totalRefund > 0
                  ? 'color: red'
                  : 'color:primary'
              "
              class="mr-2"
              small
              v-bind="attrs"
              v-on="on"
            >
              mdi-information
            </v-icon></b>
          </template>
          <template>
            <list-pay
              :show="false"
              :sale="item"
              :total-price="
                parseFloat(item.sale.totalCost)
                  .toFixed(2)
                  .toString()
              "
              :total-tax="
                parseFloat(item.sale.totalTax).toFixed(2)
              "
              :total-discount="
                parseFloat(item.sale.totalDisc).toFixed(2)
              "
              :sub-total="
                parseFloat(item.sale.subTotal).toFixed(2)
              "
              :currency="`${user.company.currency}`"
            />
          </template>
          <span v-if="item.totalRefund > 0">{{
            $vuetify.lang.t("$vuetify.menu.refund") +
              ": " +
              `${user.company.currency +
                " " +
                item.sale.totalRefund}`
          }}</span>
        </v-tooltip>
      </template>
      {{
        `${user.company.currency +
          " " +
          parseFloat(item.sale.totalCost).toFixed(2)}`
      }}
    </template>
    <template
      v-slot:[`item.data-table-expand`]="{ item, expand, isExpanded }"
    >
      <v-btn
        v-if="item.sale.articles.length > 0"
        color="primary"
        fab
        x-small
        dark
        @click="expand(!isExpanded)"
      >
        <v-icon v-if="isExpanded">
          mdi-chevron-up
        </v-icon>
        <v-icon v-else>
          mdi-chevron-down
        </v-icon>
      </v-btn>
    </template>
    <template v-slot:expanded-item="{ headers, item }">
      <td
        :colspan="headers.length"
        style="padding: 0 0 0 0"
      >
        <v-simple-table dense>
          <template v-slot:default>
            <thead>
              <tr>
                <th class="text-left">
                  {{
                    $vuetify.lang.t("$vuetify.articles.ref")
                  }}
                </th>
                <th class="text-left">
                  {{ $vuetify.lang.t("$vuetify.firstName") }}
                </th>
                <th class="text-left">
                  {{
                    $vuetify.lang.t(
                      "$vuetify.variants.cant"
                    )
                  }}
                </th>
                <th class="text-left">
                  {{
                    $vuetify.lang.t(
                      "$vuetify.articles.cost"
                    )
                  }}
                </th>
                <th class="text-left">
                  {{
                    $vuetify.lang.t(
                      "$vuetify.variants.total_cost"
                    )
                  }}
                </th>
                <th class="text-left">
                  {{
                    $vuetify.lang.t(
                      "$vuetify.articles.new_inventory"
                    )
                  }}
                </th>
                <th class="text-left">
                  {{
                    $vuetify.lang.t(
                      "$vuetify.actions.actions"
                    )
                  }}
                </th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="article in item.sale.articles"
                :key="article.id"
              >
                <td>
                  <template
                    v-if="article.refounds.length > 0"
                  >
                    <v-tooltip right>
                      <template
                        v-slot:activator="{ on, attrs }"
                      >
                        <b><v-icon
                          v-if="article.cant > 0"
                          style="color: red"
                          class="mr-2"
                          small
                          v-bind="attrs"
                          v-on="on"
                        >
                          mdi-information
                        </v-icon></b>
                      </template>
                      <template>
                        <detail-refund
                          :article="article"
                          :currency="
                            `${user.company.currency}`
                          "
                        />
                      </template>
                    </v-tooltip>
                  </template>
                  {{ article.ref }}
                </td>
                <td>{{ article.name }}</td>
                <td>
                  <v-tooltip top>
                    <template
                      v-slot:activator="{ on, attrs }"
                    >
                      <b><v-icon
                        v-if="
                          article.cantRefund > 0
                        "
                        style="color: red"
                        class="mr-2"
                        small
                        v-bind="attrs"
                        v-on="on"
                      >
                        mdi-information
                      </v-icon></b>
                    </template>
                    <span>{{
                      $vuetify.lang.t(
                        "$vuetify.menu.refund"
                      ) +
                        ": " +
                        article.cantRefund +
                        " " +
                        $vuetify.lang.t(
                          "$vuetify.menu.articles"
                        )
                    }}</span>
                  </v-tooltip>
                  {{ article.cant }}
                </td>
                <td>
                  {{
                    `${user.company.currency +
                      " " +
                      article.cost}`
                  }}
                </td>
                <td>
                  <template
                    v-if="
                      article.taxes.length > 0 ||
                        article.discount.length > 0
                    "
                  >
                    <v-tooltip top>
                      <template
                        v-slot:activator="{ on, attrs }"
                      >
                        <b><v-icon
                          :color="
                            article.moneyRefund >
                              0
                              ? 'red'
                              : 'primary'
                          "
                          class="mr-2"
                          small
                          v-bind="attrs"
                          v-on="on"
                        >
                          mdi-information
                        </v-icon></b>
                      </template>
                      <template>
                        <detail-article-cost
                          :article="article"
                          :currency="
                            user.company.currency
                          "
                        />
                        <span
                          v-if="
                            article.moneyRefund > 0
                          "
                        >{{
                          $vuetify.lang.t(
                            "$vuetify.menu.refund"
                          ) +
                            ": " +
                            `${user.company
                              .currency +
                              " " +
                              article.moneyRefund}`
                        }}</span>
                      </template>
                    </v-tooltip>
                  </template>
                  <span>{{
                    `${user.company.currency +
                      " " +
                      parseFloat(
                        article.totalCost
                      ).toFixed(2)}`
                  }}</span>
                </td>
                <td>
                  <template v-if="article.inventory > 0">
                    {{ article.inventory }}
                  </template>
                  <template v-else>
                    <i style="color: red">
                      <v-icon style="color: red">
                        mdi-arrow-down-bold-circle
                      </v-icon>
                      {{ article.inventory }}
                    </i>
                  </template>
                </td>
                <td>
                  <template>
                    <v-tooltip top>
                      <template
                        v-slot:activator="{ on, attrs }"
                      >
                        <b><v-icon
                          style="color: #ff752b"
                          class="mr-2"
                          small
                          v-bind="attrs"
                          v-on="on"
                          @click="
                            $emit(
                              'refundArticle',
                              item,
                              article
                            )
                          "
                        >
                          mdi-undo
                        </v-icon></b>
                      </template>
                      <span>{{
                        $vuetify.lang.t(
                          "$vuetify.actions.refund"
                        )
                      }}</span>
                    </v-tooltip>
                  </template>
                </td>
              </tr>
            </tbody>
          </template>
        </v-simple-table>
      </td>
    </template>
  </app-data-table>
</template>

<script>
import ListPay from '../pay/ListPay'
import DetailRefund from '../refund/DetailRefund'
import DetailArticleCost from './DetailArticleCost'
import { mapGetters } from 'vuex'
export default {
  name: 'ReceivedList',
  components: { ListPay, DetailRefund, DetailArticleCost },
  props: {
    getTableColumns: {
      type: Array,
      default: function () {
        return []
      }
    },
    articles: {
      type: Array,
      default: function () {
        return []
      }
    },
    received: {
      type: Array,
      default: function () {
        return []
      }
    },
    isTableLoading: {
      type: Boolean,
      default: false
    }
  },
  data () {
    return {
      localReceived: [],
      vBindOption: {
        itemKey: 'no_facture',
        singleExpand: false,
        showExpand: true
      }
    }
  },
  computed: {
    ...mapGetters('auth', ['user', 'access_permit'])
  },
  watch: {
    articles: function () {
      this.loadLocalReceived()
    }
  },
  created () {},
  methods: {
    loadLocalReceived () {
      if (this.articles.length > 0) {
        this.localReceived = []
        this.received.forEach(value => {
          const supply = value
          value.sale.articles.forEach((v, i) => {
            if (v.parent_id) {
              supply.sale.articles[i].name =
                                this.articles.filter(
                                  art => art.id === v.parent_id
                                )[0].name +
                                '(' +
                                v.name +
                                ')'
            }
          })
          this.localReceived.push(supply)
        })
      }
    }
  }
}
</script>

<style scoped></style>
