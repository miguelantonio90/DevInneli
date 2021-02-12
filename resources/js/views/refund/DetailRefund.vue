<template>
  <v-container>
    <b>{{ $vuetify.lang.t('$vuetify.menu.refund') }}</b>
    <v-simple-table dense>
      <template v-slot:default>
        <thead>
          <tr>
            <th class="text-left">
              {{ $vuetify.lang.t('$vuetify.created_by') }}
            </th>
            <th class="text-left">
              {{ $vuetify.lang.t('$vuetify.date') }}
            </th>
            <th class="text-left">
              {{ $vuetify.lang.t('$vuetify.variants.cant') }}
            </th>
            <th class="text-left">
              {{ $vuetify.lang.t('$vuetify.payment.cash') }}
            </th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="refund in localArticle.refounds"
            :key="refund.id"
          >
            <td>
              {{ refund.created_by }}
            </td>
            <td>
              {{ refund.created_at }}
            </td>
            <td>
              {{ refund.cant }}
            </td>
            <td>
              {{ `${currency + ' ' +refund.money}` }}
            </td>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <td
              colspan="2"
              class="text-center"
            >
              <b>{{ $vuetify.lang.t('$vuetify.pay.total') }}</b>
            </td>
            <td style="color: red">
              <b>{{ parseFloat(localArticle.cantRefund).toFixed(2) }}</b>
            </td>
            <td style="color: red">
              <b>{{ `${currency + ' ' +parseFloat(localArticle.moneyRefund).toFixed(2)}` }}</b>
            </td>
          </tr>
        </tfoot>
      </template>
    </v-simple-table>
  </v-container>
</template>
<script>
export default {
	name: 'DetailRefund',
	props: {
		article: {
			type: Object,
			default: () => {}
		},
		currency: {
			type: String,
			default: ''
		}
	},
	data () {
		return {
			localArticle: {}
		}
	},
	mounted () {
		this.localArticle = this.article
	}
}
</script>

  <style scoped />
