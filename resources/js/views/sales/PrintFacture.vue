<template>
  <v-dialog
    v-model="toogleShowModal"
    max-width="90mm"
    persistent
  >
    <v-card>
      <v-card-text>
        <div
          id="ticket"
          class="ticket"
        >
          <img
            v-if="user.company.logo"
            style="margin-top: 15px"
            class="profile mx-auto d-block"
            :src="user.company.logo"
            alt="LOGO"
          >
          <p class="centrado">
            {{ $vuetify.lang.t('$vuetify.sale.ticket') }}<br>
            <b><i>{{ editSale.shop.name }}</i></b><br>
            <b>{{ $vuetify.lang.t('$vuetify.tax.noFacture') }}</b>:
            {{ editSale.no_facture }}<br>
            <b>{{ $vuetify.lang.t('$vuetify.articles.sell_by') + ': ' }}</b>
            {{ editSale.create.firstName }} {{ editSale.create.lastName? editSale.create.lastName: '' }}<br>
            <b>{{ $vuetify.lang.t('$vuetify.menu.box') + ': ' }}</b>
            {{ editSale.box.name }}<br>
            {{ new Date(editSale.updated_at).toUTCString() }}<br>
            {{ user.company.slogan ? user.company.slogan.toUpperCase(): '' }}
          </p>
          <div style="width: 100%">
            <v-col
              v-for="(art,i) in editSale.articles"
              :key="i"
              style="width: 100%"
              cols="12"
              md="12"
            >
              <table style="width: 100%">
                <thead>
                  <tr>
                    <th class="cantidad">
                      {{ $vuetify.lang.t('$vuetify.report.cant').toUpperCase() }}
                    </th>
                    <th class="producto">
                      {{ $vuetify.lang.t('$vuetify.menu.article').toUpperCase() }}
                    </th>
                    <th class="precio">
                      $$
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>{{ art.cant }}</td>
                    <td>{{ art.name }} {{ art.um? '('+$vuetify.lang.t('$vuetify.um.' + JSON.parse(art.um).name) + ')':'' }}</td>
                    <td>
                      {{
                        `${user.company.currency + ' ' + parseFloat(art.cant*art.price).toFixed(2)}`
                      }}
                    </td>
                  </tr>
                  <tr>
                    <td
                      colspan="3"
                      style="width: 100%"
                    >
                      <template v-if="art.discount.length >0">
                        <table
                          style="width: 100%"
                        >
                          <tbody>
                            <tr
                              v-for="(lDiscount, j) of art.discount"
                              :key="j"
                            >
                              <td>
                                {{ $vuetify.lang.t('$vuetify.menu.discount') }}
                              </td>
                              <td>
                                {{ lDiscount.name }}{{ lDiscount.percent ? '('+lDiscount.value +'%) ':' ' }}
                              </td>
                              <td>
                                <i> -{{ `${user.company.currency}` }}
                                  {{ lDiscount.percent ? parseFloat(lDiscount.value*art.cant*art.price/100).toFixed(2) : parseFloat(lDiscount.value).toFixed(2) }}
                                </i>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </template>
                    </td>
                  </tr>
                  <tr>
                    <td
                      colspan="3"
                      style="width: 100%"
                    >
                      <template v-if="art.taxes.length >0">
                        <table
                          style="width: 100%"
                        >
                          <tbody>
                            <tr
                              v-for="(tax, j) of art.taxes"
                              :key="j"
                            >
                              <td>
                                {{ $vuetify.lang.t('$vuetify.articles.tax') }}
                              </td>
                              <td>
                                {{ tax.name }}{{ tax.percent ? '('+tax.value +'%) ':' ' }}
                              </td>
                              <td>
                                <i> +{{ `${user.company.currency}` }}
                                  {{ tax.percent ? parseFloat(tax.value*art.cant*art.price/100).toFixed(2) : parseFloat(tax.value).toFixed(2) }}
                                </i>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </template>
                    </td>
                  </tr>
                  <tr>
                    <td />
                    <td>{{ $vuetify.lang.t('$vuetify.pay.total').toUpperCase() }}</td>
                    <td> <b>{{ `${user.company.currency + ' ' + parseFloat(total_pay(art)).toFixed(2)}` }}</b></td>
                  </tr>
                </tbody>
              </table>
            </v-col>
          </div>
          <b style="margin-top: 10px">{{ $vuetify.lang.t('$vuetify.report.breakdown').toUpperCase() }}</b>
          <table style="width: 100%">
            <tbody>
              <tr>
                <td><b>{{ $vuetify.lang.t('$vuetify.pay.sub_total') }}</b></td>
                <td style="text-align: right">
                  {{ `${user.company.currency + ' ' + sub_total}` }}
                </td>
              </tr>
              <tr v-if="editSale.taxes.length === 0">
                <td><b>{{ $vuetify.lang.t('$vuetify.articles.tax_by_sale') }}</b></td>
                <td style="text-align: right">
                  {{ user.company.currency + ' ' + parseFloat(0).toFixed(2) }}
                </td>
              </tr>
              <tr
                v-for="tax in editSale.taxes"
                v-else
                :key="tax.name"
              >
                <td><b>{{ $vuetify.lang.t('$vuetify.articles.tax') }}({{ tax.name }})</b></td>
                <td
                  v-if="tax.percent==='true'"
                  style="text-align: right"
                >
                  <i>{{ `${user.company.currency + ' ' + parseFloat(tax.value * sub_total / 100).toFixed(2)}` }} ({{ tax.value }}%)
                  </i>
                </td>
                <td
                  v-else
                  style="text-align: right"
                >
                  <i>{{ `${user.company.currency + ' ' + tax.value}` }}</i>
                </td>
              </tr>
              <tr v-if="editSale.discounts.length === 0">
                <td><b>{{ $vuetify.lang.t('$vuetify.menu.discounts') }}</b></td>
                <td style="text-align: right">
                  {{ user.company.currency + ' ' +parseFloat(0).toFixed(2) }}
                </td>
              </tr>
              <tr
                v-for="disc in editSale.discounts"
                v-else
                :key="disc.id"
              >
                <td><b>{{ disc.name }}{{ disc.percent ? '('+disc.value +'%)':'' }}</b></td>
                <td
                  v-if="disc.percent==='true'"
                  style="text-align: right"
                >
                  <i>-{{ `${user.company.currency + ' ' + parseFloat(disc.value * sub_total / 100).toFixed(2)}` }}</i>
                </td>
                <td
                  v-else
                  style="text-align: right"
                >
                  <i>{{ `${user.company.currency + ' ' + disc.value}` }}</i>
                </td>
              </tr>
              <tr>
                <td>
                  {{ $vuetify.lang.t('$vuetify.pay.total') }}
                </td>
                <td style="text-align: right">
                  {{ `${user.company.currency + ' ' + total}` }}
                </td>
              </tr>
            </tbody>
          </table>
          <b style="margin-top: 10px">{{ $vuetify.lang.t('$vuetify.report.breakdown').toUpperCase() }}</b>
          <table style="width: 100%">
            <tbody>
              <tr
                v-for="(pay, h) in editSale.pays"
                :key="h"
                class="total"
              >
                <td
                  class="price"
                  style="border-right: black"
                >
                  {{ $vuetify.lang.t('$vuetify.payment.' + pay.method) }}:
                </td>
                <td
                  class="price"
                  style="text-align: right"
                >
                  {{ `${user.company.currency + ' ' + parseFloat(pay.cant).toFixed(2)}` }}
                </td>
              </tr>
            </tbody>
          </table>
          <p class="centrado">
            <i> {{ user.company.footer ? user.company.footer.toUpperCase(): '' }}</i><br>
            {{ $vuetify.lang.t('$vuetify.report.contact_us') +': '+ user.company.email }}
          </p>
        </div>
      </v-card-text>
      <v-card-actions>
        <v-btn
          class="mb-2"
          @click="toogleShowModal(false)"
        >
          <v-icon>mdi-close</v-icon>
          {{ $vuetify.lang.t('$vuetify.actions.cancel') }}
        </v-btn>
        <v-spacer />
        <v-btn
          class="mb-2"
          color="primary"
          @click="printFacture"
        >
          <v-icon>mdi-printer</v-icon>
          {{ $vuetify.lang.t('$vuetify.actions.print') }}
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import { mapActions, mapGetters, mapState } from 'vuex'
import printJS from 'print-js'

