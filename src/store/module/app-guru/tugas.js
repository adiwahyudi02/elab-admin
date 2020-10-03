import axios from 'axios'
import auth from '../../../AuthGuru'
const config = {
    headers: {Authorization: auth.getToken()}
} 

const state = () => ({
    items: [],
    info: '',
    listTugas: '',
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
    SET_INFO_BY_ID_JADWAL_MAPEL(state, data){
        state.listTugas = data
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
        const item = await axios.get('/api/app-guru/tugas/all.php', config);
        await commit('SET_ALL', item.data.records)
        await commit('SET_ISLOADING', false, { root: true })
    },
    async REFRESH_GET_ALL({ commit }){
        const item = await axios.get('/api/app-guru/tugas/all.php', config);
        await commit('SET_ALL', item.data.records)
    },
    async CREATE({ commit, dispatch }, {data, id_jadwal_mapel}){
        await commit('SET_ISLOADING_ACTION', true, { root: true })
        await axios.post( '/api/app-guru/tugas/create.php',
        data,
        {
            headers: {
                'Content-Type': 'multipart/form-data',
                'Authorization': auth.getToken()
            }
        })
        await dispatch('GET_INFO_BY_ID_JADWAL_MAPEL', id_jadwal_mapel)
        await commit('SET_ISLOADING_ACTION', false, { root: true })
    },
    async CREATE_FILE_TUGAS({ commit, dispatch }, { data, id_tugas }){
        await commit('SET_ISLOADING_ACTION', true, { root: true })
        await axios.post('/api/app-guru/tugas/create_attachment.php', data,{
            headers: {
                'Content-Type':  'multipart/form-data;boundary=----WebKitFormBoundaryyrV7KO0BoCBuDbTL',
                'Authorization': auth.getToken()
            }
        })
        await dispatch('GET_FETCH_INFO_BY_ID', id_tugas)
        await commit('SET_ISLOADING_ACTION', false, { root: true })
    },
    async SOFTDELETE_FILE({ commit, dispatch }, { id_attachment, id_tugas }){
        await commit('SET_ISLOADING_ACTION', true, { root: true })
        await axios.post('/api/app-guru/attachment_tugas/delete.php', {
            'id_attachment': id_attachment,
        }, config)
        await dispatch('GET_FETCH_INFO_BY_ID', id_tugas)
        await commit('SET_ISLOADING_ACTION', false, { root: true })
    },
    async SOFTDELETE_ONE({ commit, dispatch }, id){

        await commit('SET_ISLOADING_ACTION', true, { root: true })
        await axios.post('/api/app-guru/tugas/delete.php', {
            'id_tugas': id,
        }, config)
        await dispatch('REFRESH_GET_ALL')
        await commit('SET_ISLOADING_ACTION', false, { root: true })
    },
    async GET_INFO_BY_ID_JADWAL_MAPEL({ commit }, id){
        await commit('SET_ISLOADING', true, { root: true })
        const item = await axios.get('/api/app-guru/tugas/get_list_tugas.php?id_jadwal_mapel=' + id, config);
        commit('SET_INFO_BY_ID_JADWAL_MAPEL', item.data)
        await commit('SET_ISLOADING', false, { root: true })
    },
    // async GET_INFO_BY_ID({ commit }, id){
    //     await commit('SET_ISLOADING', true, { root: true })
    //     const item = await axios.get('/api/app-guru/tugas/read.php?id_tugas=' + id, config);
    //     commit('SET_INFO_BY_ID', item.data)
    //     await commit('SET_ISLOADING', false, { root: true })
    // },
    async GET_INFO_BY_ID({ state, commit }, id){
        console.log('id', id);
        await commit('SET_ISLOADING_ACTION', true, { root: true })
        commit('SET_INFO_BY_ID', await state.items.find(item => {
            return item.id_tugas === id
        }))
        await commit('SET_ISLOADING_ACTION', false, { root: true })
    },
    async GET_FETCH_INFO_BY_ID({ commit }, id){
        await commit('SET_ISLOADING', true, { root: true })
        const item = await axios.get('/api/app-guru/tugas/read.php?id_tugas=' + id, config);
        commit('SET_INFO_BY_ID', item.data)
        await commit('SET_ISLOADING', false, { root: true })
    },
    async UPDATE({ dispatch, commit }, data){
        await commit('SET_ISLOADING_ACTION', true, { root: true })
        await axios.post('/api/app-guru/tugas/update.php', {
            'id_tugas': data.id_tugas,
            'id_jadwal_mapel': data.id_jadwal_mapel,
            'title': data.title,
            "description" : data.description,
            "due_date": data.due_date,
            "status": data.status
        }, config)
        await commit('SET_INFO_BY_ID', data)
        await dispatch('GET_FETCH_INFO_BY_ID', data.id_tugas)
        await commit('SET_ISLOADING_ACTION', false, { root: true })
    },
    async SOFTDELETE_MULTI({ dispatch, commit }, selected){
        await commit('SET_ISLOADING_ACTION', true, { root: true })
        await axios.post('/api/app-guru/tugas/multi_delete.php', {
            selected
        }, config)
        await dispatch('REFRESH_GET_ALL')
        await commit('SET_ISLOADING_ACTION', false, { root: true })
    },
    async GET_ALL_TRASH({ commit }){
        await commit('SET_ISLOADING', true, { root: true })
        const item = await axios.get('/api/app-guru/tugas/all_trash.php', config)
        await commit('SET_ALL', item.data.records)
        await commit('SET_ISLOADING', false, { root: true })
    },
    async REFRESH_GET_ALL_TRASH({ commit }){
        const item = await axios.get('/api/app-guru/tugas/all_trash.php', config)
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
        await axios.post('/api/app-guru/tugas/restore.php', {
            'id_tugas': id
        }, config)
        await dispatch('REFRESH_GET_ALL_TRASH')
        await commit('SET_ISLOADING_ACTION', false, { root: true })
    },
    async FORCEDELETE_ONE({ dispatch, commit }, id){
        await commit('SET_ISLOADING_ACTION', true, { root: true })
        await axios.post('/api/app-guru/tugas/force_delete.php', {
            'id_tugas': id
        }, config)
        await dispatch('REFRESH_GET_ALL_TRASH')
        await commit('SET_ISLOADING_ACTION', false, { root: true })
    },
    async RESTORE_MULTI({ dispatch, commit }, selected){
        await commit('SET_ISLOADING_ACTION', true, { root: true })
        await axios.post('/api/app-guru/tugas/multi_restore.php', {
        selected
        }, config)
        await dispatch('REFRESH_GET_ALL_TRASH')
        await commit('SET_ISLOADING_ACTION', false, { root: true })
    },
    async FORCEDELETE_MULTI({ dispatch, commit }, selected){
        await commit('SET_ISLOADING_ACTION', true, { root: true })
        await axios.post('/api/app-guru/tugas/multi_force_delete.php', {
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