const LOAD_PRODUCTS = 'LOAD_PRODUCTS'
const ADD_TO_CART = 'ADD_TO_CART'
const REMOVE_FROM_CART = 'REMOVE_FROM_CART'

const state = {

  items: []

}

const mutations = {
  [LOAD_PRODUCTS] (state, products) {
    state.items = products
  },

  [ADD_TO_CART] (state, productId) {
    state.items
      .find(product => product.id === productId)
      .inventory--
  },

  [REMOVE_FROM_CART] (state, removedProduct) {
    state.items
      .find(product => product.id === removedProduct.id)
      .inventory += removedProduct.quantity
  }
}

const actions = {
  async loadProducts ({ commit }) {

  }
}

const getters = {
  products: (state) => state.items
}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
}
