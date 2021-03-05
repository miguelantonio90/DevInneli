<template>
  <v-container>
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
    </v-card>
  </v-container>
</template>
<script>
export default {
  name: 'BankList'
}
</script>

<style scoped>

</style>
