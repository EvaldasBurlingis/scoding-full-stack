import axios from 'axios';
import Cookies from 'js-cookie';
import router from '../../router';

export default {
    namespaced: true,
    state: () => ({
        user: null,
        isAdmin: false,
        token: null
    }),
    mutations: {
        SET_USER(state, user) {
            state.user = user;
        },
        SET_IS_ADMIN(state, isAdmin) {
            state.isAdmin = isAdmin;
        },
        SET_TOKEN(state, token) {
            state.token = token;
        }
    },
    actions: {
        async userLogin({ commit, dispatch }, credentials) {
            commit('app/SET_LOADING', true, { root: true });
            await axios.get('/sanctum/csrf-cookie');
            axios.post('/api/login', credentials)
                .then(res => {
                    commit('SET_USER', res.data.data.user);
                    commit('SET_IS_ADMIN', false)
                    commit('SET_TOKEN', res.data.data.token);
                    commit('app/SET_ERRORS', null, { root: true });
                    dispatch('user/getUserTasks', null, { root: true });
                    router.push('/home');
                })
                .catch(err => commit('app/SET_ERRORS', err.response.data.errors, { root: true }));
            
                await commit('app/SET_LOADING', false, { root: true });
        },
        async adminLogin({ commit, dispatch }, credentials) {
            commit('app/SET_LOADING', true, { root: true });
            await axios.get('/sanctum/csrf-cookie')
            axios.post('/api/admin-login', credentials)
                .then(res => {
                    commit('SET_USER', res.data.data.user);
                    commit('SET_IS_ADMIN', true)
                    commit('SET_TOKEN', res.data.data.token);
                    commit('app/SET_ERRORS', null, { root: true });
                    dispatch('admin/getUsers', null, { root: true });
                    dispatch('admin/getTasks', null, { root: true });
                    router.push('/admin/dashboard');
                })
                .catch(err => commit('app/SET_ERRORS', err.response.data.errors, { root: true }));
            
                await commit('app/SET_LOADING', false, { root: true });
        },
        async logout({ commit }) {
            await axios.post('/api/logout', [], {
                headers: {
                  'Authorization': `Bearer ${this.state.auth.token}`
                }
              })
                .then(res => {
                    if(res.status === 204) {
                        commit('SET_USER', null);
                        commit('SET_IS_ADMIN', false);
                        commit('app/SET_ERRORS', null, { root: true });
                        Cookies.remove('XSRF-TOKEN');
                        router.push('/');
                    }
                })
        }
    },
    getters: {
        user: state => state.user,
        isAdmin: state => state.isAdmin,
        isAuthenticated: state => !!state.user,
        token: state => state.token
    }
}