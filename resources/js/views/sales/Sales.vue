<template>
  <v-container>
    <v-row>
      <v-col
        :cols="12"
        md="3"
        sm="12"
        style="background-color: lightgray"
      >
        <p class="headline">
          Order Summary
        </p>
        <p class="overline">
          Shipping and additional costs are calculated based on values you have entered.
        </p>
        <v-simple-table>
          <template v-slot:default>
            <tbody>
              <tr>
                <td>Order Subtotal</td>
                <td
                  class="text-right"
                  style="width: 50px;"
                >
                  $160.00
                </td>
              </tr>
              <tr>
                <td>Shipping Charges</td>
                <td
                  class="text-right"
                  style="width: 50px;"
                >
                  $10.00
                </td>
              </tr>
              <tr>
                <td>Tax</td>
                <td
                  class="text-right"
                  style="width: 50px;"
                >
                  $5.00
                </td>
              </tr>
              <tr>
                <td>Total</td>
                <td
                  class="text-right"
                  style="width: 50px;"
                >
                  <b>$175.00</b>
                </td>
              </tr>
            </tbody>
          </template>
        </v-simple-table>
        <div class="text-center">
          <v-btn
            class="primary white--text mt-5"
            outlined
          >
            PROCEED TO PAY
          </v-btn>
        </div>
      </v-col>
      <v-col
        :cols="12"
        md="9"
        sm="12"
      >
        <v-row dense>
          <v-col
            cols="12"
            sm="8"
            class="pl-6 pt-6"
          >
            <small>Showing 1-12 of 200 products</small>
          </v-col>
          <v-col
            cols="12"
            sm="4"
          >
            <v-select
              v-model="select"
              class="pa-0"
              :items="options"
              style="margin-bottom: -20px;"
              outlined
              dense
            />
          </v-col>
        </v-row>
        <v-divider />
        <div class="row text-center">
          <div
            v-for="pro in products"
            :key="pro.id"
            class="col-md-3 col-sm-6 col-xs-12"
          >
            <v-hover v-slot:default="{ hover }">
              <v-card
                class="mx-auto"
                color="grey lighten-4"
                max-width="600"
              >
                <v-img
                  class="white--text align-end"
                  :aspect-ratio="16/9"
                  height="200px"
                  :src="pro.src"
                >
                  <v-card-title>{{ pro.type }} </v-card-title>
                  <v-expand-transition>
                    <div
                      v-if="hover"
                      class="d-flex transition-fast-in-fast-out white darken-2 v-card--reveal display-3 white--text"
                      style="height: 100%;"
                    >
                      <v-btn
                        v-if="hover"
                        href="#"
                        class=""
                        outlined
                      >
                        VIEW
                      </v-btn>
                    </div>
                  </v-expand-transition>
                </v-img>
                <v-card-text class="text--primary">
                  <div>
                    <a
                      href="#"
                      style="text-decoration: none"
                    >{{ pro.name }}</a>
                  </div>
                  <div>${{ pro.price }}</div>
                </v-card-text>
              </v-card>
            </v-hover>
          </div>
        </div>
        <div class="text-center mt-12">
          <v-pagination
            v-model="page"
            :length="6"
          />
        </div>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  data: () => ({
    range: [0, 10000],
    select: 'Popularity',
    options: [
      'Default',
      'Popularity',
      'Relevance',
      'Price: Low to High',
      'Price: High to Low'
    ],
    page: 1,
    min: 0,
    max: 10000,
    items: [
      {
        id: 2,
        name: 'Shoes',
        children: [
          { id: 2, name: 'Casuals' },
          { id: 3, name: 'Formals' },
          { id: 4, name: 'Sneakers' }
        ]
      },
      {
        id: 1,
        name: 'Clothing',
        children: [
          { id: 5, name: 'Shirts' },
          { id: 6, name: 'Tops' },
          { id: 7, name: 'Tunics' },
          { id: 8, name: 'Bodysuit' }
        ]
      }
    ],
    products: [
      {
        id: 1,
        name: 'BLACK TEE',
        type: 'Jackets',
        price: '18.00',
        src: '/assets/nature/n1.jpeg'
      },
      {
        id: 2,
        name: 'WHITE TEE',
        type: 'Polo',
        price: '40.00',
        src: '/assets/nature/n1.jpeg'
      },
      {
        id: 3,
        name: 'Zara limited...',
        type: 'Denim',
        price: '25.00',
        src: '/assets/nature/n1.jpeg'
      },
      {
        id: 4,
        name: 'SKULL TEE',
        type: 'Jackets',
        price: '30.00',
        src: '/assets/nature/n1.jpeg'
      },
      {
        id: 5,
        name: 'MANGO WINTER',
        type: 'Sweaters',
        price: '50.00',
        src: '/assets/nature/n1.jpeg'
      },
      {
        id: 6,
        name: 'SHIRT',
        type: 'Denim',
        price: '34.00',
        src: '/assets/nature/n1.jpeg'
      },
      {
        id: 7,
        name: 'TRUCKER JACKET',
        type: 'Jackets',
        price: '38.00',
        src: '/assets/nature/n1.jpeg'
      },
      {
        id: 8,
        name: 'COATS',
        type: 'Jackets',
        price: '25.00',
        src: '/assets/nature/n1.jpeg'
      }, {
        id: 9,
        name: 'MANGO WINTER',
        type: 'Sweaters',
        price: '50.00',
        src: '/assets/nature/n1.jpeg'
      },
      {
        id: 10,
        name: 'SHIRT',
        type: 'Denim',
        price: '34.00',
        src: '/assets/nature/n1.jpeg'
      },
      {
        id: 11,
        name: 'TRUCKER JACKET',
        type: 'Jackets',
        price: '38.00',
        src: '/assets/nature/n1.jpeg'
      },
      {
        id: 12,
        name: 'COATS',
        type: 'Jackets',
        price: '25.00',
        src: '/assets/nature/n1.jpeg'
      }
    ]
  })
}
</script>

<style>
.v-card--reveal {
    align-items: center;
    bottom: 0;
    justify-content: center;
    opacity: .8;
    position: absolute;
    width: 100%;
}
</style>
