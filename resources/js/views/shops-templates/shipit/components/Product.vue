<template>
  <div>
    <v-container>
      <v-row class="row">
        <v-col
          v-if="article.images"
          class="col-md-5 col-sm-5 col-xs-12"
        >
          <v-carousel v-if="article.images.length > 0">
            <v-carousel-item
              v-for="(image,i) in article.images"
              :key="i"
              :src="image.path"
            />
          </v-carousel>
        </v-col>
        <v-col class="col-md-7 col-sm-7 col-xs-12">
          <div class="pl-6">
            <p class="display-1 mb-0">
              {{ article.name }}
            </p>
            <v-card-actions class="pa-0">
              <p class="headline font-weight-light pt-3">
                ${{ article.price }}
              </p>
              <v-spacer />
              <v-rating
                v-model="article.start"
                class=""
                background-color="warning lighten-3"
                color="warning"
                dense
              />
            </v-card-actions>
            <p class="subtitle-1 font-weight-thin">
              {{ article.description }}
            </p>
            <v-text-field
              outlined
              style="width:100px"
              :value="1"
              dense
            />
            <v-btn
              class="primary white--text"
              outlined
              tile
              dense
            >
              <v-icon>mdi-cart</v-icon> ADD TO CART
            </v-btn>
          </div>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>
<script>
export default {
	props: {
		article: {
			type: Object,
			default: function () {
				return {}
			}
		}
	},
	data: () => ({
		rating: 4.5,
		item: 5
	})
}
</script>
f
