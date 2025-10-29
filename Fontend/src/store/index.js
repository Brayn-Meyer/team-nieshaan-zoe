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
    employee_info: [],
    history_info: []
  },
  getters: {
  },
  mutations: {
    get_employee_info(state, payload) {
      state.employee_info = payload;
    },
    get_history_info(state, payload) {
      state.history_info = payload;
    }
  },
  actions: {
    async fetch_employee_info({ commit }) {
      let data = await axios.get(`${API_URL}/api/clock-in-out/clockInOut`)
      // Use the new formatted employees data if available, otherwise fall back to old structure
      const employees = data.data.employees || data.data.clock_in_out_data || []
      commit('get_employee_info', employees)
      console.log('Fetched employees:', employees)
    },
    async fetch_history_info({ commit }) {
      let data = await axios.get(`${API_URL}/api/employees/`)
      const history = data.data.history || data.data || []
      commit('get_history_info', history)
    },

    async add_employee({ dispatch }, payload) {
      await axios.post(`${API_URL}/api/employees/addEmployee`, payload)
      dispatch("fetch_employee_info")
      console.log("Added Employee", payload)
    },
    async apply_history_filter({ commit }, payload) {
     try {
       // normalize payload so empty values don't break backend
        const body = {
          date: payload?.date || '',
          name: payload?.name || '',
          status: payload?.status || '',
          employeeId: payload?.employeeId || ''
        }

        const res = await axios.post(`${API_URL}/api/employees/search`, body)
        const history = res.data.history || res.data || []
        commit('get_history_info', history)
        console.log('Applied history filter', body, '->', history.length, 'records')
        return history
      } catch (err) {
        console.error('apply_history_filter failed:', err.response ? err.response.data : err.message)
        throw err
      }
  },
    async edit_employee({ dispatch }, payload){
      // expect payload to include id
      await axios.patch(`${API_URL}/api/edit-employee/employee/edit/${payload.id}`, payload)
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
