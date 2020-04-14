import Vue from 'vue'
import VueRouter from 'vue-router'
import covid_complaint from './covid_complaint'
import covid_config from './covid_config'
import assessment from './assessment'
import users from './users'
import NotFound from '../views/error/not_found.vue'

Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',
    scrollBehavior (to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition
        } else {
            return { x: 0, y: 0 }
        }
    },
    routes: [
        {path: '/', component: require('../views/dashboard/index.vue')},
        ...covid_complaint,       
        ...covid_config,       
        ...users,      
        ...assessment,      
        {path: '*', component: NotFound}
    ]
})

export default router
