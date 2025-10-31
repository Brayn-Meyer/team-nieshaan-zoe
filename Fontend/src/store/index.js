import { createStore } from 'vuex'
import axios from 'axios'
import API_URL from '../API';
import socket from '../sockets/socket';

function socketPlugin(store) {
  if (!socket || typeof socket.on !== 'function') {
    console.warn('Socket not available to store plugin')
    return
  }
  // when backend emits "kpiUpdated" refresh employee list
  socket.on('kpiUpdated', () => {
    // silent refresh — log any error
    store.dispatch('fetch_employee_info').catch(err => {
      console.error('fetch_employee_info after kpiUpdated failed', err)
    })
  })
}

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
      let data = await axios.get(`${API_URL}/api/clock-in-out/clockInOut`)
      // Use the new formatted employees data if available, otherwise fall back to old structure
      const employees = data.data.employees || data.data.clock_in_out_data || []
      commit('get_employee_info', employees)
      console.log('Fetched employees:', employees)
    },

    async add_employee({ dispatch }, payload) {
      await axios.post(`${API_URL}/api/employees/addEmployee`, payload)
      dispatch("fetch_employee_info")
      console.log("Added Employee", payload)
    },

    async edit_employee({ dispatch }, payload){
      // expect payload to include employee_id
      await axios.patch(`${API_URL}/api/edit-employee/employee/edit/${payload.employee_id}`, payload)
      dispatch("fetch_employee_info")
    },

    async delete_employee({ dispatch }, id) {
      await axios.delete(`${API_URL}/api/employees/deleteEmployee/${id}`)
      dispatch("fetch_employee_info")
    },
  },
  plugins: [socketPlugin],
  modules: {
  }
})
