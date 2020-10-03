<template>
  <div>
    <div v-if="isLoading" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 999">
        <b-spinner style="width: 3rem; height: 3rem;" variant="info" label="Large Spinner"></b-spinner>
    </div>
    <div v-else>
        <div class="d-flex justify-content-start align-items-center bg-white shadow-sm px-4 py-3 card-list card-dd-mapel mb-3">
            <div class="mr-3">
                <router-link style="text-decoration: none;" :to="{ name: 'schedule-teacher'}">
                <i class="fas fa-arrow-left text-info" style="font-size: 13pt;"></i>
                </router-link>
            </div>
            <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 0; width: 90%">
                <div>
                    <div class="">
                        <div>
                            <small style="color: lightseagreen; font-size: 9pt;">{{listTugas.data.jam_mulai}} - {{listTugas.data.jam_selesai}}</small>
                        </div>
                    </div>
                    <div style="font-size: 10pt;" class="">
                        <div>
                            <p class="mb-0">
                                {{ listTugas.data.nama_mapel }}
                            </p>
                        </div>
                    </div>
                </div>
                <div>
                    {{listTugas.data.nama_lab}} - {{listTugas.data.nama_kelas}}
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="ml-3 mb-0" style="color: lightseagreen"><b>Task.</b></h5>
            <Create />
        </div>
        <div class="p-2">
            <div v-if="listTugas.records == ''">
                <p class="text-center" style="color: grey">Task is empty.</p>
            </div>
            <div v-else>
                <div v-for="tugas in listTugas.records" :key="tugas.id_tugas">
                    <router-link style="text-decoration: none;" :to="{ name: 'info-task', params: { id_tugas: tugas.id_tugas }}">
                        <div class="d-flex justify-content-between align-items-center bg-white shadow-sm px-4 py-3 mb-1 card-list card-dd-mapel" style="margin-bottom: 0">
                            <div>
                                <div class="">
                                    <div>
                                        <small style="font-size: 9pt;">Due date: </small><small style="color: lightseagreen; font-size: 9pt;">{{tugas.due_date}}</small>
                                    </div>
                                </div>
                                <div style="font-size: 10pt;" class="">
                                    <div>
                                        <p class="mb-0">
                                            {{ tugas.title }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <p class="mb-0" :style="[tugas.status !== 'Closed' ? {'color': 'green'} : {'color': 'red'}]">
                                    {{tugas.status}}
                                </p>
                            </div>
                        </div>
                    </router-link>
                </div>
            </div>
            
        </div>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import Create from '../../components/app-guru/tugas/Create'
export default {
    components: {
        Create
    },
    computed: {
        ...mapState({
            listTugas: state => state.tugas.listTugas,
            isLoading: state => state.isLoading
        })
    },
    async created(){
        try {
            let id = this.$route.params.id;
            await this.$store.dispatch('tugas/GET_INFO_BY_ID_JADWAL_MAPEL', id)
        } catch (error) {
            alert(error)   
        }
    }
}
</script>

<style>
  input[type="file"]{
    position: absolute;
    top: -500px;
  }

  div.file-listing{
    width: 200px;
  }

  span.remove-file{
    color: red;
    cursor: pointer;
    float: right;
  }
</style>