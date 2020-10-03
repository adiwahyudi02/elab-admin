<template>
  <div>
    <!-- {{column}}
    {{jadwal_mapel}} -->
        <b-form @submit.stop.prevent="createJadwalMapel" class="bg-white shadow shadow-lg rounded p-2">
            <div class="d-flex justify-content-end">
                <b-btn class="btn btn-sm btn-danger rounded-circle" @click="closeCreateJadwalMapel"> x </b-btn>
            </div>
            <div class="d-flex justify-content-around align-items-center">
                <div style="width: 45%">
                    <div class="form-group">
                        <label for="id_mapel" style="font-size: 8pt">Subject:</label>
                        <select name="" v-model.trim="$v.id_mapel.$model" class="custom-select custom-select-sm mb-0" id="id_mapel">
                            <option disabled value="" style="">- - -</option>
                            <option v-for="k in mapel" :key="k.id_mapel" :value="k.id_mapel">{{k.nama_mapel}}</option>
                        </select>
                        <div class="error" v-if="!$v.id_mapel.required && $v.id_mapel.$anyDirty">Subject is required.</div>
                    </div>
                </div>
                <div style="width: 45%">
                    <div class="form-group">
                        <label for="id_guru" style="font-size: 8pt">Teacher:</label>
                        <select name="" v-model.trim="$v.id_guru.$model" class="custom-select custom-select-sm mb-0" id="id_guru">
                            <option disabled value="" style="">- - -</option>
                            <option v-for="k in guru" :key="k.id_guru" :value="k.id_guru">{{k.nama_guru}}</option>
                        </select>
                        <div class="error" v-if="!$v.id_guru.required && $v.id_guru.$anyDirty">Teacher is required.</div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-around align-items-center">
                <div style="width: 45%">
                    <div class="form-group">
                        <label for="edit_jam_mulai" style="font-size: 8pt">Started at :</label>
                        <input type="time" v-model.trim="$v.jam_mulai.$model" class="form-control form-control-sm mb-0" id="edit_jam_mulai">
                        <div class="error" v-if="!$v.jam_mulai.required && $v.jam_mulai.$anyDirty">Started at is required.</div>
                        <div class="error" v-if="!$v.jam_mulai.isUnique && $v.jam_mulai.$anyDirty">Started at is already registered.</div>
                        <div class="error" v-if="!$v.jam_mulai.isBetween && $v.jam_mulai.$anyDirty">Must be between {{column.jam_mulai}} and {{column.jam_selesai}}</div>
                    </div>
                </div>

                <div style="width: 45%">
                    <div class="form-group">
                        <label for="edit_jam_selesai" style="font-size: 8pt">Ended at :</label>
                        <input type="time" v-model.trim="$v.jam_selesai.$model" class="form-control form-control-sm mb-0" id="edit_jam_selesai">
                        <div class="error" v-if="!$v.jam_selesai.required && $v.jam_selesai.$anyDirty">Ended at is required.</div>
                        <div class="error" v-if="!$v.jam_selesai.isUnique && $v.jam_selesai.$anyDirty">Ended at is already registered.</div>
                        <div class="error" v-if="!$v.jam_selesai.isBetween && $v.jam_selesai.$anyDirty">Must be between {{column.jam_mulai}} and {{column.jam_selesai}}</div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between mr-2">
                <div>
                    <div class="error" v-if="!conditionCekLintasJadwalBentrok" style="color: cadetblue">
                        You have a conflict schedule because your started at and ended at accros a schedule. please check again !
                    </div>
                </div>
                <b-button :disabled="!conditionCekLintasJadwalBentrok" type="submit" class="btn btn-info btn-sm px-5">
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
        </b-form>
        <b-toast id="toast-created-jm" title="Successfuly to created." variant="success" toaster="b-toaster-bottom-right">
            Schedule will be added.
        </b-toast>
  </div>
</template>

<script>
import { required } from 'vuelidate/lib/validators'
import { mapState  } from 'vuex'
export default {
    data(){
        return{
            id_mapel: '',
            id_guru: '',
            jam_mulai: '',
            jam_selesai: '',
            conditionCekLintasJadwalBentrok: true
        }
    },
    props: [
        'column'
    ],
    computed: {
        ...mapState({
            mapel: state => state.mapel.items,
            guru: state => state.guru.items,
            isLoadingAction: state => state.isLoadingAction,
            jadwal_mapel (state) {
                const info = JSON.parse(JSON.stringify(state.view_jadwal.info))
                return info.jadwal_mapel
            },
        }),
    },
    watch: {
        jam_mulai(){
            this.cekLintasJadwalBentrok()
        },
        jam_selesai(){
            this.cekLintasJadwalBentrok()
        }
    },
    validations: {
        id_mapel: {required},
        id_guru: {required},
        jam_mulai: {
            required,
            isUnique (value) {
                
                if (value === '') return true

                if(this.jadwal_mapel.find(jm => value + ':00' == jm.jam_mulai)){
                    return false
                }
                else if(this.jadwal_mapel.find(jm => value > jm.jam_mulai && value + ':00' == jm.jam_selesai )){
                    return true
                }
                else if(this.jadwal_mapel.find(jm => value > jm.jam_mulai && value < jm.jam_selesai )){
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
                
                if (value === '') return true

                if(this.jadwal_mapel.find(jm => value > jm.jam_mulai && value + ':00' == jm.jam_selesai )){
                    return true
                }
                else if(this.jadwal_mapel.find(jm => value > jm.jam_mulai && value < jm.jam_selesai )){
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
    methods: {
        async cekLintasJadwalBentrok(){
            var bentrok = this.jadwal_mapel.find(jm =>{
                return this.jam_selesai > jm.jam_mulai && this.jam_mulai < jm.jam_selesai && this.jam_mulai + ':00' != jm.jam_selesai
            })

            if (bentrok) {
                this.conditionCekLintasJadwalBentrok = false
            }else{
                this.conditionCekLintasJadwalBentrok = true
            }

            console.log('bentrok', bentrok);
        },
        async closeCreateJadwalMapel(){
            this.$emit('closeCreateJadwalMapel')
        },
        async createJadwalMapel(){

            this.$v.$touch();

            if (this.$v.$anyError) {
                return;
            }
            try{
                await this.$store.dispatch('view_jadwal/CREATE_JADWAL_MAPEL', 
                {
                    'form': {
                        'id_jadwal_lab': this.column.id,
                        'id_kelas': this.column.id_kelas,
                        'id_mapel': this.id_mapel,
                        'id_guru': this.id_guru,
                        'jam_mulai': this.jam_mulai,
                        'jam_selesai': this.jam_selesai,
                    }, 
                    'id': this.$route.params.id 
                })
                this.$bvToast.show('toast-created-jm')
                this.id_mapel = ''
                this.id_guru = ''
                this.jam_mulai = ''
                this.jam_selesai = ''
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