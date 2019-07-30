import {postResourceListReorder} from "../../api";
import filters from './filters';

export const SET_RESOURCE_KEY = 'SET_RESOURCE_KEY';

export default {
    namespaced: true,
    modules: {
        filters,
    },

    state: {
        resourceKey: null,
    },

    mutations: {
        [SET_RESOURCE_KEY](state, resourceKey) {
            state.resourceKey = resourceKey;
        },
    },

    actions: {
        update({dispatch}, {data, layout, config, filtersValues}) {
            return Promise.all([
                dispatch('filters/update', {
                    filters: config.filters,
                    values: filtersValues
                }),
            ]);
        },
        reorder({state}, {instances}) {
            return postResourceListReorder({
                resourceKey: state.resourceKey,
                instances,
            });
        },
        setResourceKey({commit}, resourceKey) {
            commit(SET_RESOURCE_KEY, resourceKey);
        }
    }
}
