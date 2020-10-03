<template>
  <div>
      <b-btn v-if="!conditionAddClass" @click="redirectCreate(column)"  variant="light" class="mx-2 mb-5 rouded" style="width: 95%">
            <div class="py-2 d-flex justify-content-center align-items-center"><small class="mb-0" style="color: lightseagreen"><b>Create new class schedule</b></small></div>
        </b-btn>
      <div v-if="kelas != [] && conditionAddClass" class="d-flex justify-content-center">
        <b-form @submit.stop.prevent="create" class="p-2 rounded bg-white shadow shadow-lg mb-3" style="width: 260px">

                <div class="d-flex justify-content-between align-items-center">
                    <div style="width: 45%">
                        <div class="form-group">
                            <label style="font-size: 8pt" for="edit_jam_mulai">Started at :</label>
                            <input type="time" v-model.trim="$v.jam_mulai.$model" class="form-control form-control-sm mb-0" id="edit_jam_mulai">
                            <div class="error" style="font-size: 8pt;" v-if="!$v.jam_mulai.required && $v.jam_mulai.$anyDirty">Started at is required.</div>
                            <div class="error" style="font-size: 8pt;" v-if="!$v.jam_mulai.isUnique && $v.jam_mulai.$anyDirty">Started at is already registered.</div>
                        </div>
                        
                    </div>

                    <div style="width: 45%">
                        <div class="form-group">
                            <label style="font-size: 8pt" for="edit_jam_selesai">Ended at :</label>
                            <input type="time" v-model.trim="$v.jam_selesai.$model" class="form-control form-control-sm mb-0" id="edit_jam_selesai">
                            <div class="error" style="font-size: 8pt;" v-if="!$v.jam_selesai.required && $v.jam_selesai.$anyDirty">Ended at is required.</div>
                            <div class="error" style="font-size: 8pt;" v-if="!$v.jam_selesai.isUnique && $v.jam_selesai.$anyDirty">Ended at is already registered.</div>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="error" style="font-size: 8pt; color: cadetblue" v-if="!conditionCekLintasJadwalBentrok">
                        You have a conflict schedule because your started at and ended at accros a schedule. please check again !
                    </div>
                </div>

                <div class="form-group">
                    <label style="font-size: 8pt" for="id_kelas">Class :</label>
                    <select name="" v-model.trim="$v.id_kelas.$model" class="custom-select custom-select-sm mb-0" id="id_kelas">
                        <option disabled value="" style="">- - -</option>
                        <option v-for="k in kelas" :key="k.id_kelas" :value="k.id_kelas">{{k.nama_kelas}}</option>
                    </select>
                    <div class="error" style="font-size: 8pt;" v-if="!$v.id_kelas.required && $v.id_kelas.$anyDirty">Class is required.</div>
                </div>

                
                <div class="d-flex justify-content-start align-items-center">
                    <b-button type="submit" class="btn btn-info btn-sm px-5" :disabled="!conditionCekLintasJadwalBentrok">
                        <div v-if="isLoadingActionButton">
                            <b-spinner variant="light" type="grow" label="Spinning" small></b-spinner>
                            <b-spinner variant="light" type="grow" label="Spinning" small></b-spinner>
                            <b-spinner variant="light" type="grow" label="Spinning" small></b-spinner>
                        </div>
                        <div v-else style="font-size: 8pt;">
                            Save
                        </div>
                    </b-button>
                    <div>
                        <b-btn class="btn btn-sm btn-danger rounded-circle ml-2" @click="closeAddClass"> x </b-btn>
                    </div>
                </div>
            </b-form>
            <b-toast id="toast-created-jd" title="Successfuly to created." variant="success" toaster="b-toaster-bottom-right">
                Schedule will be added.
            </b-toast>
    </div>
  </div>
</template>

<script>
import { required } from 'vuelidate/lib/validators'
import { mapState  } from 'vuex'


export default {
    data(){
        return {
            id_hari: '',
            id_kelas: '',
            jam_mulai: '',
            jam_selesai: '',
            conditionAddClass: '',
            conditionCekLintasJadwalBentrok: true
        }
    },
    props: [
        'column'
    ],
    computed: {
        ...mapState({
            kelas: state => state.kelas.items,
            isLoadingActionButton: state => state.isLoadingActionButton
        }),
    },
    validations: {
        id_hari: {required},
        id_kelas: {required},
        jam_mulai: {
            required,
            isUnique (value) {
                
                if (value === '') return true

                if(this.column.jadwal_lab.find(jl => value + ':00' == jl.jam_mulai)){
                    return false
                }
                else if(this.column.jadwal_lab.find(jl => value > jl.jam_mulai && value + ':00' == jl.jam_selesai )){
                    return true
                }
                else if(this.column.jadwal_lab.find(jl => value > jl.jam_mulai && value < jl.jam_selesai )){
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
                
                if (value === '') return true

                if(this.column.jadwal_lab.find(jl => value > jl.jam_mulai && value + ':00' == jl.jam_selesai )){
                    return true
                }
                else if(this.column.jadwal_lab.find(jl => value > jl.jam_mulai && value < jl.jam_selesai )){
                    return false
                }
                else{
                    return true
                }
            }
        }
        
    },
    watch: {
        jam_mulai(){
            this.cekLintasJadwalBentrok()
        },
        jam_selesai(){
            this.cekLintasJadwalBentrok()
        }
    },
    methods: {
        async cekLintasJadwalBentrok(){
            var bentrok = this.column.jadwal_lab.find(jl =>{
                return this.jam_selesai > jl.jam_mulai && this.jam_mulai < jl.jam_selesai && this.jam_mulai + ':00' != jl.jam_selesai
            })

            if (bentrok) {
                this.conditionCekLintasJadwalBentrok = false
            }else{
                this.conditionCekLintasJadwalBentrok = true
            }

            console.log('bentrok', bentrok);
        },

        async redirectCreate(column){
            try{
                // this.conditionAddClass = false
                this.conditionAddClass = true
                await this.$store.commit('SET_ISLOADING_ACTION_BUTTON', true, { root: true })
                this.column = column
                // this.$bvModal.show( "create-jadwal-dokter"  + column.id)
                this.id_hari = this.column.id
                await this.$store.dispatch('kelas/REFRESH_GET_ALL')
                await this.$store.commit('SET_ISLOADING_ACTION_BUTTON', false, { root: true })
                
            }catch(err) {
                alert(err);
            }
        },

        async closeAddClass(){
            this.conditionAddClass = false
        },


        async create(){
            console.log(this.$route.params.id);
            this.$v.$touch();

            if (this.$v.$anyError) {
                return;
            }

            try{
                await this.$store.dispatch('view_jadwal/CREATE_JADWAL_KELAS', {
                    'form': {
                        id_hari: this.id_hari,
                        id_kelas: this.id_kelas,
                        jam_mulai: this.jam_mulai,
                        jam_selesai: this.jam_selesai
                    }, 
                    'id': this.$route.params.id })
                this.$bvToast.show('toast-created-jd')
                this.id_hari = ''
                this.id_kelas = ''
                this.jam_mulai = ''
                this.jam_selesai = ''
                this.conditionAddClass = false
                await this.$v.$reset()

            }catch(err){
                alert(err);
            }
        },
    }
    
}
</script>

<style>

</style>