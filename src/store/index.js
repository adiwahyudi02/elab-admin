  
import Vue from 'vue'
import Vuex from 'vuex'
import lab from './module/lab'
import kelas from './module/kelas'
import komputer from './module/komputer'
import mapel from './module/mapel'
import siswa from './module/siswa'
import billing from './module/billing'
import guru from './module/guru'
import admin from './module/admin'
import view_jadwal from './module/view_jadwal'

//app-guru
import jadwal from './module/app-guru/jadwal'
import tugas from './module/app-guru/tugas'
import work from './module/app-guru/work'

Vue.use(Vuex)

const debug = process.env.NODE_ENV !== 'production'

export default new Vuex.Store({
    state: {
        isLoading: false,
        isLoadingAction: false,
        isLoadingSearch: false,
        isLoadingActionButton: false
    },
    mutations: {
        SET_ISLOADING(state, condition){
            state.isLoading = condition
        },
        SET_ISLOADING_ACTION(state, condition){
            state.isLoadingAction = condition
        },
        SET_ISLOADING_SEARCH(state, condition){
            state.isLoadingSearch = condition
        },
        SET_ISLOADING_ACTION_BUTTON(state, condition){
            state.isLoadingActionButton = condition
        }
    },
    modules: {
        lab,
        kelas,
        komputer,
        mapel,
        siswa,
        billing,
        guru,
        admin,
        view_jadwal,
        jadwal,
        tugas,
        work
    },
    strict: debug
})