export default {
  name: 'PrintFacture',
  props: {
    id: {
      type: String,
      default: ''
    }
  },
  data () {
    return {
      totalTax: 0,
      totalDisc: 0,
      total: 0,
      sub_total: 0
    }
  },
  computed: {
    ...mapState('sale', ['editSale']),
    ...mapState('discount', ['discounts']),
    ...mapGetters('auth', ['user', 'userPin'])
  },
  async created () {
    await this.getDiscounts()
    this.totalTax = 0
    this.totalDisc = 0
    this.total = 0
    this.sub_total = 0
    this.editSale.articles.forEach((v) => {
      this.sub_total = parseFloat(v.totalPrice) + this.sub_total
    })
    this.editSale.taxes.forEach((v) => {
      this.totalTax += v.percent === 'true' ? this.sub_total * v.value / 100 : v.value
    })
    this.editSale.discounts.forEach((v) => {
      this.totalDisc += v.percent === 'true' ? this.sub_total * v.value / 100 : v.value
    })
    this.total = (this.sub_total + parseFloat(this.totalTax) - parseFloat(this.totalDisc)).toFixed(2)
    this.total = parseFloat(this.total).toFixed(2)
    this.$emit('updateData')
  },
  methods: {
    ...mapActions('sale', ['toogleShowModal']),
    ...mapActions('discount', ['getDiscounts']),
    total_pay (item) {
      let sum = 0
      item.taxes.forEach((v) => {
        sum += v.percent ? item.cant * item.price * v.value / 100 : v.value
      })
      let discount = 0
      item.discount.forEach((v) => {
        discount += v.percent ? item.cant * item.price * v.value / 100 : v.value
      })
      return item.cant * item.price + sum - discount - item.moneyRefund
    },
    printFacture () {
      printJS({ printable: 'ticket', type: 'html', targetStyles: ['*'] })
    }
  }
}
</script>

<style scoped>

.profile {
    width: 100px;
    height: 100px;
    border-radius: 50%;
}
* {
    font-size: 12px;
    font-family: 'Times New Roman',sans-serif;
}

td,
th,
tr,
table {
    border-top: 1px solid black;
    border-collapse: collapse;
}

td.producto,
th.producto {
    width: 75px;
    max-width: 75px;
}

td.cantidad,
th.cantidad {
    width: 40px;
    max-width: 40px;
    word-break: break-all;
}

td.precio,
th.precio {
    width: 40px;
    max-width: 40px;
    word-break: break-all;
}

.centrado {
    text-align: center;
    align-content: center;
}

#ticket {
    padding-top: 5px;
    align-content: center;
    width: 75mm;
    max-width: 75mm;
}

img {
    max-width: inherit;
    width: inherit;
}

</style>
