<template>
  <div class="page-add-product">
    <v-container>
      <v-card>
        <v-card-title>
          <span class="headline">{{
            $vuetify.lang.t('$vuetify.titles.new', [
              $vuetify.lang.t('$vuetify.articles.name'),
            ])
          }}</span>
        </v-card-title>
        <v-card-text />
        <v-card-actions>
          <v-spacer />
          <v-btn
            class="mb-2"
            color="error"
            @click="$router.push({name:'product_list'})"
          >
            <v-icon>mdi-close</v-icon>
            {{ $vuetify.lang.t('$vuetify.actions.cancel') }}
          </v-btn>
          <v-btn
            class="mb-2"
            color="primary"
          >
            <v-icon>mdi-check</v-icon>
            {{ $vuetify.lang.t('$vuetify.actions.save') }}
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-container>
  </div>
</template>

<script>
export default {
  name: 'NewArticlePage'
}
</script>

<style scoped>

</style>
