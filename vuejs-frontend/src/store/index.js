import { createStore } from 'vuex'
import createPersistedState from 'vuex-persistedstate'
import auth from './modules/auth'
import admin from './modules/admin'
import app from './modules/app'
import user from './modules/user';

export default createStore({
    plugins: [createPersistedState()],
    modules: {
      auth,
      admin,
      app,
      user
    }
  })
  