<template>
  <div v-if="data != '' && form != undefined && jadwals != '' && column != ''">
      {{cekLintasJadwalBentrok()}}
      <b-modal id="modalInfo" :title="form.hari + ' - ' + nama_kelas + ' | ' + form.jam_mulai + ' - ' + form.jam_selesai" hide-footer ok-only size="md">
            <div class="d-flex justify-content-end">
                <b-button id="popover-button-variant" class="btn btn-sm btn-danger" href="#" tabindex="0">Delete this class schedule</b-button>
                <b-popover placement="left" target="popover-button-variant" variant="seondary" triggers="focus">
                    <p class="mb-1"> Are you sure ?</p>
                    <b-button class="btn btn-sm container" variant="primary" @click="softDelete(form.id)">
                        Yes
                    </b-button>
                </b-popover>
            </div>
          <b-tabs fill
                active-nav-item-class="font-weight-bold text-primary"
                content-class="mt-3"
                class="mt-3"
            >
                <b-tab title="Subjects Schedule" active>
                    <b-toast id="toast-other-switch-mapel" title="Information switch schedule." no-auto-hide :noCloseButton="true" variant="info" toaster="b-toaster-bottom-right">
                        <div class="d-flex justify-content-between">
                            <p class="mr-2">
                                Choose one other schedule you want switch. 
                            </p>
                            <div>
                                <b-button variant="danger" @click="CancelSwitch" title="esc">Cancel</b-button>
                            </div>
                        </div>
                    </b-toast>

                    
                    <b-toast id="toast-switched-mapel" title="Successfuly to exchanged." variant="success" toaster="b-toaster-bottom-right">
                        Schedule will be exchanged.
                    </b-toast>
                    <div v-if="jadwal_mapel == ''">
                        <small style="color: grey"> No have subject shedule </small>
                    </div>
                    <div v-for="card in jadwal_mapel" :key="card.id" class="py-1" >
                        <b-btn variant="light" class="rouded p-0" style="width: 100%" @click.left.exact="redirectInfoMapel(card)" @click.shift="switchMapel(card, {'id':form.id, 'hari': form.hari})">
                            <div class="card shadow-sm p-2 card-list card-dd-mapel" style="margin-bottom: 0" :id="form.id + '-' + card.id">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <small style="color: lightseagreen">{{card.jam_mulai}} - {{card.jam_selesai}}</small>
                                    </div>
                                </div>
                            <div style="font-size: 10pt;" class="d-flex justify-content-between">
                                <div>
                                    <p class="mb-0">
                                        {{ card.nama_mapel }}
                                    </p>
                                    <p class="mb-0">
                                        {{card.nama_guru}}
                                    </p>
                                </div>
                                <div>
                                    <i class="fas fa-pencil-alt ml-1 icon-edit-card p-2 rounded" style="font-size: 9pt;"></i>
                                </div>
                            </div>
                            
                            </div>
                        </b-btn>
                        
                    </div>
                    <b-btn v-if="!conditionCreateJadwalMapel" @click="redirectCreateMapel()"  variant="light" class="mx-2 mb-5 mt-3 rouded" style="width: 95%">
                        <div class="py-2 d-flex justify-content-center align-items-center"><small class="mb-0" style="color: lightseagreen"><b>Create new subject schedule</b></small></div>
                    </b-btn>
                    <CreateJadwalMapel @closeCreateJadwalMapel="closeCreateJadwalMapel" v-if="conditionCreateJadwalMapel" :column="jadwal_lab" />
                </b-tab>
                <b-tab title="Edit">
                    <b-form @submit.stop.prevent="updateJadwalKelas">
                        <div class="form-group">
                            <label style="font-size: 8pt" for="id_kelas">Class :</label>
                            <select name="" v-model.trim="$v.form.id_kelas.$model" class="custom-select custom-select-sm mb-0" id="id_kelas">
                                <option disabled value="" style="">- - -</option>
                                <option v-for="k in kelas" :key="k.id_kelas" :value="k.id_kelas">{{k.nama_kelas}}</option>
                            </select>
                            <div class="error" v-if="!$v.form.id_kelas.required && $v.form.id_kelas.$anyDirty">Class is required.</div>
                        </div>

                        <div>
                            <div class="form-group">
                                <label style="font-size: 8pt" for="edit_jam_mulai">Started at :</label>
                                <input type="time" v-model.trim="$v.form.jam_mulai.$model" class="form-control form-control-sm mb-0" id="edit_jam_mulai">
                                <div class="error" v-if="!$v.form.jam_mulai.required && $v.form.jam_mulai.$anyDirty">Started at is required.</div>
                                <div class="error" style="font-size: 8pt;" v-if="!$v.form.jam_mulai.isUnique && $v.form.jam_mulai.$anyDirty">Started at is already registered.</div>
                            </div>
                            
                        </div>

                        <div>
                            <div class="form-group">
                                <label style="font-size: 8pt" for="edit_jam_selesai">Ended at :</label>
                                <input type="time" v-model.trim="$v.form.jam_selesai.$model" class="form-control form-control-sm mb-0" id="edit_jam_selesai">
                                <div class="error" v-if="!$v.form.jam_selesai.required && $v.form.jam_selesai.$anyDirty">Ended at is required.</div>
                                <div class="error" style="font-size: 8pt;" v-if="!$v.form.jam_selesai.isUnique && $v.form.jam_selesai.$anyDirty">Ended at is already registered.</div>
                            </div>
                        </div>

                        <div>
                            <div class="error mb-2" style="font-size: 8pt;" v-if="!conditionCekLintasJadwalBentrok">
                                You have a conflict schedule because your started at and ended at accros a schedule. please check again !
                            </div>
                        </div>

                        <div>
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
                    </b-form>
                </b-tab>
            </b-tabs>
            <b-toast id="toast-updated-jd" title="Successfuly to updated." variant="success" toaster="b-toaster-bottom-right">
                Schedule will be updated.
            </b-toast>
      </b-modal>

        <b-toast id="toast-softdelete-jl" title="Schedule successfuly to softdelete." variant="success" toaster="b-toaster-bottom-right">
            Schedule will be put in trash.
        </b-toast>

      <InfoJadwalMapel :column="jadwal_lab" :jadwal_mapel="card" :jadwal_lab="jadwal_lab" />
  </div>
