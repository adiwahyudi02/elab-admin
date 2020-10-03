import axios from 'axios'

import "vue-smooth-dnd";
import { applyDrag } from '../../utils/helpers'

import auth from '../../Auth'
const config = {
    headers: {Authorization: auth.getToken()}
} 

const state = () => ({
    items: {},
    info: '',
    info_for_bentrok_mapel: '',
    multi_info: '',
    trash: {
        items: [],
    },
    bentrok: {
        conditionCekLintasJadwalBentrok: true,
        data: '',
        columnId: {}
    }

})

const getters = {
    getItemById: (state) => (id) => {
        return state.items.find(item => item.id == id);
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

        data.jadwal_hari.filter(item => {
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
        const jh = monday.concat(tuesday, wednesday, thursday, friday, saturday, sunday);
        
        state.items = {
            'id_lab': data.id_lab,
            'nama_lab': data.nama_lab,
            'jadwal_hari': jh
        }
    },
    SET_INFO_BY_ID(state, data){
        state.info = data
    },
    SET_INFO_BY_ID_FOR_BENTROK_MAPEL(state, data){
        state.info_for_bentrok_mapel = data
    },
    SET_MULTI_INFO(state, data){
        state.multi_info = data
    },
    SET_ALL_TRASH(state, data){
        state.trash.items = data
    },
    SET_ON_DROP_CARD(state, {columnId, dropResult}){

        if (dropResult.removedIndex !== null || dropResult.addedIndex !== null) {
            const jadwals = state.items
            const column = jadwals.jadwal_hari.filter(p => p.id === columnId)[0]
            const columnIndex = jadwals.jadwal_hari.indexOf(column)

            const newColumn = column
            newColumn.jadwal_lab = applyDrag(newColumn.jadwal_lab, dropResult, columnId)
            jadwals.jadwal_hari.splice(columnIndex, 1, newColumn)

            if (dropResult.payload.id_hari != columnId) {

                var bentrok = newColumn.jadwal_lab.filter(jl =>{
                    return dropResult.payload.jam_selesai > jl.jam_mulai && dropResult.payload.jam_mulai < jl.jam_selesai && dropResult.payload.jam_mulai != jl.jam_selesai
                })


                if (bentrok.length == 1) {
                    if (bentrok[0].id == dropResult.payload.id) {
                        state.bentrok.conditionCekLintasJadwalBentrok = true
                    }else{
                        state.bentrok.conditionCekLintasJadwalBentrok = false
                    }
                    
                }else if(bentrok.length > 1){
                    state.bentrok.conditionCekLintasJadwalBentrok = false
                }
                else{
                    state.bentrok.conditionCekLintasJadwalBentrok = true
                }

                if (!state.bentrok.conditionCekLintasJadwalBentrok) {
                    state.bentrok.data = dropResult.payload

                    state.bentrok.columnId = state.items.jadwal_hari.find(e => {
                        return e.id == columnId
                    })
                }
            }

            axios.post('/api/view-jadwal/move-kelas.php', { //ddcard
                columnId: columnId,
                result: dropResult,
                data: newColumn.jadwal_lab
            }, config)
            

            // if (dropResult.payload.id_hari != columnId) {
            //     jadwals.jadwal_hari.forEach(jp => {
            //         jp.jadwal_lab.forEach(i => {
            //             if (i.id == dropResult.payload.id) {
            //                 i.id_hari = columnId
            //             }
            //         });
            //     });    
            // }

            
            // newColumn.jadwal_lab.sort(function (a, b) {
            //     return a.jam_mulai.localeCompare(b.jam_mulai);
            // });
            
        }
    },
    SET_IF_BENTROK_JADWAL_MAPEL(state, data){

        var bentrok = state.info_for_bentrok_mapel.jadwal_mapel.filter(jm =>{
            return data.jam_selesai > jm.jam_mulai && data.jam_mulai < jm.jam_selesai && data.jam_mulai != jm.jam_selesai
        })

        if (bentrok.length == 1) {
            if (bentrok[0].id == data.id) {
                state.bentrok.conditionCekLintasJadwalBentrok = true
            }else{
                state.bentrok.conditionCekLintasJadwalBentrok = false
            }
            
        }else if(bentrok.length > 1){
            state.bentrok.conditionCekLintasJadwalBentrok = false
        }
        else{
            state.bentrok.conditionCekLintasJadwalBentrok = true
        }

        if (!state.bentrok.conditionCekLintasJadwalBentrok) {
            state.bentrok.data = data
            state.bentrok.columnId = state.info_for_bentrok_mapel.jadwal_lab
        }
    },
    
    SET_SWITCH_CARD(state ,data){

        let data_1 = data[0].card
        let data_2 = data[1].card

        console.log('dt_1', data_1);
        console.log('dt_2', data_2);


        console.log('di_1', data_1.id_kelas);
        console.log('di_2', data_2.id_kelas);

        

        let id_kelas_1 = data_1.id_kelas
        let id_kelas_2 = data_2.id_kelas
        data_1.id_kelas = id_kelas_2
        data_2.id_kelas = id_kelas_1

        

        let dokter_user_1 = data_1.nama_kelas
        let dokter_user_2 = data_2.nama_kelas
        data_1.nama_kelas = dokter_user_2
        data_2.nama_kelas = dokter_user_1
    },

    SET_SWITCH_CARD_MAPEL(state, data){
        let data_1 = data[0].card
        let data_2 = data[1].card

        console.log('dt_1', data_1);
        console.log('dt_2', data_2);


        console.log('di_1', data_1.id_mapel);
        console.log('di_2', data_2.id_mapel);

        let id_mapel_1 = data_1.id_mapel
        let id_mapel_2 = data_2.id_mapel
        data_1.id_mapel = id_mapel_2
        data_2.id_mapel = id_mapel_1

        let nama_mapel_1 = data_1.nama_mapel
        let nama_mapel_2 = data_2.nama_mapel
        data_1.nama_mapel = nama_mapel_2
        data_2.nama_mapel = nama_mapel_1

        let id_guru_1 = data_1.id_guru
        let id_guru_2 = data_2.id_guru
        data_1.id_guru = id_guru_2
        data_2.id_guru = id_guru_1

        let nama_guru_1 = data_1.nama_guru
        let nama_guru_2 = data_2.nama_guru
        data_1.nama_guru = nama_guru_2
        data_2.nama_guru = nama_guru_1
    }
}

const actions = {
    async GET_ALL({ commit }, id){
        await commit('SET_ISLOADING', true, { root: true })
        const item = await axios.get('/api/view-jadwal/read.php?id_lab=' + id, config)
        await commit('SET_ALL', item.data)
        await commit('SET_ISLOADING', false, { root: true })
    },
    async REFRESH_GET_ALL({ commit }, id){
        console.log('asdfadsfsdafsdsaasfds');
        const item = await axios.get('/api/view-jadwal/read.php?id_lab=' + id, config)
        await commit('SET_ALL', item.data)
    },
    async CREATE_JADWAL_HARI({ commit, dispatch }, data){
        await commit('SET_ISLOADING_ACTION', true, { root: true })
        await axios.post('/api/hari/create.php', {
            'id_lab': data.id_lab,
            'hari': data.hari
        }, config)
        await dispatch('REFRESH_GET_ALL', data.id_lab)
        await commit('SET_ISLOADING_ACTION', false, { root: true })
    },

    async CREATE_JADWAL_KELAS({ dispatch, commit }, data){
        await commit('SET_ISLOADING_ACTION', true, { root: true })
        await axios.post('/api/jadwal_lab/create.php', {
            'id_hari': data.form.id_hari,
            'id_kelas': data.form.id_kelas,
            'jam_mulai': data.form.jam_mulai,
            'jam_selesai': data.form.jam_selesai
        }, config)
        await dispatch('REFRESH_GET_ALL', data.id)
        await commit('SET_ISLOADING_ACTION', false, { root: true })
    },

    async CREATE_JADWAL_MAPEL({ dispatch, commit }, data){
        await commit('SET_ISLOADING_ACTION', true, { root: true })
        console.log('form', data.form);
        await axios.post('/api/jadwal_mapel/create.php', {
            'id_jadwal_lab': data.form.id_jadwal_lab,
            'id_kelas': data.form.id_kelas,
            'id_mapel': data.form.id_mapel,
            'id_guru': data.form.id_guru,
            'jam_mulai': data.form.jam_mulai,
            'jam_selesai': data.form.jam_selesai
        }, config)
        await dispatch('REFRESH_GET_ALL', data.id)
        await dispatch('GET_INFO_JADWAL_KELAS_BY_ID', data.form.id_jadwal_lab)
        await commit('SET_ISLOADING_ACTION', false, { root: true })
    },

    async ON_CARD_DROP({ commit, dispatch }, { columnId, dropResult, id}){
        // console.log('dr', dropResult);
        await commit('SET_ON_DROP_CARD', {columnId, dropResult})
        await dispatch('REFRESH_GET_ALL', id)
        // await dispatch('GET_INFO_JADWAL_KELAS_BY_ID', dropResult.payload.id)
    },
    async SWITCH_KELAS({commit}, data){
        await commit('SET_ISLOADING_ACTION', true, { root: true })
        await commit('SET_SWITCH_CARD', data)
        await axios.put('/api/view-jadwal/switch-kelas.php', {
            'data': data
        }, config)
        await commit('SET_ISLOADING_ACTION', false, { root: true })
    },

    async SWITCH_MAPEL({commit}, data){
        await commit('SET_ISLOADING_ACTION', true, { root: true })
        await commit('SET_SWITCH_CARD_MAPEL', data)
        await axios.put('/api/view-jadwal/switch-mapel.php', {
            'data': data
        }, config)
        await commit('SET_ISLOADING_ACTION', false, { root: true })
    },

    async GET_INFO_JADWAL_KELAS_BY_ID({ state, commit }, id){
        
        await commit('SET_ISLOADING_ACTION', true, { root: true })

        var cc = {}

        state.items.jadwal_hari.forEach(jp => {
            jp.jadwal_lab.forEach(i => {
                if (i.id == id) {
                    cc = {
                        'jadwal_lab': {
                            'id': i.id,
                            'id_hari': i.id_hari,
                            'hari': i.hari,
                            'id_kelas': i.id_kelas,
                            'nama_kelas': i.nama_kelas,
                            'jam_mulai': i.jam_mulai,
                            'jam_selesai': i.jam_selesai,
                            'created_at': i.created_at,
                            'updated_at': i.updated_at
                        },
                        'jadwal_mapel': i.jadwal_mapel
                    }
                }
            });

        });
        
        commit('SET_INFO_BY_ID', cc)

        await commit('SET_ISLOADING_ACTION', false, { root: true })
    },

    async GET_INFO_JADWAL_KELAS_BY_ID_FOR_BENTROK_MAPEL({ state, commit }, id){
        
        await commit('SET_ISLOADING_ACTION', true, { root: true })

        var cc = {}

        state.items.jadwal_hari.forEach(jp => {
            jp.jadwal_lab.forEach(i => {
                if (i.id == id) {
                    cc = {
                        'jadwal_lab': {
                            'id': i.id,
                            'id_hari': i.id_hari,
                            'hari': i.hari,
                            'id_kelas': i.id_kelas,
                            'nama_kelas': i.nama_kelas,
                            'jam_mulai': i.jam_mulai,
                            'jam_selesai': i.jam_selesai,
                            'created_at': i.created_at,
                            'updated_at': i.updated_at
                        },
                        'jadwal_mapel': i.jadwal_mapel
                    }
                }
            });

        });
        
        commit('SET_INFO_BY_ID_FOR_BENTROK_MAPEL', cc)

        await commit('SET_ISLOADING_ACTION', false, { root: true })
    },

    async UPDATE_JADWAL_KELAS({ dispatch, commit }, data){
        await commit('SET_ISLOADING_ACTION', true, { root: true })
        await axios.post('/api/jadwal_lab/update.php', {
            'id_jadwal_lab': data.form.jadwal_lab.id,
            'id_hari': data.form.jadwal_lab.id_hari,
            'id_kelas': data.form.jadwal_lab.id_kelas,
            'jam_mulai': data.form.jadwal_lab.jam_mulai,
            'jam_selesai': data.form.jadwal_lab.jam_selesai
        }, config)
        console.log('data.form',data.form);
        // await commit('SET_INFO_BY_ID', data.form)
        await dispatch('REFRESH_GET_ALL', data.id)
        await dispatch('GET_INFO_JADWAL_KELAS_BY_ID', data.form.jadwal_lab.id)
        await commit('SET_ISLOADING_ACTION', false, { root: true })
    },

    async UPDATE_JADWAL_MAPEL({ dispatch, commit }, data){
        await commit('SET_ISLOADING_ACTION', true, { root: true })
        await axios.post('/api/jadwal_mapel/update.php', {
            'id_jadwal_mapel': data.form.id_jadwal_mapel,
            'id_jadwal_lab': data.form.id_jadwal_lab,
            'id_kelas': data.form.id_kelas,
            'id_mapel': data.form.id_mapel,
            'id_guru': data.form.id_guru,
            'jam_mulai': data.form.jam_mulai,
            'jam_selesai': data.form.jam_selesai
        }, config)
        await dispatch('REFRESH_GET_ALL', data.id)
        await dispatch('GET_INFO_JADWAL_KELAS_BY_ID', data.form.current_id_jadwal_lab)
        await dispatch('GET_INFO_JADWAL_KELAS_BY_ID_FOR_BENTROK_MAPEL', data.form.id_jadwal_lab)
        await commit('SET_IF_BENTROK_JADWAL_MAPEL', data.form)
        await commit('SET_ISLOADING_ACTION', false, { root: true })
    },

    async SOFTDELETE_ONE_JADWAL_HARI({ dispatch, commit }, {id, id_lab}){
        await commit('SET_ISLOADING_ACTION', true, { root: true })
        await axios.post('/api/hari/delete.php', {
            'id_hari': id
        }, config)
        await dispatch('REFRESH_GET_ALL', id_lab)
        await commit('SET_ISLOADING_ACTION', false, { root: true })
    },

    async SOFTDELETE_ONE_JADWAL_KELAS({ dispatch, commit }, {id, id_lab}){
        await commit('SET_ISLOADING_ACTION', true, { root: true })
        await axios.post('/api/jadwal_lab/delete.php', {
            'id_jadwal_lab': id
        }, config)
        await dispatch('REFRESH_GET_ALL', id_lab)
        await commit('SET_ISLOADING_ACTION', false, { root: true })
    },

    async SOFTDELETE_ONE_JADWAL_MAPEL({ dispatch, commit }, {id, id_lab, id_jadwal_lab}){
        await commit('SET_ISLOADING_ACTION', true, { root: true })
        await axios.post('/api/jadwal_mapel/delete.php', {
            'id_jadwal_mapel': id
        }, config)
        await dispatch('REFRESH_GET_ALL', id_lab)
        await dispatch('GET_INFO_JADWAL_KELAS_BY_ID', id_jadwal_lab)
        await commit('SET_ISLOADING_ACTION', false, { root: true })
    },

    
    async UPDATE({ dispatch, commit }, data){
        await commit('SET_ISLOADING_ACTION', true, { root: true })
        await axios.put('api/poli/' + data.id, {
            'nama_poli': data.nama_poli,
            'kode_antri': data.kode_antri
        }, config)
        await commit('SET_INFO_BY_ID', data)
        await dispatch('REFRESH_GET_ALL')
        await commit('SET_ISLOADING_ACTION', false, { root: true })
    },
    
    async SOFTDELETE_MULTI({ dispatch, commit }, selected){
        await commit('SET_ISLOADING_ACTION', true, { root: true })
        await axios.post('api/multi-softdelete-poli', {
            selected
        }, config)
        await dispatch('REFRESH_GET_ALL')
        await commit('SET_ISLOADING_ACTION', false, { root: true })
    },
    async GET_ALL_TRASH({ commit }){
        await commit('SET_ISLOADING', true, { root: true })
        const item = await axios.get('/api/trashed-polis', config)
        await commit('SET_ALL_TRASH', item.data.trashed)
        await commit('SET_ISLOADING', false, { root: true })
    },
    async REFRESH_GET_ALL_TRASH({ commit }){
        const item = await axios.get('/api/trashed-polis', config)
        await commit('SET_ALL_TRASH', item.data.trashed)
    },
    async GET_INFO_TRASH_BY_ID({ state, commit }, id){
        await commit('SET_ISLOADING_ACTION', true, { root: true })
        commit('SET_INFO_BY_ID', await state.trash.items.find(item => {
            return item.id === id
        }))
        await commit('SET_ISLOADING_ACTION', false, { root: true })
    },
    async RESTORE_ONE({ dispatch, commit }, id){
        await commit('SET_ISLOADING_ACTION', true, { root: true })
        await axios.get('/api/restore-poli/' + id, config)
        await dispatch('REFRESH_GET_ALL_TRASH')
        await commit('SET_ISLOADING_ACTION', false, { root: true })
    },
    async FORCEDELETE_ONE({ dispatch, commit }, id){
        await commit('SET_ISLOADING_ACTION', true, { root: true })
        await axios.get('/api/force-delete-poli/' + id, config)
        await dispatch('REFRESH_GET_ALL_TRASH')
        await commit('SET_ISLOADING_ACTION', false, { root: true })
    },
    async RESTORE_MULTI({ dispatch, commit }, selected){
        await commit('SET_ISLOADING_ACTION', true, { root: true })
        await axios.post('api/multi-restore-poli', {
        selected
        }, config)
        await dispatch('REFRESH_GET_ALL_TRASH')
        await commit('SET_ISLOADING_ACTION', false, { root: true })
    },
    async FORCEDELETE_MULTI({ dispatch, commit }, selected){
        await commit('SET_ISLOADING_ACTION', true, { root: true })
        await axios.post('api/multi-force-delete-poli', {
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