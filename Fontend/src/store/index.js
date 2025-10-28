import { createStore } from 'vuex'
import axios from 'axios'
const API_URL = "http://localhost:3315/"

export default createStore({
  state: {
    employee_info: []
  },
  getters: {
  },
  mutations: {
    get_employee_info(state, payload) {
      state.employee_info = payload;
    },
  },
  actions: {
    async fetch_employee_info({ commit }) {
      let data = await axios.get(`${API_URL}clockInOut`)
      commit('get_employee_info', data.data.employees)
      console.log(data.data.employees)
    },

    async add_employee({ dispatch }, payload) {
      await axios.post(`${API_URL}employees`, payload)
      dispatch("fetch_employee_info")
    },

    async edit_employee({ dispatch }, payload){
      await axios.patch(`${API_URL}employees`, payload)
      dispatch("fetch_employee_info")
    },
  },
  modules: {
  }
})
