<template>
  <v-dialog
    v-model="toogleShowModal"
    max-width="850px"
    persistent
  >
    <v-card>
      <v-card-text>
        <div
          id="letter"
        >
          <header class="clearfix">
            <div class="container">
              <figure>
                <img
                  v-if="user.company.logo"
                  class="logo profile mx-auto d-block"
                  :src="user.company.logo"
                  alt="LOGO"
                >
              </figure>
              <div class="company-info">
                <h2 class="title">
                  {{ user.company.name }}
                </h2>
                <div class="address">
                  <p>
                    {{ user.company.address }}
                  </p>
                </div>
                <div class="phone">
                  {{ user.company.phone }}
                </div>
                <div class="email">
                  <a
                    :href="'mailto:' + `${user.company.email }`"
                  >{{ user.company.email }}</a>
                </div>
              </div>
            </div>
          </header>

          <section>
            <div class="container">
              <div class="details clearfix">
                <div
                  v-if="editSale.client"
                  class="client left"
                >
                  <p class="name">
                    {{ editSale.client.firstName }}
                  </p>
                  <p>{{ editSale.client.address }}</p>
                  <p>{{ editSale.client.address }}</p>
                  <a
                    :href="'mailto:' + `${editSale.client.email }`"
                  >{{ editSale.client.email }}</a>
                </div>
                <div class="data right">
                  <div class="date">
                    <p>
                      {{ $vuetify.lang.t('$vuetify.tax.noFacture') + ': ' }}
                      {{ editSale.no_facture }}
                    </p>
                  </div>
                  <div class="date">
                    <p>
                      {{ $vuetify.lang.t('$vuetify.menu.box') + ': ' }}
                      {{ editSale.box.name }}
                    </p>
                  </div>
                  <div class="date">
                    <p>
                      {{ $vuetify.lang.t('$vuetify.articles.sell_by') + ': ' }}
                      {{ editSale.create.firstName }} {{ editSale.create.lastName? editSale.create.lastName: '' }}
                    </p>
                  </div>
                  <div class="date">
                    <p>
                      {{ $vuetify.lang.t('$vuetify.date') + ': ' }}
                      {{ dateFormatCreated }}
                    </p>
                  </div>
                </div>
              </div>

              <table>
                <thead>
                  <tr>
                    <th class="qty">
                      {{ $vuetify.lang.t('$vuetify.report.cant').toUpperCase() }}
                    </th>
                    <th class="desc">
                      {{ $vuetify.lang.t('$vuetify.menu.article').toUpperCase() }}
                    </th>
                    <th class="price">
                      $$
                    </th>
                    <th class="total">
                      Total
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="article in editSale.articles"
                    :key="article.ref"
                  >
                    <td class="qty">
                      {{ article.cant }}
                    </td>
                    <td class="desc">
                      <h3>{{ article.name }} {{ article.um? '('+$vuetify.lang.t('$vuetify.um.' + JSON.parse(article.um).name) + ')':'' }}</h3>
                    </td>
                    <td class="price">
                      {{ user.company.currency +' ' + article.price }}
                      <table>
                        <thead>
                          <th style="width: 60%; background-color: white; color: black; font-size: large" />
                          <th style="width: 40%; background-color: white; color: black; font-size: large" />
                        </thead>
                        <tbody>
                          <tr
                            v-for="(lDiscount, j) of article.discount"
                            :key="j"
                          >
                            <td>
                              {{ $vuetify.lang.t('$vuetify.menu.discount') +'(' +lDiscount.name }}{{ lDiscount.percent ? '('+lDiscount.value +'%)) ':') ' }}
                            </td>
                            <td>
                              <i> -{{ `${user.company.currency}` }}
                                {{ lDiscount.percent ? parseFloat(lDiscount.value*article.cant*article.price/100).toFixed(2) : parseFloat(lDiscount.value).toFixed(2) }}
                              </i>
                            </td>
                          </tr>
                          <tr
                            v-for="lTax of article.taxes"
                            :key="lTax.name"
                          >
                            <td>
                              {{ $vuetify.lang.t('$vuetify.articles.tax') +'(' +lTax.name }}{{ lTax.percent ? '('+lTax.value +'%)) ':') ' }}
                            </td>
                            <td>
                              <i> +{{ `${user.company.currency}` }}
                                {{ lTax.percent ? parseFloat(lTax.value*article.cant*article.price/100).toFixed(2) : parseFloat(lTax.value).toFixed(2) }}
                              </i>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </td>
                    <td class="total">
                      {{ user.company.currency +' '+ parseFloat(article.totalPrice).toFixed(2) }}
                    </td>
                  </tr>
                </tbody>
              </table>

              <div class="details clearfix">
                <div class="data left">
                  <table class="total">
                    <tbody>
                      <tr
                        v-for="pay in editSale.pays"
                        :key="pay.id"
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
                          style="text-align: left"
                        >
                          {{ `${user.company.currency + ' ' + parseFloat(pay.cant).toFixed(2)}` }}
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="data right">
                  <table class="total">
                    <tbody>
                      <tr class="total">
                        <td class="price">
                          {{ $vuetify.lang.t('$vuetify.pay.sub_total') }}:
                        </td>
                        <td class="price">
                          {{ `${user.company.currency + ' ' + parseFloat(sub_total).toFixed(2)}` }}
                        </td>
                      </tr>
                      <template v-if="editSale.taxes.length > 0">
                        <tr
                          v-for="tax in editSale.taxes"
                          :key="tax.id"
                          class="total"
                        >
                          <td class="price">
                            {{ $vuetify.lang.t('$vuetify.tax.name') }}({{ tax.name }} +{{ tax.value }}%)
                          </td>
                          <td class="price">
                            <i>+{{ `${user.company.currency + ' ' + parseFloat(tax.value * sub_total / 100).toFixed(2)}` }} </i>
                          </td>
                        </tr>
                      </template>
                      <template v-else>
                        <tr>
                          <td class="price">
                            {{ $vuetify.lang.t('$vuetify.articles.tax_by_sale') }}
                          </td>
                          <td class="price">
                            <i>{{ user.company.currency + ' 00.00' }}</i>
                          </td>
                        </tr>
                      </template>
                      <template v-if="editSale.discounts.length > 0">
                        <tr
                          v-for="discount in editSale.discounts"
                          :key="discount.id"
                          class="total"
                        >
                          <td
                            class="price"
                            style="width: 30%"
                          >
                            {{ $vuetify.lang.t('$vuetify.menu.discount') }}({{ discount.name }} -{{ discount.value }}%)
                          </td>
                          <td class="price">
                            <i>-{{ `${user.company.currency + ' ' + parseFloat(discount.value * sub_total / 100).toFixed(2)}` }}</i>
                          </td>
                        </tr>
                      </template>
                      <template v-else>
                        <tr>
                          <td class="price">
                            {{ $vuetify.lang.t('$vuetify.menu.discounts') }}
                          </td>
                          <td class="price">
                            <i>{{ user.company.currency + ' 00.00' }}</i>
                          </td>
                        </tr>
                      </template>
                      <tr>
                        <td class="price">
                          <h3>{{ $vuetify.lang.t('$vuetify.pay.total') }}:</h3>
                        </td>
                        <td class="price">
                          <h3>
                            <i>{{ `${user.company.currency + ' ' + total}` }}</i>
                          </h3>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </section>
          <footer />
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
	name: 'PrintFactureLetter',
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
		...mapGetters('auth', ['user', 'userPin']),
		dateFormatCreated () {
			const date = new Date(this.editSale.updated_at)
			const day = date.getDate()
			const month = date.getMonth() + 1
			const year = date.getFullYear()
			if (month < 10) {
				return (`${day}/0${month}/${year}`)
			} else {
				return (`${day}/${month}/${year}`)
			}
		}
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
			printJS({
				printable: 'letter',
				type: 'html',
				targetStyles: ['*'],
				scanStyles: true
			})
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
    font-size: 10px;
    font-family: 'Times New Roman',sans-serif;
}

