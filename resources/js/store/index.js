import Vue from 'vue';
import Vuex from 'vuex';
import employee from './modules/Employee';
import bike from './modules/Bike';
import bikeReservation from './modules/BikeReservation';
import notification from './modules/Notification';

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        employee,
        bike,
        bikeReservation,
        notification
    }
})