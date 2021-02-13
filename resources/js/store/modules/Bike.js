const state = {
    bikes: []
}

const mutations = {
    setBikes (state, data) {
        state.bikes = data
    },
    addBike(state, data) {
        state.bikes.unshift(data);
    }
}

const actions = {
    addBikes ( { commit }, data ) {
        commit('setBikes', data);
    },
    pushBike ( { commit }, data ) {
        commit('addBike', data);
    }
}

const getters = {
    bikes (state) {
        return state.bikes
    }
}

export default {
    state,
    mutations,
    actions,
    getters
}