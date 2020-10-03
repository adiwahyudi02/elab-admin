<template>
  <Main>
    <div>
        <div class="bg-info navbar fixed-top">
            <h5 class="text-white mb-0 ml-3">Schedule</h5>
        </div>
        <div class="mt-5">
            <b-tabs content-class="mt-3" fill
            active-nav-item-class="font-weight-bold text-primary"
            >
                <b-tab title="Schedule" active>
                    <div v-if="itemToday == '' && items == ''">
                        <div v-if="isLoading" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 999">
                            <b-spinner style="width: 3rem; height: 3rem;" variant="info" label="Large Spinner"></b-spinner>
                        </div>
                    </div>
                    <div v-else class="p-2">
                        <div v-for="item in items" :key="item.hari">
                            <div v-if="item.data != ''">
                                <h5 class="text-info font-weight-bold my-2 ml-3">
                                    {{item.hari}}
                                </h5>
                            </div>
                            <div v-if="item.data != ''">
                                <div v-for="jadwal in item.data" :key="jadwal.id_jadwal_mapel">
                                    <router-link style="text-decoration: none;" :to="{ name: 'info-mapel', params: { id: jadwal.id_jadwal_mapel }}">
                                        <div style="margin-bottom: 10px" class="d-flex justify-content-between align-items-center bg-white shadow-sm px-4 py-3 card-list card-dd-mapel" :id="jadwal.id_lab + '-' + jadwal.id_jadwal_mapel">
                                            <div>
                                                <div class="">
                                                    <div>
                                                        <small style="color: lightseagreen; font-size: 9pt;">{{jadwal.jam_mulai}} - {{jadwal.jam_selesai}}</small>
                                                    </div>
                                                </div>
                                                <div style="font-size: 10pt;" class="">
                                                    <div>
                                                        <p class="mb-0">
                                                            {{ jadwal.nama_mapel }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                {{jadwal.nama_lab}} - {{jadwal.nama_kelas}}
                                            </div>
                                        </div>
                                    </router-link>
                                </div>
                            </div>
                        </div>
                    </div>
                </b-tab>
                <b-tab title="Today">
                    <div v-if="itemToday == '' && items == ''">
                        <div v-if="isLoading" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 999">
                            <b-spinner style="width: 3rem; height: 3rem;" variant="info" label="Large Spinner"></b-spinner>
                        </div>
                    </div>
                    <div v-else class="p-2">
                        <div v-if="itemToday.data != []">
                            <h5 class="text-info font-weight-bold my-2 ml-3">
                                {{itemToday.hari}}
                            </h5>
                        </div>
                        <div v-if="itemToday.data != []">
                            <div v-for="jadwal in itemToday.data" :key="jadwal.id_jadwal_mapel">
                                <router-link style="text-decoration: none;" :to="{ name: 'info-mapel', params: { id: jadwal.id_jadwal_mapel }}">
                                    <div style="margin-bottom: 10px" class="d-flex justify-content-between align-items-center bg-white shadow-sm px-4 py-3 card-list card-dd-mapel" :id="jadwal.id_lab + '-' + jadwal.id_jadwal_mapel">
                                        <div>
                                            <div class="">
                                                <div>
                                                    <small style="color: lightseagreen; font-size: 9pt;">{{jadwal.jam_mulai}} - {{jadwal.jam_selesai}}</small>
                                                </div>
                                            </div>
                                            <div style="font-size: 10pt;" class="">
                                                <div>
                                                    <p class="mb-0">
                                                        {{ jadwal.nama_mapel }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            {{jadwal.nama_lab}} - {{jadwal.nama_kelas}}
                                        </div>
                                    </div>
                                </router-link>
                            </div>
                        </div>
                    </div>
                </b-tab>
                
            </b-tabs>
        </div>
    </div>
  </Main>
</template>

<script>
import { mapState  } from 'vuex'
import Main from '../../layouts/app-guru/Main'
export default {
    data(){
        return {
            itemToday: ''
        }
    },
    components: {
        Main
    },
    computed: {
        getTodaySchedule(){
            return this.$store.getters['jadwal/getTodaySchedule']
        },
        ...mapState({
            items: state => state.jadwal.items,
            isLoading: state => state.isLoading,
            isLoadingAction: state => state.isLoadingAction
        }),
    },
    watch: {
        getTodaySchedule(){
            this.itemToday = this.getTodaySchedule
        }
    },
    async created(){
        try {
            await this.$store.dispatch('jadwal/GET_ALL')
        } catch (error) {
            alert(error);
        }
    }
}
</script>

<style>

</style>