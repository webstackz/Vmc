export default [
    {path: '/covid_config', component: require('../views/sales_orders/index.vue')},
    {path: '/covid_config/create', component: require('../views/sales_orders/form.vue')},
    {path: '/covid_config/:id/edit', component: require('../views/sales_orders/form.vue'), meta: {mode: 'edit'}},
    {path: '/covid_config/:id/clone', component: require('../views/sales_orders/form.vue'), meta: {mode: 'clone'}},
    {path: '/covid_config/:id', component: require('../views/sales_orders/show.vue')},
]
