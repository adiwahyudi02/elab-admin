import axios from 'axios'
import auth from '../../Auth'
const config = {
    headers: {Authorization: auth.getToken()}
} 

const state = () => ({
    items: [],
    info: '',
    multi_info: '',
    trash: {
        items: [],
    }
})

const getters = {
    getItemById: (state) => (id) => {
        var result = state.items.find(item => {
            return item.id == id
        })
        return result
    },
    count: (state) => {
        return state.items.length
    },
    trashCount: (state) => {
        return state.trash.items.length
    },
}

const mutations = {
    SET_ALL(state, data){
        state.items = data
    },
    SET_INFO_BY_ID(state, data){
        state.info = data
    },
    SET_MULTI_INFO(state, data){
        state.multi_info = data
    },
    SET_ALL_TRASH(state, data){
        state.trash.items = data
    }
}

const actions = {
    async GET_ALL({ commit }){
        await commit('SET_ISLOADING', true, { root: true })
        const item = await axios.get('/api/komputer/all.php', config);
        await commit('SET_ALL', item.data.records)
        await commit('SET_ISLOADING', false, { root: true })
    },
    async REFRESH_GET_ALL({ commit }){
        const item = await axios.get('/api/komputer/all.php', config);
        await commit('SET_ALL', item.data.records)
    },
    async CREATE({ commit, dispatch }, data){
        await commit('SET_ISLOADING_ACTION', true, { root: true })
        await axios.post('/api/komputer/create.php', {
            'nama_komputer': data.nama_komputer,
            'ip_address': data.ip_address,
            'spesifikasi': data.spesifikasi,
            'id_lab': data.id_lab,
        }, config)
        await dispatch('REFRESH_GET_ALL')
        await commit('SET_ISLOADING_ACTION', false, { root: true })
    },
    async SOFTDELETE_ONE({ dispatch, commit }, id){

        console.log(id);

        await commit('SET_ISLOADING_ACTION', true, { root: true })
        await axios.post('/api/komputer/delete.php', {
            'id_komputer': id,
        }, config)
        await dispatch('REFRESH_GET_ALL')
        await commit('SET_ISLOADING_ACTION', false, { root: true })
    },
    async GET_INFO_BY_ID({ state, commit }, id){
        await commit('SET_ISLOADING_ACTION', true, { root: true })
        commit('SET_INFO_BY_ID', await state.items.find(item => {
            return item.id_komputer === id
        }))
        await commit('SET_ISLOADING_ACTION', false, { root: true })
    },
    async UPDATE({ dispatch, commit }, data){
        await commit('SET_ISLOADING_ACTION', true, { root: true })
        await axios.post('/api/komputer/update.php', {
            'id_komputer': data.id_komputer,
            'nama_komputer': data.nama_komputer,
            'ip_address': data.ip_address,
            'spesifikasi': data.spesifikasi,
            'id_lab': data.id_lab
        }, config)
        await commit('SET_INFO_BY_ID', data)
        await dispatch('REFRESH_GET_ALL')
        await commit('SET_ISLOADING_ACTION', false, { root: true })
    },
    async SOFTDELETE_MULTI({ dispatch, commit }, selected){
        await commit('SET_ISLOADING_ACTION', true, { root: true })
        await axios.post('/api/komputer/multi_delete.php', {
            selected
        }, config)
        await dispatch('REFRESH_GET_ALL')
        await commit('SET_ISLOADING_ACTION', false, { root: true })
    },
    async GET_ALL_TRASH({ commit }){
        await commit('SET_ISLOADING', true, { root: true })
        const item = await axios.get('/api/komputer/all_trash.php', config)
        await commit('SET_ALL', item.data.records)
        await commit('SET_ISLOADING', false, { root: true })
    },
    async REFRESH_GET_ALL_TRASH({ commit }){
        const item = await axios.get('/api/komputer/all_trash.php', config)
        await commit('SET_ALL', item.data.records)
    },
    // async GET_INFO_TRASH_BY_ID({ state, commit }, id){
    //     await commit('SET_ISLOADING_ACTION', true, { root: true })
    //     commit('SET_INFO_BY_ID', await state.trash.items.find(item => {
    //         return item.id === id
    //     }))
    //     await commit('SET_ISLOADING_ACTION', false, { root: true })
    // },
    async RESTORE_ONE({ dispatch, commit }, id){
        await commit('SET_ISLOADING_ACTION', true, { root: true })
        await axios.post('/api/komputer/restore.php', {
            'id_komputer': id
        }, config)
        await dispatch('REFRESH_GET_ALL_TRASH')
        await commit('SET_ISLOADING_ACTION', false, { root: true })
    },
    async FORCEDELETE_ONE({ dispatch, commit }, id){
        await commit('SET_ISLOADING_ACTION', true, { root: true })
        await axios.post('/api/komputer/force_delete.php', {
            'id_komputer': id
        }, config)
        await dispatch('REFRESH_GET_ALL_TRASH')
        await commit('SET_ISLOADING_ACTION', false, { root: true })
    },
    async RESTORE_MULTI({ dispatch, commit }, selected){
        await commit('SET_ISLOADING_ACTION', true, { root: true })
        await axios.post('/api/komputer/multi_restore.php', {
        selected
        }, config)
        await dispatch('REFRESH_GET_ALL_TRASH')
        await commit('SET_ISLOADING_ACTION', false, { root: true })
    },
    async FORCEDELETE_MULTI({ dispatch, commit }, selected){
        await commit('SET_ISLOADING_ACTION', true, { root: true })
        await axios.post('/api/komputer/multi_force_delete.php', {
            selected
        }, config)
        await dispatch('REFRESH_GET_ALL_TRASH')
        await commit('SET_ISLOADING_ACTION', false, { root: true })
    }
}

export default {
    namespaced: true,
    state,
    actions,
    mutations,
    getters
}