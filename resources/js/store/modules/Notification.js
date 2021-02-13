const state = {
    message: null
}

const mutations = {
    setMessage (state, data) {
        state.message = data
    },
}

const actions = {
    addMessage ( { commit }, data ) {
        commit('setMessage', data);
    }
}

const getters = {
    message (state) {
        return state.message
    }
}

export default {
    state,
    mutations,
    actions,
    getters
}