<template>
    <div v-if="jadwal_lab != '' && jadwal_mapel != ''">
        <b-modal id="modalInfoMapel" hide-footer ok-only size="md">
            <b-tabs fill
                active-nav-item-class="font-weight-bold text-primary"
                content-class="mt-3">
                <b-tab title="Detail" active>
                    <div style="display: none">
                            {{search_jadwal_lab()}}
                            {{cekLintasJadwalBentrok()}}
                    </div> 
                    <b-form @submit.stop.prevent="update('data')">
                            <div class="d-flex justify-content-end">
                                <b-button id="popover-button-variant-jm" class="btn btn-sm btn-danger" href="#" tabindex="0">Delete this subject schedule</b-button>
                                <b-popover placement="left" target="popover-button-variant-jm" variant="seondary" triggers="focus">
                                    <p class="mb-1"> Are you sure ?</p>
                                    <b-button class="btn btn-sm container" variant="primary" @click="softDelete(jadwal_mapel.id)">
                                        Yes
                                    </b-button>
                                </b-popover>
                            </div>
                            <div>
                                <p style="color: lightseagreen">
                                    <b>
                                        Data
                                    </b>
                                </p>
                            <div class="d-flex justify-content-around align-items-center">
                                <div style="width: 45%">
                                    <div class="form-group">
                                        <label for="id_mapel" style="font-size: 8pt">Subject:</label>
                                        <select name="" v-model.trim="$v.jadwal_mapel.id_mapel.$model" class="custom-select custom-select-sm mb-0" id="id_mapel">
                                            <option disabled value="" style="">- - -</option>
                                            <option v-for="k in mapel" :key="k.id_mapel" :value="k.id_mapel">{{k.nama_mapel}}</option>
                                        </select>
                                        <div class="error" v-if="!$v.jadwal_mapel.id_mapel.required && $v.jadwal_mapel.id_mapel.$anyDirty">Subject is required.</div>
                                    </div>
                                </div>
                                <div style="width: 45%">
                                    <div class="form-group">
                                        <label for="id_guru" style="font-size: 8pt">Teacher:</label>
                                        <select name="" v-model.trim="$v.jadwal_mapel.id_guru.$model" class="custom-select custom-select-sm mb-0" id="id_guru">
                                            <option disabled value="" style="">- - -</option>
                                            <option v-for="k in guru" :key="k.id_guru" :value="k.id_guru">{{k.nama_guru}}</option>
                                        </select>
                                        <div class="error" v-if="!$v.jadwal_mapel.id_guru.required && $v.jadwal_mapel.id_guru.$anyDirty">Teacher is required.</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-around align-items-center">
                                <div style="width: 45%">
                                    <div class="form-group">
                                        <label for="edit_jam_mulai" style="font-size: 8pt">Started at :</label>
                                        <input type="time" v-model.trim="$v.jadwal_mapel.jam_mulai.$model" class="form-control form-control-sm  mb-0" id="edit_jam_mulai">
                                        <div class="error" v-if="!$v.jadwal_mapel.jam_mulai.required && $v.jadwal_mapel.jam_mulai.$anyDirty">Started at is required.</div>
                                        <div class="error" v-if="!$v.jadwal_mapel.jam_mulai.isUnique && $v.jadwal_mapel.jam_mulai.$anyDirty">Started at is already registered.</div>
                                        <div class="error" v-if="!$v.jadwal_mapel.jam_mulai.isBetween && $v.jadwal_mapel.jam_mulai.$anyDirty">Must be between {{column.jam_mulai}} and {{column.jam_selesai}}</div>
                                    </div>
                                </div>

                                <div style="width: 45%">
                                    <div class="form-group">
                                        <label for="edit_jam_selesai" style="font-size: 8pt">Ended at :</label>
                                        <input type="time" v-model.trim="$v.jadwal_mapel.jam_selesai.$model" class="form-control form-control-sm  mb-0" id="edit_jam_selesai">
                                        <div class="error" v-if="!$v.jadwal_mapel.jam_selesai.required && $v.jadwal_mapel.jam_selesai.$anyDirty">Ended at is required.</div>
                                        <div class="error" v-if="!$v.jadwal_mapel.jam_selesai.isUnique && $v.jadwal_mapel.jam_selesai.$anyDirty">Ended at is already registered.</div>
                                        <div class="error" v-if="!$v.jadwal_mapel.jam_selesai.isBetween && $v.jadwal_mapel.jam_selesai.$anyDirty">Must be between {{column.jam_mulai}} and {{column.jam_selesai}}</div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="error" style="font-size: 8pt; color: cadetblue" v-if="!conditionCekLintasJadwalBentrok">
                                    You have a conflict schedule because your started at and ended at accros a schedule. please check again !
                                </div>
                            </div>

                            <div class="mb-3 mr-2 d-flex justify-content-end">
                                <b-button type="submit" class="btn btn-info btn-sm px-5" :disabled="!conditionCekLintasJadwalBentrok">
                                    <div v-if="isLoadingAction">
                                        <b-spinner variant="light" type="grow" label="Spinning" small></b-spinner>
                                        <b-spinner variant="light" type="grow" label="Spinning" small></b-spinner>
                                        <b-spinner variant="light" type="grow" label="Spinning" small></b-spinner>
                                    </div>
                                    <div v-else>
                                        Save
                                    </div>
                                </b-button>
                            </div>
                        </div>
                    </b-form>
                    
                    <b-form @submit.stop.prevent="update('position')">
                    <div>
                        <p style="color: lightseagreen">
                            <b>
                                Position
                            </b>
                        </p>
                        <div>
                            <div class="mb-3 select-p">
                                <label for="edit_hari" style="font-size: 8pt">Day :</label>
                                <select name="" v-model.trim="$v.jadwal_lab.id_hari.$model" class="custom-select custom-select-sm mb-0" id="edit_hari">
                                    <option v-for="hari in jadwals.jadwal_hari" :key="hari.id" v-bind:value="hari.id">{{hari.hari}}</option>
                                </select>
                                <div class="error" v-if="!$v.jadwal_lab.id_hari.required && $v.jadwal_lab.id_hari.$anyDirty">Day is required.</div>
                            </div>
                        </div>
                        <div>
                            <label style="font-size: 8pt">Class: </label>
                            <div class="mb-3" v-for="jadwal_lab in jadwal_labs" :key="jadwal_lab.id">
                                <div class="d-flex justify-content-start align-items-center bg-light p-1 rounded shadow-sm mb-1">
                                    <input type="radio" name="jadwal_lab" :id="'jp' + jadwal_lab.id" v-bind:value="jadwal_lab.id" v-model.trim="$v.jadwal_lab.id.$model" class="mr-3">
                                    <label style="font-size: 9pt" :for="'jp' + jadwal_lab.id" class="mb-0 container p-1">{{ jadwal_lab.nama_kelas }}</label>
                                </div>
                            </div>
                            <div class="error" v-if="jadwal_labs == ''">
                                <p style="color: grey"> This day no have class shedule </p>
                            </div>
                            
                            <div class="error" v-if="!$v.jadwal_lab.id.required && $v.jadwal_lab.id.$anyDirty"> Day is required.</div>
                        </div>

                    </div>
                    <div class="mb-3 d-flex justify-content-end">
                            <b-button type="submit" class="btn btn-info btn-sm px-5" :disabled="!conditionCekLintasJadwalBentrok">
                                <div v-if="isLoadingAction">
                                    <b-spinner variant="light" type="grow" label="Spinning" small></b-spinner>
                                    <b-spinner variant="light" type="grow" label="Spinning" small></b-spinner>
                                    <b-spinner variant="light" type="grow" label="Spinning" small></b-spinner>
                                </div>
                                <div v-else>
                                    Move
                                </div>
                            </b-button>
                        </div>
                    </b-form>
                </b-tab>

                <b-tab title="Task" @click="getListTask">
                    <div v-if="isLoading">
                        <b-spinner variant="light" type="grow" label="Spinning" small></b-spinner>
                        <b-spinner variant="light" type="grow" label="Spinning" small></b-spinner>
                        <b-spinner variant="light" type="grow" label="Spinning" small></b-spinner>
                    </div>
                    <div v-else>
                        <div class="p-2">
                            <div v-if="listTugas.records == ''">
                                <p class="text-center" style="color: grey">Task is empty.</p>
                            </div>
                            <div v-else>
                                <div v-for="tugas in listTugas.records" :key="tugas.id_tugas">
                                    <div style="text-decoration: none;cursor: pointer" :to="{ name: 'info-task', params: { id_tugas: tugas.id_tugas }}" @click="redirectInfoTugas(tugas)">
                                        <div class="d-flex justify-content-between align-items-center bg-white shadow-lg px-4 py-3 mb-1 card-list card-dd-mapel" style="margin-bottom: 0">
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
                                            <p class="mb-0" :style="[tugas.status !== 'Closed' ? {'color': 'green'} : {'color': 'red'}]">
                                                {{tugas.status}}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </b-tab>
            </b-tabs>
        </b-modal>
        <InfoTugas />
        <b-toast id="toast-softdelete-jm" title="Schedule successfuly to softdelete." variant="success" toaster="b-toaster-bottom-right">
            Schedule will be put in trash.
        </b-toast>
        
        <b-toast id="bentrok-jm" :title="'You have a conflict schedule. in ' + bentrok.columnId.hari + ' -> ' + bentrok.columnId.nama_kelas + ' ' + bentrok.columnId.jam_mulai + '-' + bentrok.columnId.jam_selesai + ' -> (' + bentrok.data.nama_mapel + ' ' + bentrok.data.jam_mulai + '-' + bentrok.data.jam_selesai + ')'" :no-auto-hide="true" variant="danger" toaster="b-toaster-bottom-full">
            Because in this schedule started at and ended at accros a schedule. please check again ! 
        </b-toast>
    </div>
