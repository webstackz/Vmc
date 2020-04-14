export default [
    {path: '/covid_config', component: require('../views/covid_config/index.vue')},
    {path: '/covid_config/create', component: require('../views/covid_config/form.vue')},
    {path: '/covid_config/:id/edit', component: require('../views/covid_config/form.vue'), meta: {mode: 'edit'}},
    {path: '/covid_config/:id/clone', component: require('../views/covid_config/form.vue'), meta: {mode: 'clone'}},
    {path: '/covid_config/:id', component: require('../views/covid_config/show.vue')},
]