td,
th,
tr,
table {
    border-top: 1px solid black;
    border-collapse: collapse;
}

html, body, div, span, applet, object, iframe,
h1, h2, h3, h4, h5, h6, p, blockquote, pre,
a, abbr, acronym, address, big, cite, code,
del, dfn, em, img, ins, kbd, q, s, samp,
small, strike, strong, sub, sup, tt, var,
b, u, i, center,
dl, dt, dd, ol, ul, li,
fieldset, form, label, legend,
table, caption, tbody, tfoot, thead, tr, th, td,
article, aside, canvas, details, embed,
figure, figcaption, footer, header, hgroup,
menu, nav, output, ruby, section, summary,
time, mark, audio, video {
    margin: 0;
    padding: 0;
    border: 0;
    font: inherit;
    font-size: 100%;
    vertical-align: baseline;
}

html {
    line-height: 1;
}

ol, ul {
    list-style: none;
}

table {
    border-collapse: collapse;
    border-spacing: 0;
}

caption, th, td {
    text-align: left;
    font-weight: normal;
    vertical-align: middle;
}

q, blockquote {
    quotes: none;
}
q:before, q:after, blockquote:before, blockquote:after {
  content: none;
}

a img {
    border: none;
}

article, aside, details, figcaption, figure, footer, header, hgroup, main, menu, nav, section, summary {
    display: block;
}