</template>

<script>
import { required } from 'vuelidate/lib/validators'
import { mapState  } from 'vuex'
import InfoJadwalMapel from './InfoJadwalMapel'
import CreateJadwalMapel from './CreateJadwalMapel'


export default {
    props: [
        'column'
    ],
    data(){
        return {
            nama_kelas: '',
            hari: '',
            form: {
                id: '',
                id_hari: '',
                hari: '',
                id_kelas: '',
                jam_mulai: '',
                jam_selesai: ''
            },
            jadwal_lab: '',
            jadwal_mapel: '',
            dataSwitch: [],
            card: '',
            conditionInfoJadwalMapel: false,
            conditionCreateJadwalMapel: false,
            conditionCekLintasJadwalBentrok: true
        }
    },
    components: {
        InfoJadwalMapel, CreateJadwalMapel
    },
    computed: {
        ...mapState({
            isLoadingAction: state => state.isLoadingAction,
            data (state) {
                const info = JSON.parse(JSON.stringify(state.view_jadwal.info))
                this.form = info.jadwal_lab
                this.jadwal_lab = info.jadwal_lab
                this.jadwal_mapel = info.jadwal_mapel
                this.dataSwitch = []
            },
            jadwals(state){
                const jadwals = state.view_jadwal.items
                return jadwals
            },
            kelas(state){
                const info = Object.assign({}, state.view_jadwal.info)
                state.kelas.items.find(x =>{
                    if (x.id_kelas == info.jadwal_lab.id_kelas) {
                        this.nama_kelas = x.nama_kelas
                    }
                })
                return state.kelas.items
            },
        })
    },
    validations: {
        form: {
            id_hari: {required},
            id_kelas: {required},
            jam_mulai: {
                required,
                isUnique (value) {

                    if (value === '') return true

                    const cek1 = this.column.jadwal_lab.find(jl => {
                        return value + ':00' == jl.jam_mulai
                    })
                    if (cek1) {
                        if(this.jadwal_lab.id == cek1.id){
                            return true
                        }
                    }
                    const cek2 = this.column.jadwal_lab.find(jl => {
                        return value > jl.jam_mulai && value < jl.jam_selesai
                    })
                    if (cek2) {
                        if (this.jadwal_lab.id == cek2.id) {
                            return true
                        }
                    }

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
                
                    const cek1 = this.column.jadwal_lab.find(jl => {
                        return value > jl.jam_mulai && value < jl.jam_selesai
                    })
                    if (cek1) {
                        if(this.jadwal_lab.id == cek1.id){
                            return true
                        }
                    }
                    
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
        }
    },
    watch: {
        // jam_mulai(){
        //     this.cekLintasJadwalBentrok()
        // },
        // jam_selesai(){
        //     this.cekLintasJadwalBentrok()
        // },
        'form.jam_mulai': function() {
            this.cekLintasJadwalBentrok()
        },
        'form.jam_selesai': function() {
            this.cekLintasJadwalBentrok()
        }
    },
    methods: {

        async cekLintasJadwalBentrok(){
            if (this.column != '') {
                var bentrok = this.column.jadwal_lab.filter(jl =>{
                    return this.form.jam_selesai > jl.jam_mulai && this.form.jam_mulai < jl.jam_selesai && this.form.jam_mulai + ':00' != jl.jam_selesai
                })

                if (bentrok.length == 1) {
                    if (bentrok[0].id == this.form.id) {
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

                console.log('bentrok', bentrok);         
            }
            
        },

        async switchMapel(card, column){
            this.dataSwitch.push({
                'card': card, 
                'column': column
            })
            console.log('cc', column, card);
            console.log('b', column.id + '-' + card.id)
            document.getElementById(column.id + '-' + card.id).style.backgroundColor = '#e6f1fc';
            if (this.dataSwitch.length == 2) {
                try {
                    await this.$store.dispatch('view_jadwal/SWITCH_MAPEL', this.dataSwitch)
                    const all = document.getElementsByClassName('card-dd-mapel');
                    for (var i = 0; i < all.length; i++) {
                        all[i].style.backgroundColor = 'white';
                    }
                    this.dataSwitch = []
                    this.$bvToast.hide('toast-other-switch-mapel')
                    this.$bvToast.show('toast-switched-mapel')
                } catch (error) {
                    alert(error)
                }
                
            }else if (this.dataSwitch.length < 2) {
                this.$bvToast.show('toast-other-switch-mapel')
            }else {
                const all = document.getElementsByClassName('card-dd-mapel');
                for (let i = 0; i < all.length; i++) {
                    all[i].style.backgroundColor = 'white';
                }
                this.dataSwitch = []
                this.dataSwitch.push({
                    'card': card, 
                    'column': column
                })
                document.getElementById(column.id + '-' + card.id).style.backgroundColor = '#e6f1fc';
                this.$bvToast.show('toast-other-switch-mapel')
            }
        },

        async CancelSwitch(){
            const all = document.getElementsByClassName('card-dd-mapel');
            for (var i = 0; i < all.length; i++) {
                all[i].style.backgroundColor = 'white';
            }
            // this.$bvToast.hide('toast-switching')
            this.$bvToast.hide('toast-other-switch-mapel')
            this.dataSwitch = []
            console.log(this.dataSwitch);
        },

        async redirectInfoMapel(card){
            await this.$store.dispatch('mapel/REFRESH_GET_ALL')
            await this.$store.dispatch('guru/REFRESH_GET_ALL')
            this.card = card
            // this.conditionInfoJadwalMapel = card.id
            await this.$bvModal.show("modalInfoMapel")
        },

        async redirectCreateMapel(){
            try{
                await this.$store.commit('SET_ISLOADING_ACTION', true, { root: true })
                this.conditionCreateJadwalMapel = true
                await this.$store.dispatch('mapel/REFRESH_GET_ALL')
                await this.$store.dispatch('guru/REFRESH_GET_ALL')
                await this.$store.commit('SET_ISLOADING_ACTION', false, { root: true })
                
            }catch(err) {
                alert(err);
            }
        },

        async closeCreateJadwalMapel(){
            this.conditionCreateJadwalMapel = false
        },

        async updateJadwalKelas(){
            this.$v.form.$touch();

            if (this.$v.form.$anyError) {
                return;
            }

            try{
                await this.$store.dispatch('view_jadwal/UPDATE_JADWAL_KELAS', {
                    'form': {
                        'jadwal_lab': this.form,
                        'jadwal_mapel': this.jadwal_mapel
                    },
                    'id': this.$route.params.id 
                })
                this.$bvToast.show('toast-updated-jd')

            }catch(err){
                alert(err);
            }
        },

        async softDelete(id){
            try{
                await this.$store.dispatch('view_jadwal/SOFTDELETE_ONE_JADWAL_KELAS', {
                    'id': id,
                    'id_lab': this.$route.params.id
                })
                this.$bvModal.hide("modalInfo")
                await this.$bvToast.show('toast-softdelete-jl')
            }catch(err) {
                alert(err);
            }
        },
    }
    
}
</script>

<style>

</style>