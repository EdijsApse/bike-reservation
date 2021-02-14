import Vue from 'vue'
import VueRouter from 'vue-router'

import BikeReservationList from '../components/BikeReservation/List';
import BikesList from '../components/Bikes/List';
import EmployeesList from '../components/Employees/List';

Vue.use(VueRouter)

const routes = [
    {
        path: '/',
        component: BikeReservationList
    },
    {
        path: '/bikes',
        component: BikesList
    },
    {
        path: '/employees',
        component: EmployeesList
    },
    {
        path: '*',
        redirect: '/'
    }
]

const router = new VueRouter({
    mode: 'history',
    routes
})

export default router