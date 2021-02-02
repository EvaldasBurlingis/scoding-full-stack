import axios from 'axios';

export default {
    namespaced: true,
    state: () => ({
        users: [],
        tasks: []
    }),
    mutations: {
        SET_USERS(state, users) {
            state.users = users;
        },
        SET_TASKS(state, tasks) {
            state.tasks = tasks;
        }
    },
    actions: {
        async getUsers({ commit }) {
            await axios.get('/api/admin/users', {
                headers: {
                  'Authorization': `Bearer ${this.state.auth.token}`
                }
              })
                .then(res => commit('SET_USERS', res.data.data.users))
                .catch(err => console.error(err));
        },
        async createUser({ dispatch, commit }, payload) {
            commit('app/SET_LOADING', true, { root: true });
            await axios.post('/api/admin/users', payload, {
                headers: {
                    'Authorization': `Bearer ${this.state.auth.token}`
                }
            })
                .then((res) => {
                    dispatch('getUsers');
                    dispatch('getTasks');    
                    commit('app/SET_SUCCESS_MESSAGE', res.data.message, { root: true });
                })
                .catch(err => console.log(err));

            await commit('app/SET_LOADING', false, { root: true });
        },
        async deleteUser({ dispatch, commit }, id) {
            commit('app/SET_LOADING', true, { root: true });
            await axios.delete(`/api/admin/users/${id}`, {
                headers: {
                'Authorization': `Bearer ${this.state.auth.token}`
                }
            }).catch(err => console.error(err));

            await dispatch('getUsers');
            await dispatch('getTasks');
            await commit('app/SET_LOADING', false, { root: true });
        },
        async updateUser({ commit, dispatch }, payload) {
            commit('app/SET_LOADING', true, { root: true });
            await axios.put(`/api/admin/users/${payload.id}`, payload, {
                headers: {
                    'Authorization': `Bearer ${this.state.auth.token}`
                }
            })
                .then(res => {
                    commit('app/SET_ERRORS', null, { root: true });
                    commit('app/SET_SUCCESS_MESSAGE', res.data.message, { root: true });
                    dispatch('getUsers');
                })
                .catch(err => console.log(err));
            
                commit('app/SET_LOADING', false, { root: true });
            },
        async getTasks({ commit }) {
            await axios.get('/api/admin/tasks', {
                headers: {
                  'Authorization': `Bearer ${this.state.auth.token}`
                }
              })
                .then(res => commit('SET_TASKS', res.data.data.tasks))
                .catch(err => console.error(err));
        },
        async createTask({ commit, dispatch }, payload) {
            commit('app/SET_LOADING', true, { root: true });
            await axios.post('api/admin/tasks', payload, {
                 headers: {
                    'Authorization': `Bearer ${this.state.auth.token}`
                }
            }).then(res => {
                commit('app/SET_ERRORS', null, { root: true });
                commit('app/SET_SUCCESS_MESSAGE', res.data.message, { root: true });
                dispatch('getTasks');
                dispatch('getUsers'); 
            }).catch(err => commit('app/SET_ERRORS', err.response.data.errors, { root: true }))
            
            await commit('app/SET_LOADING', false, { root: true });
        },
        async updateTask({ commit, dispatch }, payload) {
            commit('app/SET_LOADING', true, { root: true });
            await axios.put(`/api/admin/tasks/${payload.id}`, payload, {
                headers: {
                'Authorization': `Bearer ${this.state.auth.token}`
                }
            }).then((res) => {
                commit('app/SET_ERRORS', null, { root: true });
                commit('app/SET_SUCCESS_MESSAGE', res.data.message, { root: true });
                dispatch('getTasks');
            }).catch(err => {
                commit('app/SET_ERRORS', err.response.data.errors, { root: true });
            })

            await commit('app/SET_LOADING', false, { root: true });
        },
        async deleteTask({ dispatch, commit }, id) {
            commit('app/SET_LOADING', true, { root: true });
            await axios.delete(`/api/admin/tasks/${id}`, {
                headers: {
                'Authorization': `Bearer ${this.state.auth.token}`
                }
            }).catch(err => console.error(err));

            await dispatch('getUsers');
            await dispatch('getTasks');
            await commit('app/SET_LOADING', false, { root: true });
        }
    },
    getters: {
        users: state => state.users,
        tasks: state => state.tasks
    }
}