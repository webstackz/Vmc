export default [
    {path: '/covid_complaint', component: require('../views/covid_complaint/index.vue')},
    {path: '/covid_complaint/create', component: require('../views/covid_complaint/form.vue')},
    {path: '/covid_complaint/:id/edit', component: require('../views/covid_complaint/form.vue'), meta: {mode: 'edit'}},
    {path: '/covid_complaint/:id', component: require('../views/covid_complaint/show.vue')},
]
