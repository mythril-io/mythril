import Vue from 'vue';
import Vuex from 'Vuex'
import createPersistedState from 'vuex-persistedstate'

Vue.use(Vuex)

const cdnURL = 'https://mythril.nyc3.cdn.digitaloceanspaces.com/';

const store = new Vuex.Store({
  state: {
    user: null,
    userAuthenticated: false,
    cdnURL: cdnURL,
    userAvatarURL: cdnURL + 'users/avatars/',
    gameIconURL: cdnURL + 'games/icons/',
    gameBannerURL: cdnURL + 'games/banners/'
  },
  plugins: [createPersistedState()],
  mutations: {
  	userLoggedIn (state, userObject) {
      state.userAuthenticated = true;
      state.user = userObject;
    },
    userLoggedOut (state) {
      state.userAuthenticated = false;
      state.user = null;
    },
    updateUser (state, userObject) {
      state.user = userObject;
    }
  }
})

export default store;