import axios from 'axios'
import auth from '../../../AuthGuru'
const config = {
    headers: {Authorization: auth.getToken()}
} 

const state = () => ({
    items: [],
    info: ''
})

const getters = {
    getItemById: (state) => (id) => {
        var result = state.items.find(item => {
            return item.id == id
        })
        return result
    },
    getTodaySchedule(state){
        var d = new Date();
        var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        let a = state.items.find(item => {
            return days[d.getDay()] == item.hari
        })
        return a
    }
}

const mutations = {
    SET_ALL(state, data){
        var monday = []
        var tuesday = []
        var wednesday = []
        var thursday = []
        var friday = []
        var saturday = []
        var sunday = []
        data.filter(item => {
            if (item.hari == 'Monday') {
                monday.push(item)
            }else if (item.hari == 'Tuesday') {
                tuesday.push(item)
            }else if (item.hari == 'Wednesday') {
                wednesday.push(item)
            }else if (item.hari == 'Thursday') {
                thursday.push(item)
            }else if (item.hari == 'Friday') {
                friday.push(item)
            }else if (item.hari == 'Saturday') {
                saturday.push(item)
            }else if (item.hari == 'Sunday') {
                sunday.push(item)
            }
        })
        const rs = [
            {
                'hari': 'Monday',
                'data': monday
            },
            {
                'hari': 'Tuesday',
                'data': tuesday
            },
            {
                'hari': 'Wednesday',
                'data': wednesday
            }, 
            {
                'hari': 'Thursday',
                'data': thursday
            }, 
            {
                'hari': 'Friday',
                'data': friday
            }, 
            {
                'hari': 'Saturday',
                'data': saturday
            }, 
            {
                'hari': 'Sunday',
                'data': sunday
            },
        ]
        state.items = rs
    },
    SET_INFO_BY_ID(state, data){
        state.info = data
    }
}

const actions = {
    async GET_ALL({ commit }){
        await commit('SET_ISLOADING', true, { root: true })
        let auth = JSON.parse(localStorage.getItem('auth'));
        const item = await axios.get('/api/app-guru/jadwal/read.php?id_guru=' + auth.data.id, config);
        await commit('SET_ALL', item.data.records)
        await commit('SET_ISLOADING', false, { root: true })
    },
    async REFRESH_GET_ALL({ commit }){
        let auth = JSON.parse(localStorage.getItem('auth'));
        const item = await axios.get('/api/app-guru/jadwal/read.php?id_guru=' + auth.data.id, config);
        await commit('SET_ALL', item.data.records)
    },

    async GET_INFO_BY_ID({ state, commit }, id){
        await commit('SET_ISLOADING_ACTION', true, { root: true })
        commit('SET_INFO_BY_ID', await state.items.find(item => {
            return item.nis === id
        }))
        await commit('SET_ISLOADING_ACTION', false, { root: true })
    },
}

export default {
    namespaced: true,
    state,
    actions,
    mutations,
    getters
}