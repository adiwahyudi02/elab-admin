<template>
  <Main>
    <div>
      <div>
        <h4 class="text-success ml-5 m-4">
          <b>
            Dashboard
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-activity align-middle"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
          </b>
        </h4>
      </div>
      <div v-if="isLoading" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 999">
          <b-spinner style="width: 3rem; height: 3rem;" variant="info" label="Large Spinner"></b-spinner>
      </div>
      <div v-else>
        <div class="container d-flex flex-wrap justify-content-around">
          <div class="" style="width: 45%">
            <div class="card mx-2 flex-fill" style="width: 100%">
              <div class="card-header">
                <h4 class="text-success card-title mb-0"><b> Master </b></h4>
              </div>
              <table class="table table-hover my-0">
                <thead>
                  <tr>
                    <th>Table</th>
                    <th>Total</th>
                    <th>Details</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Admins</td>
                    <td><span class="badge px-2 badge-success">{{admin.length}}</span></td>
                    <td class="d-flex justify-content-start align-items-center">
                      <div>
                        <router-link to="/admins">
                          <b-button class="btn btn-sm btn-info p-1 rounded mx-2" v-b-tooltip.hover title="Go to master">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye align-middle"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                          </b-button>  
                        </router-link>
                      </div>
                      <div>
                        <router-link to="/trash-admins">
                          <b-button class="btn btn-sm btn-warning p-1 rounded" v-b-tooltip.hover title="Go to trash">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 align-middle"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                          </b-button>
                        </router-link>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>Labs</td>
                    <td><span class="badge px-2 badge-success">{{lab.length}}</span></td>
                    <td class="d-flex justify-content-start align-items-center">
                      <div>
                        <router-link to="/labs">
                          <b-button class="btn btn-sm btn-info p-1 rounded mx-2" v-b-tooltip.hover title="Go to master">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye align-middle"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                          </b-button>  
                        </router-link>
                      </div>
                      <div>
                        <router-link to="/trash-labs">
                          <b-button class="btn btn-sm btn-warning p-1 rounded" v-b-tooltip.hover title="Go to trash">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 align-middle"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                          </b-button>
                        </router-link>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>Computers</td>
                    <td><span class="badge px-2 badge-success">{{komputer.length}}</span></td>
                    <td class="d-flex justify-content-start align-items-center">
                      <div>
                        <router-link to="/computers">
                          <b-button class="btn btn-sm btn-info p-1 rounded mx-2" v-b-tooltip.hover title="Go to master">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye align-middle"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                          </b-button>  
                        </router-link>
                      </div>
                      <div>
                        <router-link to="/trash-computers">
                          <b-button class="btn btn-sm btn-warning p-1 rounded" v-b-tooltip.hover title="Go to trash">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 align-middle"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                          </b-button>
                        </router-link>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>Class</td>
                    <td><span class="badge px-2 badge-success">{{kelas.length}}</span></td>
                    <td class="d-flex justify-content-start align-items-center">
                      <div>
                        <router-link to="/class">
                          <b-button class="btn btn-sm btn-info p-1 rounded mx-2" v-b-tooltip.hover title="Go to master">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye align-middle"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                          </b-button>  
                        </router-link>
                      </div>
                      <div>
                        <router-link to="/trash-class">
                          <b-button class="btn btn-sm btn-warning p-1 rounded" v-b-tooltip.hover title="Go to trash">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 align-middle"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                          </b-button>
                        </router-link>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>Students</td>
                    <td><span class="badge px-2 badge-success">{{siswa.length}}</span></td>
                    <td class="d-flex justify-content-start align-items-center">
                      <div>
                        <router-link to="/students">
                          <b-button class="btn btn-sm btn-info p-1 rounded mx-2" v-b-tooltip.hover title="Go to master">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye align-middle"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                          </b-button>  
                        </router-link>
                      </div>
                      <div>
                        <router-link to="/trash-students">
                          <b-button class="btn btn-sm btn-warning p-1 rounded" v-b-tooltip.hover title="Go to trash">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 align-middle"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                          </b-button>
                        </router-link>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>Teachers</td>
                    <td><span class="badge px-2 badge-success">{{guru.length}}</span></td>
                    <td class="d-flex justify-content-start align-items-center">
                      <div>
                        <router-link to="/teachers">
                          <b-button class="btn btn-sm btn-info p-1 rounded mx-2" v-b-tooltip.hover title="Go to master">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye align-middle"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                          </b-button>  
                        </router-link>
                      </div>
                      <div>
                        <router-link to="/trash-teachers">
                          <b-button class="btn btn-sm btn-warning p-1 rounded" v-b-tooltip.hover title="Go to trash">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 align-middle"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                          </b-button>
                        </router-link>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>Subjects</td>
                    <td><span class="badge px-2 badge-success">{{mapel.length}}</span></td>
                    <td class="d-flex justify-content-start align-items-center">
                      <div>
                        <router-link to="/subjects">
                          <b-button class="btn btn-sm btn-info p-1 rounded mx-2" v-b-tooltip.hover title="Go to master">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye align-middle"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                          </b-button>  
                        </router-link>
                      </div>
                      <div>
                        <router-link to="/trash-subjects">
                          <b-button class="btn btn-sm btn-warning p-1 rounded" v-b-tooltip.hover title="Go to trash">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 align-middle"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                          </b-button>
                        </router-link>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>Billings</td>
                    <td><span class="badge px-2 badge-success">{{billing.length}}</span></td>
                    <td class="d-flex justify-content-start align-items-center">
                      <div>
                        <router-link to="/billings">
                          <b-button class="btn btn-sm btn-info p-1 rounded mx-2" v-b-tooltip.hover title="Go to master">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye align-middle"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                          </b-button>  
                        </router-link>
                      </div>
                      <div>
                        <router-link to="/trash-billings">
                          <b-button class="btn btn-sm btn-warning p-1 rounded" v-b-tooltip.hover title="Go to trash">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 align-middle"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                          </b-button>
                        </router-link>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>Tasks</td>
                    <td><span class="badge px-2 badge-success">{{tugas.length}}</span></td>
                    <td class="d-flex justify-content-start align-items-center">
                      <div>
                        <router-link to="/tasks">
                          <b-button class="btn btn-sm btn-info p-1 rounded mx-2" v-b-tooltip.hover title="Go to master">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye align-middle"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                          </b-button>  
                        </router-link>
                      </div>
                      <div>
                        <router-link to="/trash-tasks">
                          <b-button class="btn btn-sm btn-warning p-1 rounded" v-b-tooltip.hover title="Go to trash">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 align-middle"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                          </b-button>
                        </router-link>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="" style="width: 45%">
            <div>
              <div class="mx-3 mt-4 my-3">
                <h4 class="text-success card-title mb-0"><b> Schedule </b></h4>
              </div>
              <div>
                <div v-for="item in lab" :key="item.id_lab">
                  <router-link class="bg-white shadow m-2 text-success d-flex justify-content-between align-items-center" style="text-decoration: none" :to="{ name: 'view-schedule', params: { id: item.id_lab }}">
                    <div class="py-4 px-5 rounded">
                        {{item.nama_lab}}
                    </div>
                    <div>
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right align-middle mr-3 text-success"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </div>
                  </router-link>
                </div>
              </div>
            </div>
            <div>
              <div class="mx-3 mt-4 my-3">
                <h4 class="text-success card-title mb-0"><b> Report. </b></h4>
              </div>
              <div>
                <router-link class="bg-white shadow m-2 text-success d-flex justify-content-between align-items-center" style="text-decoration: none" to="/billings">
                  <div class="py-4 px-5 rounded">
                      Make Report billing table.
                  </div>
                  <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right align-middle mr-3 text-success"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                  </div>
                </router-link>
              </div>
            </div>
          </div>
        </div>
        <div>
          <div class="ml-5 mx-3 mt-4 my-3">
              <h4 class="text-success card-title mb-0"><b> Statistic billing. </b></h4>
          </div>
          <div class="container">
            <div class="container d-flex justify-content-around align-items-center flex-wrap">
              <ChartPieThisDay :data="billing" :lab="lab" />
              <ChartPieThisWeek :data="billing" :lab="lab" />
              <ChartPieAll :data="billing" :lab="lab" />
            </div>
            <div class="container d-flex justify-content-center" style="width: 100%">
              <ChartBarThisYear :data="billing" :lab="lab" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </Main>
