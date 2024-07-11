// src/store.js
import { createStore } from 'vuex';

export default createStore({
  state: {
    cart: [],
  },
  mutations: {
    ADD_TO_CART(state, product) {
      const item = state.cart.find(item => item.id === product.id);
      if (item) {
        item.quantity += product.quantity;
      } else {
        state.cart.push(product);
      }
    },
    SET_CART(state, cart) {
      state.cart = cart;
    },
  },
  actions: {
    addToCart({ commit }, product) {
      commit('ADD_TO_CART', product);
    },
    setCart({ commit }, cart) {
      commit('SET_CART', cart);
    },
  },
  getters: {
    totalItemsInCart(state) {
      return state.cart.reduce((total, item) => total + item.quantity, 0);
    },
    cartItems(state) {
      return state.cart;
    },
  },
});
