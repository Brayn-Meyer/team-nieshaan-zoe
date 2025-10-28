import { createStore } from 'vuex'
import axios from 'axios'
const API_URL = "http://localhost:9090/"

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
      let data = await axios.get(`${API_URL}api/clock-in-out/clockInOut`)
      commit('get_employee_info', data.data.employees)
      console.log(data.data.employees)
    },

    async add_employee({ dispatch }, payload) {
      await axios.post(`${API_URL}api/employees/addEmployee`, payload)
      dispatch("fetch_employee_info")
    },

    async edit_employee({ dispatch }, payload){
      // expect payload to include id
      await axios.patch(`${API_URL}api/edit-employee/employee/edit/${payload.id}`, payload)
      dispatch("fetch_employee_info")
    },

    async delete_employee({ dispatch }, id) {
      await axios.delete(`${API_URL}api/employees/deleteEmployee/${id}`)
      dispatch("fetch_employee_info")
    },
  },
  modules: {
  }
})
