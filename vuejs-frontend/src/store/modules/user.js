import axios from 'axios';

export default {
    namespaced: true,
    state: () => ({
        tasks: []
    }),
    mutations: {
        SET_TASKS(state, tasks) {
            state.tasks = tasks;
        }
    },
    actions: {
        async updateTaskStatus({ commit, dispatch }, payload) {
            commit('app/SET_LOADING', true, { root: true });
            await axios.patch(`/api/users/${payload.user_id}/tasks/${payload.task_id}`, payload, {
                headers: {
                    'Authorization': `Bearer ${this.state.auth.token}`
                }
            }).then(res => {
                    commit('app/SET_ERRORS', null, { root: true });
                    commit('app/SET_SUCCESS_MESSAGE', res.data.message, { root: true });
                    dispatch('getUserTasks');
                }).catch(err => console.log(err));
            
            commit('app/SET_LOADING', false, { root: true });
        },
        async getUserTasks({ commit }) {
            commit('app/SET_LOADING', true, { root: true });
            await axios.get(`/api/users/${this.state.auth.user.id}/tasks`, {
                headers: {
                    'Authorization': `Bearer ${this.state.auth.token}`
                }
            }).then(res => {
                console.log(res);
                commit('SET_TASKS', res.data.data[0].tasks);
            }).catch(err => console.log(err));

            commit('app/SET_LOADING', false, { root: true });
        }
    },
    getters: {
        tasks: state => state.tasks
    }
}