import ResourceListPage from './components/pages/ResourceListPage.vue';
import DashboardPage from './components/pages/DashboardPage.vue';

export default [
    {
        name: 'resource-list',
        path: '/list/:id',
        component: ResourceListPage
    },
    {
        name: 'dashboard',
        path: '/dashboard/:id',
        component: DashboardPage,
    }
];