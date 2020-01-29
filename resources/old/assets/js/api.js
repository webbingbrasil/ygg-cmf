import axios from 'axios';
import {API_PATH} from "./consts";
import paramsSerializer from './helpers/paramsSerializer';

export const api = axios.create({
    baseURL: API_PATH,
    paramsSerializer,
});

export function getDashboard({ dashboardKey, filters }) {
    return api.get(`dashboard/${dashboardKey}`, {
        params: {
            ...filters,
        },
    }).then(response => response.data);
}

export function postDashboardAction({ dashboardKey, actionKey, query, data }) {
    return api.post(`dashboard/${dashboardKey}/action/${actionKey}`, {
        query,
        data,
    }, { responseType: 'blob' });
}

export function getDashboardActionFormData({ dashboardKey, actionKey, query }) {
    return api.get(`dashboard/${dashboardKey}/action/${actionKey}/data`, {
        params: {
            ...query,
        },
    }).then(response => response.data.data);
}

export function postResourceListReorder({ resourceKey, instances }) {
    return api.post(`list/${resourceKey}/reorder`, { instances });
}

export function getGlobalFilters() {
    return api.get(`filters`).then(response => response.data);
}

export function postGlobalFilters({ filterKey, value }) {
    return api.post(`filters/${filterKey}`, { value });
}

export function getAutocompleteSuggestions({ url, method, locale, searchAttribute, query, }) {
    const params = {
        locale,
        [searchAttribute]: query,
    };
    if(method.toLowerCase() === 'get') {
        return axios.get(url, { params})
            .then(response => response.data);
    } else {
        return axios.post(url, params)
            .then(response => response.data);
    }
}