body {
    font-family: 'Source Sans Pro', sans-serif;
    font-weight: 300;
    font-size: 12px;
    margin: 0;
    padding: 0;
    color: #555555;
}
body a {
    text-decoration: none;
    color: inherit;
}
body a:hover {
    color: inherit;
    opacity: 0.7;
}
body .container {
    min-width: 460px;
    margin: 0 auto;
    padding: 0 20px;
}
body .clearfix:after {
    content: "";
    display: table;
    clear: both;
}
.clearfix:after {
    content: "";
    display: table;
    clear: both;
}
body .left {
    float: left;
}
body .right {
    float: right;
}
body .helper {
    display: inline-block;
    height: 100%;
    vertical-align: middle;
}
body .no-break {
    page-break-inside: avoid;
}

header {
    margin-top: 15px;
    margin-bottom: 45px;
}
header figure {
    float: left;
    margin-right: 10px;
    width: 65px;
    height: 70px;
    background-color: #555555;
    text-align: center;
}
header figure img {
    margin-top: 10px;
}
header .company-info {
    float: right;
    color: #555555;
    font-family: SourceSansPro,sans-serif;
    line-height: 10px;
}
header .company-info .address, header .company-info .phone, header .company-info .email {
    margin-top: 5px;
    position: relative;
}
header .company-info .address img, header .company-info .phone img {
    margin-top: 2px;
}
header .company-info .email img {
    margin-top: 3px;
}
header .company-info .title {
    color: #555555;
    font-weight: 400;
    font-size: 1.33333333333333em;
}
header .company-info .icon {
    position: absolute;
    left: -15px;
    top: 1px;
    width: 10px;
    height: 10px;
    background-color: #555555;
    text-align: center;
    line-height: 0;
}

section .details {
    min-width: 440px;
    margin-bottom: 40px;
    padding: 5px 10px;
    color: black;
    line-height: 20px;
}
section .details .client {
    width: 50%;
}
section .details .client .name {
    font-size: 1.16666666666667em;
    font-weight: 600;
}
section .details .data {
    width: 50%;
    font-weight: 600;
    text-align: right;
}
section .details .title {
    margin-bottom: 5px;
    font-size: 1.33333333333333em;
    text-transform: uppercase;
}
section table {
    width: 100%;
    margin-bottom: 20px;
    table-layout: fixed;
    border-collapse: collapse;
    border-spacing: 0;
}
section table .qty, section table .unit, section table .total {
    width: 15%;
}
section table .desc {
    width: 25%;
}
section table .price {
    width: 45%;
}
section table thead {
    display: table-header-group;
    vertical-align: middle;
    border-color: inherit;
}
section table thead th {
    padding: 7px 10px;
    background: #FFFFFF;
    border-bottom: 2px solid black;
    color: black;
    text-align: center;
    font-weight: 200;
    text-transform: uppercase;
}
section table thead th:last-child {
    border-right: none;
}
section table tbody tr td {
    border-bottom: 1px solid #555555;
}
section table tbody td {
    padding: 10px 10px;
    text-align: center;
    border-right: 1px solid #555555;
}
section table tbody td:last-child {
    border-right: none;
}
section table tbody td.desc {
    text-align: left;
}
section table tbody td.total {
    color: #555555;
    font-weight: 600;
    text-align: right;
}
section table tbody h3 {
    margin-bottom: 5px;
    color: #555555;
    font-weight: 600;
}
section table.total {
    margin-bottom: 50px;
}
section table.total tbody tr td {
    padding: 0 10px 12px;
    border: none;
    background-color: #ffffff;
    color: #555555;
    font-weight: 300;
    text-align: right;
}
section table.total tbody tr:first-child td {
    padding-top: 12px;
}
section table.total tbody tr:last-child td {
    background-color: transparent;
}
section table.total tbody .total {
    padding: 0;
}
section table.total tbody .total div {
    float: right;
    padding: 11px 10px;
    background-color: #555555;
    color: #ffffff;
    font-weight: 600;
}
section table.total tbody .total div span {
    display: inline-block;
    margin-right: 20px;
    width: 80px;
}

footer {
    margin-bottom: 15px;
    padding: 0 5px;
}
footer .thanks {
    margin-bottom: 40px;
    color: #555555;
    font-size: 1.16666666666667em;
    font-weight: 600;
}
footer .notice {
    margin-bottom: 15px;
}
footer .end {
    padding-top: 5px;
    border-top: 2px solid #555555;
    text-align: center;
}

img {
    max-width: inherit;
    width: inherit;
}

</style>