</template>

<script>
import { mapState } from 'vuex'
import Main from '../layouts/Main'
import ChartPieAll from '../components/dashboard/chart/ChartPieAll'
import ChartPieThisDay from '../components/dashboard/chart/ChartPieThisDay'
import ChartPieThisWeek from '../components/dashboard/chart/ChartPieThisWeek'
import ChartBarThisYear from '../components/dashboard/chart/ChartBarThisYear'

export default {
  components: {
    Main, ChartPieThisDay, ChartPieThisWeek, ChartPieAll, ChartBarThisYear
  },
  computed: {
    ...mapState({
      isLoading: state => state.isLoading,
      admin: state => state.admin.items,
      lab: state => state.lab.items,
      komputer: state => state.komputer.items,
      kelas: state => state.kelas.items,
      siswa: state => state.siswa.items,
      guru: state => state.guru.items,
      mapel: state => state.mapel.items,
      billing(state){
        const data = state.billing.items
        return data
      },
      tugas: state => state.tugas.items,
    }),
  },
  async created(){
    try {
      await this.$store.commit('SET_ISLOADING', true, { root: true })
      await this.$store.dispatch('admin/REFRESH_GET_ALL')
      await this.$store.dispatch('lab/REFRESH_GET_ALL')
      await this.$store.dispatch('komputer/REFRESH_GET_ALL')
      await this.$store.dispatch('kelas/REFRESH_GET_ALL')
      await this.$store.dispatch('siswa/REFRESH_GET_ALL')
      await this.$store.dispatch('guru/REFRESH_GET_ALL')
      await this.$store.dispatch('mapel/REFRESH_GET_ALL')
      await this.$store.dispatch('billing/REFRESH_GET_ALL')
      await this.$store.dispatch('tugas/REFRESH_GET_ALL')
      await this.$store.commit('SET_ISLOADING', false, { root: true })
    } catch (error) {
      alert(error);
    }
  }
}
</script>

<style>

</style>