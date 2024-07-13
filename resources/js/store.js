import { createStore } from 'vuex';
import axios from 'axios';

export default createStore({
  state: {
    cart: [],
    watchlist: [],
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
    SET_WATCHLIST(state, watchlist) {
      state.watchlist = watchlist;
    },
  },


  actions: {
    addToCart({ commit }, product) {
      commit('ADD_TO_CART', product);
    },

    setCart({ commit }, cart) {
      commit('SET_CART', cart);
    },

    fetchWatchlist({ commit }) {
      axios.get('/api/watchlist')
        .then(response => {
          if (response.data && typeof response.data === 'object') {
            const watchlist = Object.values(response.data);
            commit('SET_WATCHLIST', watchlist);
          } else {
            console.error('Response data is not in expected format:', response.data);
          }
        })
        .catch(error => {
          console.error('There was an error fetching the watchlist!', error);
        });
    },
  },

  getters: {

    // totalItemsInCart(state) {
    //   return state.cart.reduce((total, item) => total + item.quantity, 0);
    // },

    // totalItemsInWatchlist(state) {
    //   return state.watchlist.reduce((total, item) => total + item.quantity, 0);
    // },


    totalItemsInCart(state) {
      return state.cart.length; // Count of distinct items in the cart
    },

    totalItemsInWatchlist(state) {
      return state.watchlist.length; // Count of distinct items in the Watchlist
    },


    cartItems(state) {
      return state.cart;
    },

 
    watchlistItems(state) {
      return state.watchlist;
    },
  },
});
