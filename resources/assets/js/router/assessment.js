export default [
    {path: '/assessment', component: require('../views/assessment/index.vue')},
    {path: '/assessment/create', component: require('../views/assessment/form.vue')},
    {path: '/assessment/:id/edit', component: require('../views/assessment/form.vue'), meta: {mode: 'edit'}},
    {path: '/assessment/:id/clone', component: require('../views/assessment/form.vue'), meta: {mode: 'clone'}},
    {path: '/assessment/:id', component: require('../views/assessment/show.vue')},
]
