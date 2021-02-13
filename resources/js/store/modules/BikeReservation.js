const state = {
    bikeReservation: []
}

const mutations = {
    setBikeReservation (state, data) {
        state.bikeReservation = data
    },
    addBikeReservation(state, data) {
        state.bikeReservation.unshift(data);
    }
}

const actions = {
    addBikeReservation ( { commit }, data ) {
        commit('setBikeReservation', data);
    },
    pushBikeReservation ( { commit }, data ) {
        commit('addBikeReservation', data);
    }
}

const getters = {
    bikeReservation (state) {
        return state.bikeReservation
    }
}

export default {
    state,
    mutations,
    actions,
    getters
}