</template>

<script>
import { required } from 'vuelidate/lib/validators'
import { mapState  } from 'vuex'
import InfoTugas from './InfoTugas'

export default {
    props: [
        'jadwal_mapel', 'jadwal_lab', 'column'
    ],
    data(){
        return{
            conditionCekLintasJadwalBentrok: true,
            count_search_jadwal_lab: 0
        }
    },
    components: {
        InfoTugas
    },
    computed: {
        ...mapState({
            isLoadingAction: state => state.isLoadingAction,
            isLoading: state => state.isLoading,
            jadwals(state){
                return state.view_jadwal.items
            },
            mapel: state => state.mapel.items,
            guru: state => state.guru.items,
            jadwal_mapels (state) {
                const info = JSON.parse(JSON.stringify(state.view_jadwal.info))
                return info.jadwal_mapel
            },
            bentrok: state => state.view_jadwal.bentrok,
            listTugas: state => state.tugas.listTugas,
        }),
        current_id_jadwal_lab(){
            return this.jadwal_mapel.id_jadwal_lab
        }
    },
    watch:{
        'jadwal_lab.id_hari': function() {
            this.search_jadwal_lab();
        },
        'jadwal_mapel.jam_mulai': function(){
            
            this.cekLintasJadwalBentrok()
        },
        'jadwal_mapel.jam_selesai': function(){
            this.cekLintasJadwalBentrok()
        },
    },
    validations: {
        jadwal_mapel: {
            id_mapel: {required},
            id_guru: {required},
            jam_mulai: {
                required,
                isUnique (value) {
                    
                    if (value === '') return true

                    const cek1 = this.jadwal_mapels.find(jm => {
                        return value + ':00' == jm.jam_mulai
                    })
                    if (cek1) {
                        if(this.jadwal_mapel.id == cek1.id){
                            return true
                        }
                    }
                    const cek2 = this.jadwal_mapels.find(jm => {
                        return value > jm.jam_mulai && value < jm.jam_selesai
                    })
                    if (cek2) {
                        if (this.jadwal_mapel.id == cek2.id) {
                            return true
                        }
                    }

                    if(this.jadwal_mapels.find(jm => value + ':00' == jm.jam_mulai)){
                        return false
                    }
                    else if(this.jadwal_mapels.find(jm => value > jm.jam_mulai && value + ':00' == jm.jam_selesai )){
                        return true
                    }
                    else if(this.jadwal_mapels.find(jm => value > jm.jam_mulai && value < jm.jam_selesai )){
                        return false
                    }
                    else{
                        return true
                    }
                },
                isBetween(value){
                    if (value + ':00' > this.column.jam_selesai) {
                        return false
                    }else if(value + ':00' < this.column.jam_mulai){
                        return false
                    }
                    else{
                        return true
                    }
                }
            },
            jam_selesai: {
                required,
                isUnique (value) {

                    const cek1 = this.jadwal_mapels.find(jm => {
                        return value > jm.jam_mulai && value < jm.jam_selesai
                    })
                    if (cek1) {
                        if(this.jadwal_mapel.id == cek1.id){
                            return true
                        }
                    }
                    
                    if (value === '') return true

                    if(this.jadwal_mapels.find(jm => value > jm.jam_mulai && value + ':00' == jm.jam_selesai )){
                        return true
                    }
                    else if(this.jadwal_mapels.find(jm => value > jm.jam_mulai && value < jm.jam_selesai )){
                        return false
                    }
                    else{
                        return true
                    }
                },
                isBetween(value){
                    if((value + ':00' < this.column.jam_mulai || value + ':00' > this.column.jam_mulai) && value + ':00' == this.column.jam_selesai){
                        return true
                    }
                    console.log(value , this.column.jam_selesai);
                    if(value == this.column.jam_selesai){
                        return true
                    }
                    if (value + ':00' > this.column.jam_selesai) {
                        return false
                    }else if(value + ':00' < this.column.jam_mulai){
                        return false
                    }
                    else{
                        return true
                    }
                }
            }
        },
        jadwal_lab: {
            id_hari: {required},
            id: {required},
        }
    },
    
    methods: {
        async getListTask(){
            try {
                let id = this.jadwal_mapel.id;
                await this.$store.dispatch('tugas/GET_INFO_BY_ID_JADWAL_MAPEL', id)
            } catch (error) {
                alert(error)   
            }
        },
        async cekLintasJadwalBentrok(){

            var bentrok = this.jadwal_mapels.filter(jm =>{
                return this.jadwal_mapel.jam_selesai > jm.jam_mulai && this.jadwal_mapel.jam_mulai < jm.jam_selesai && this.jadwal_mapel.jam_mulai + ':00' != jm.jam_selesai
            })

            if (bentrok.length == 1) {
                if (bentrok[0].id == this.jadwal_mapel.id) {
                    this.conditionCekLintasJadwalBentrok = true
                }else{
                    this.conditionCekLintasJadwalBentrok = false
                }
                
            }else if(bentrok.length > 1){
                this.conditionCekLintasJadwalBentrok = false
            }
            else{
                this.conditionCekLintasJadwalBentrok = true
            }
        },

        async search_jadwal_lab(){
            try {
                
                var a = this.jadwals.jadwal_hari.find(el => {
                    return el.id == this.jadwal_lab.id_hari
                })
                this.jadwal_labs = a.jadwal_lab
            } catch (error) {
                console.log(error);
                this.jadwal_labs = []
            }
        },
        async update(ket){
            this.$v.$touch();
            if (this.$v.$anyError) {
                return;
            }

            try{
                var id_kelas_in_mapel = this.jadwal_labs.find(el => {
                    return el.id == this.jadwal_lab.id
                })
                await this.$store.dispatch('view_jadwal/UPDATE_JADWAL_MAPEL', {
                    'form': {
                        'current_id_jadwal_lab': this.jadwal_lab.id,
                        'id_jadwal_mapel': this.jadwal_mapel.id,
                        'id_jadwal_lab': this.jadwal_lab.id,
                        'id_kelas': id_kelas_in_mapel.id_kelas,
                        'id_mapel': this.jadwal_mapel.id_mapel,
                        'id_guru': this.jadwal_mapel.id_guru,
                        'jam_mulai': this.jadwal_mapel.jam_mulai,
                        'jam_selesai': this.jadwal_mapel.jam_selesai,
                        'nama_mapel': this.jadwal_mapel.nama_mapel,
                        'nama_guru': this.jadwal_mapel.nama_guru
                    },
                    'id': this.$route.params.id 
                })

                console.log(this.bentrok.data, this.bentrok.columnId);

                if (ket == 'position') {
                    this.$bvModal.hide("modalInfoMapel")
                }
                console.log('cekk', this.bentrok.data.id_jadwal_lab, this.current_id_jadwal_lab);
                if (this.bentrok.data.id_jadwal_lab == this.current_id_jadwal_lab) {
                    this.$bvToast.hide('bentrok-jm')
                }else{
                    if (!this.bentrok.conditionCekLintasJadwalBentrok) {
                        this.$bvToast.show('bentrok-jm')
                    }else{
                        this.$bvToast.hide('bentrok-jm')
                    }
                }
                

                this.$bvToast.show('toast-updated-jd')

            }catch(err){
                alert(err);
            }
        },
        async softDelete(id){
            try{
                await this.$store.dispatch('view_jadwal/SOFTDELETE_ONE_JADWAL_MAPEL', {
                    'id': id,
                    'id_lab': this.$route.params.id,
                    'id_jadwal_lab': this.jadwal_mapel.id_jadwal_lab
                })
                this.$bvModal.hide("modalInfoMapel")
                await this.$bvToast.show('toast-softdelete-jm')
            }catch(err) {
                alert(err);
            }
        },
        async redirectInfoTugas(tugas){
            try {
                await this.$store.commit('tugas/SET_INFO_BY_ID', tugas)
                this.$bvModal.show( "infoTugas")
            } catch (error) {
                alert(error)   
            }
        }
    }
}
</script>

<style>

</style>
