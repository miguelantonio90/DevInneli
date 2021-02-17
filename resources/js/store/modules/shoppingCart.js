const ADD_TO_CART = 'ADD_TO_CART'
const REMOVE_FROM_CART = 'REMOVE_FROM_CART'

const state = {

	items: []

}

const mutations = {

	[ADD_TO_CART] (state, productId) {
		const found = state.items.find(
			(product) => product.id === productId
		)

		if (found) { found.quantity++ } else {
			state.items.push({
				id: productId,
				quantity: 1
			})
		}
	},

	[REMOVE_FROM_CART] (state, removedProduct) {
		const index = state.items.findIndex(
			(currentItem) => currentItem.id === removedProduct.id
		)
		state.items.splice(index, 1)
	}

}

const actions = {
	async addItemToCart ({ commit }, item) {
		if (item.inventory > 0) { commit(ADD_TO_CART, item.id) }
	},

	async removeItemFromCart ({ commit }, item) {
		commit(REMOVE_FROM_CART, item)
	}
}

const getters = {
	cartItemList: (state) => state.items,
	cartItems: (state) => {
		const items = state.shoppingCart.items
		const cart = []

		items.forEach((item, index) => {
			const found = state.items.find(
				(product) => item.id === product.id
			)

			cart.push({
				...found,
				quantity: item.quantity
			})
		})
		return cart
	},
	cartItemCount: (state) => {
		return this.cartItems(state).reduce((total, current) => {
			return total + current.quantity
		}, 0)
	},
	cartSubtotal: state => {
		return this.calculateCart(state, 'price')
	},

	cartShipping: state => {
		return this.calculateCart(state, 'shipping')
	},

	cartTaxes: state => {
		return Math.floor(
			this.calculateCart(state, 'price') * 0.04166 // TODO: tax rate
		)
	},

	cartGrandTotal: state => {
		return this.cartSubtotal(state) + this.cartShipping(state) + this.cartTaxes(state)
	},

	calculateCart: (state, value) => {
		return this.cartItems(state).reduce((total, current) => {
			return total + (current[value] * current.quantity)
		}, 0)
	}
}

export default {
	namespaced: true,
	state,
	getters,
	actions,
	mutations
}
