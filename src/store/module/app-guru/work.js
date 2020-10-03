import axios from 'axios'
import auth from '../../../AuthGuru'
const config = {
    headers: {Authorization: auth.getToken()}
} 

const state = () => ({
    items: [],
    info: '',
    listWork: '',
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
    SET_INFO_BY_ID_TUGAS(state, data){
        state.listWork = data
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
        const item = await axios.get('/api/app-guru/work/all.php', config);
        await commit('SET_ALL', item.data.records)
        await commit('SET_ISLOADING', false, { root: true })
    },
    async REFRESH_GET_ALL({ commit }){
        const item = await axios.get('/api/app-guru/work/all.php', config);
        await commit('SET_ALL', item.data.records)
    },
    async CREATE({ commit, dispatch }, data){
        await commit('SET_ISLOADING_ACTION', true, { root: true })
        await axios.post('/api/app-guru/work/create.php', {
            'id_jadwal_mapel': data.id_jadwal_mapel,
            'title': data.title,
            'description': data.description,
            'due_date': data.due_date,
            'status': 'On progress'
        }, config)
        await dispatch('GET_INFO_BY_ID_JADWAL_MAPEL', data.id_jadwal_mapel)
        await commit('SET_ISLOADING_ACTION', false, { root: true })
    },
    async SOFTDELETE_ONE({ dispatch, commit }, id){

        console.log(id);

        await commit('SET_ISLOADING_ACTION', true, { root: true })
        await axios.post('/api/app-guru/work/delete.php', {
            'id_guru': id,
        }, config)
        await dispatch('REFRESH_GET_ALL')
        await commit('SET_ISLOADING_ACTION', false, { root: true })
    },
    async GET_INFO_BY_ID_TUGAS({ commit }, id){
        await commit('SET_ISLOADING_ACTION', true, { root: true })
        const item = await axios.get('/api/app-guru/work/get_list_work.php?id_tugas=' + id, config);
        commit('SET_INFO_BY_ID_TUGAS', item.data)
        await commit('SET_ISLOADING_ACTION', false, { root: true })
    },
    async GET_INFO_BY_ID({ commit }, id){
        await commit('SET_ISLOADING', true, { root: true })
        const item = await axios.get('/api/app-guru/work/read.php?id_tugas=' + id, config);
        commit('SET_INFO_BY_ID', item.data)
        await commit('SET_ISLOADING', false, { root: true })
    },
    async UPDATE({ dispatch, commit }, data){
        await commit('SET_ISLOADING_ACTION', true, { root: true })
        await axios.post('/api/app-guru/work/update.php', {
            'id_guru': data.id_guru,
            'nip': data.nip,
            'nama_guru': data.nama_guru,
            "username" : data.username,
            "email": data.email,
            "password": data.password,
        }, config)
        await commit('SET_INFO_BY_ID', data)
        await dispatch('REFRESH_GET_ALL')
        await commit('SET_ISLOADING_ACTION', false, { root: true })
    },
    async SOFTDELETE_MULTI({ dispatch, commit }, selected){
        await commit('SET_ISLOADING_ACTION', true, { root: true })
        await axios.post('/api/app-guru/work/multi_delete.php', {
            selected
        }, config)
        await dispatch('REFRESH_GET_ALL')
        await commit('SET_ISLOADING_ACTION', false, { root: true })
    },
    async GET_ALL_TRASH({ commit }){
        await commit('SET_ISLOADING', true, { root: true })
        const item = await axios.get('/api/app-guru/work/all_trash.php', config)
        await commit('SET_ALL', item.data.records)
        await commit('SET_ISLOADING', false, { root: true })
    },
    async REFRESH_GET_ALL_TRASH({ commit }){
        const item = await axios.get('/api/app-guru/work/all_trash.php', config)
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
        await axios.post('/api/app-guru/work/restore.php', {
            'id_guru': id
        }, config)
        await dispatch('REFRESH_GET_ALL_TRASH')
        await commit('SET_ISLOADING_ACTION', false, { root: true })
    },
    async FORCEDELETE_ONE({ dispatch, commit }, id){
        await commit('SET_ISLOADING_ACTION', true, { root: true })
        await axios.post('/api/app-guru/work/force_delete.php', {
            'id_guru': id
        }, config)
        await dispatch('REFRESH_GET_ALL_TRASH')
        await commit('SET_ISLOADING_ACTION', false, { root: true })
    },
    async RESTORE_MULTI({ dispatch, commit }, selected){
        await commit('SET_ISLOADING_ACTION', true, { root: true })
        await axios.post('/api/app-guru/work/multi_restore.php', {
        selected
        }, config)
        await dispatch('REFRESH_GET_ALL_TRASH')
        await commit('SET_ISLOADING_ACTION', false, { root: true })
    },
    async FORCEDELETE_MULTI({ dispatch, commit }, selected){
        await commit('SET_ISLOADING_ACTION', true, { root: true })
        await axios.post('/api/app-guru/work/multi_force_delete.php', {
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