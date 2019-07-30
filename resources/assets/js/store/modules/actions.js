export const SET_ACTIONS = 'SET_ACTIONS';

export default {
    namespaced: true,

    state: {
        actions: null,
    },
    mutations: {
        [SET_ACTIONS](state, actions) {
            state.actions = actions;
        }
    },
    getters: {
        forType(state) {
            return type => state.actions ? state.actions[type] : null;
        }
    },
    actions: {
        update({commit}, {actions}) {
            commit(SET_ACTIONS, actions);
        }
    },
}
