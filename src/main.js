import Vue from 'vue'
import BootstrapVue from "bootstrap-vue"
import 'bootstrap'
import "bootstrap/dist/css/bootstrap.min.css"
import "bootstrap-vue/dist/bootstrap-vue.css"
import Vuelidate from 'vuelidate'

import auth from './Auth'
import App from './App.vue'
import store from './store'

import Labs from './pages/Labs.vue'
import Kelas from './pages/Kelas.vue'
import Komputer from './pages/Komputer.vue'
import Mapel from './pages/Mapel.vue'
import Siswa from './pages/Siswa.vue'
import Billing from './pages/Billing.vue'
import Guru from './pages/Guru.vue'
import Admin from './pages/Admin.vue'
import Tugas from './pages/Tugas.vue'
import Dashboard from './pages/Dashboard.vue'

import TrashLabs from './pages/trash/TrashLabs'
import TrashKelas from './pages/trash/TrashKelas'
import TrashKomputer from './pages/trash/TrashKomputer'
import TrashSiswa from './pages/trash/TrashSiswa'
import TrashMapel from './pages/trash/TrashMapel'
import TrashBilling from './pages/trash/TrashBilling'
import TrashGuru from './pages/trash/TrashGuru'
import TrashAdmin from './pages/trash/TrashAdmin'
import TrashTugas from './pages/trash/TrashTugas'

import ViewJadwalLab from './pages/view-jadwal/ViewJadwalLab'
import ListJadwalLab from './pages/view-jadwal/ListJadwalLab'

import Login from './pages/Login'
import NotFound from './pages/404.vue';


//app-guru
import authGuru from './AuthGuru'
import Jadwal from './pages/app-guru/Jadwal.vue'
import Profile from './pages/app-guru/Profile.vue'
import InfoMapel from './pages/app-guru/InfoMapel.vue'
import InfoTugas from './pages/app-guru/InfoTugas.vue'
import LoginGuru from './pages/app-guru/Login.vue'



import VueRouter from 'vue-router'

import './assets/css/app.css'
// import './assets/app.js'

Vue.config.productionTip = false
Vue.use(VueRouter)
Vue.use(BootstrapVue)
Vue.use(Vuelidate)

import axios from 'axios'
axios.defaults.baseURL = 'http://localhost/elab'

function requireAuth (to, from, next) {
  if (!auth.loggedIn()) {
    next({
      path: '/login',
      query: { redirect: to.fullPath }

    })
  } else {
    next()
  }
}

function requireAuthGuru(to, from, next) {
  if (!authGuru.loggedIn()) {
    next({
      path: '/app-teacher/login',
      query: { redirect: to.fullPath }

    })
  } else {
    next()
  } 
}

const router = new VueRouter({

  routes: [
      {
        path: '/',
        name: 'dashboard',
        component: Dashboard,
        beforeEnter: requireAuth
      },
      {
        path: '/labs',
        name: 'labs',
        component: Labs,
        beforeEnter: requireAuth
      },
      {
        path: '/class',
        name: 'class',
        component: Kelas,
        beforeEnter: requireAuth
      },
      {
        path: '/computers',
        name: 'computers',
        component: Komputer,
        beforeEnter: requireAuth
      },
      {
        path: '/subjects',
        name: 'subjects',
        component: Mapel,
        beforeEnter: requireAuth
      },
      {
        path: '/students',
        name: 'students',
        component: Siswa,
        beforeEnter: requireAuth
      },
      {
        path: '/billings',
        name: 'billings',
        component: Billing,
        beforeEnter: requireAuth
      },
      {
        path: '/teachers',
        name: 'teachers',
        component: Guru,
        beforeEnter: requireAuth
      },
      {
        path: '/admins',
        name: 'admins',
        component: Admin,
        beforeEnter: requireAuth
      },
      {
        path: '/tasks',
        name: 'tasks',
        component: Tugas,
        beforeEnter: requireAuth
      },
      {
        path: '/trash-labs',
        name: 'trash-labs',
        component: TrashLabs,
        beforeEnter: requireAuth
      },
      {
        path: '/trash-class',
        name: 'trash-class',
        component: TrashKelas,
        beforeEnter: requireAuth
      },
      {
        path: '/trash-computers',
        name: 'trash-computers',
        component: TrashKomputer,
        beforeEnter: requireAuth
      },
      {
        path: '/trash-subjects',
        name: 'trash-subjects',
        component: TrashMapel,
        beforeEnter: requireAuth
      },
      {
        path: '/trash-students',
        name: 'trash-students',
        component: TrashSiswa,
        beforeEnter: requireAuth
      },
      {
        path: '/trash-billings',
        name: 'trash-billings',
        component: TrashBilling,
        beforeEnter: requireAuth
      },
      {
        path: '/trash-teachers',
        name: 'trash-teachers',
        component: TrashGuru,
        beforeEnter: requireAuth
      },
      {
        path: '/trash-admins',
        name: 'trash-admins',
        component: TrashAdmin,
        beforeEnter: requireAuth
      },
      {
        path: '/trash-tasks',
        name: 'trash-tasks',
        component: TrashTugas,
        beforeEnter: requireAuth
      },
      {
        path: '/list-schedule/',
        name: 'list-schedule',
        component: ListJadwalLab,
        beforeEnter: requireAuth
      },
      {
        path: '/view-schedule/:id',
        name: 'view-schedule',
        component: ViewJadwalLab,
        beforeEnter: requireAuth
      },
      {
        path: '/login',
        name: 'login',
        component: Login,
      },
      { 
        path: '/logout',
        beforeEnter (to, from, next) {
            auth.logout()
            next('/login')
        },
        name: 'logout'
      },
      
      //app-guru
      
      {
        path: '/app-teacher/login',
        name: 'login-guru',
        component: LoginGuru,
      },
      { 
        path: '/app-teacher/logout-teacher',
        beforeEnter (to, from, next) {
          authGuru.logout()
            next('/app-teacher/login')
        },
        name: 'logout-teacher'
      },
      {
        path: '/app-teacher/schedule',
        name: 'schedule-teacher',
        component: Jadwal,
        beforeEnter: requireAuthGuru
      },
      {
        path: '/app-teacher/profile',
        name: 'profile-teacher',
        component: Profile,
        beforeEnter: requireAuthGuru
      },
      {
        path: '/app-teacher/info-mapel/:id',
        name: 'info-mapel',
        component: InfoMapel,
        beforeEnter: requireAuthGuru
      },
      {
        path: '/app-teacher/info-task/:id_tugas',
        name: 'info-task',
        component: InfoTugas,
        beforeEnter: requireAuthGuru
      },
      { 
        path: '*', 
        name: '404',
        component: NotFound 
      },
  ]
});

new Vue({ 
  render: h => h(App),
  router,
  store
}).$mount('#app')
