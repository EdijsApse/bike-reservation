const state = {
    employees: []
}

const mutations = {
    setEmployees (state, data) {
        state.employees = data
    },
    addEmployee(state, data) {
        state.employees.unshift(data);
    }
}

const actions = {
    addEmployees ( { commit }, data ) {
        commit('setEmployees', data);
    },
    pushEmployee ( { commit }, data ) {
        commit('addEmployee', data);
    }
}

const getters = {
    employees (state) {
        return state.employees
    }
}

export default {
    state,
    mutations,
    actions,
    getters
}