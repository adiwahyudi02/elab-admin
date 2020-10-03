<template>
    <b-modal v-if="data != ''" id="modalUpdate" title="Edit subject" size="lg" :hide-footer="true">
        <!-- {{searchAddDokter()}} -->
        <b-form @submit.stop.prevent="update">
            <div>
                <div>
                    <div class="form-group">
                        <label for="nis">NIS:</label>
                        <input type="number" v-model.trim="$v.form.nis.$model" id="nis" class="form-control mb-0">
                        <div class="error" v-if="!$v.form.nis.required && $v.form.nis.$anyDirty">NIS is required.</div>
                        <div class="error" v-if="!$v.form.nis.isUnique && $v.form.nis.$anyDirty">NIS is already registered.</div>
                    </div>
                </div>

                <div>
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" v-model.trim="$v.form.nama.$model" id="name" class="form-control mb-0">
                        <div class="error" v-if="!$v.form.nama.required && $v.form.nama.$anyDirty">Name is required.</div>
                    </div>
                </div>

                <div>
                    <div class="form-group">
                        <label for="Username">Username:</label>
                        <input type="text" v-model.trim="$v.form.username.$model" id="Username" class="form-control mb-0">
                        <div class="error" v-if="!$v.form.username.required && $v.form.username.$anyDirty">Username is required.</div>
                        <div class="error" v-if="!$v.form.username.isUnique && $v.form.username.$anyDirty">Username is already registered.</div>
                    </div>
                </div>

                <div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="text" v-model.trim="$v.form.email.$model" id="email" class="form-control mb-0">
                        <div class="error" v-if="!$v.form.email.required && $v.form.email.$anyDirty">Email is required.</div>
                    </div>
                </div>

                <div>
                    <label for="">Gender:</label>
                    <div class="d-flex justify-content-start align-items-center p-1 mb-1">
                        <input type="radio" name="" id="laki-laki" value="laki-laki" v-model.trim="$v.form.jenis_kelamin.$model" class="mr-3">
                        <label for="laki-laki" class="mb-0 container p-1">Male</label>
                    </div>
                    <div class="d-flex justify-content-start align-items-center p-1 mb-1">
                        <input type="radio" name="" id="perempuan" value="perempuan" v-model.trim="$v.form.jenis_kelamin.$model" class="mr-3">
                        <label for="perempuan" class="mb-0 container p-1">Female</label>
                    </div>
                    
                    <div class="error mb-2" v-if="!$v.form.jenis_kelamin.required && $v.form.jenis_kelamin.$anyDirty">Gender is required.</div>
                </div>

                <div>
                    <div class="form-group">
                        <label for="No.telp">No.telp:</label>
                        <input type="number" v-model.trim="$v.form.no_telepon.$model" id="No.telp" class="form-control mb-0">
                        <div class="error" v-if="!$v.form.no_telepon.required && $v.form.no_telepon.$anyDirty">No.telp is required.</div>
                    </div>
                </div>

                <div>
                    <div class="form-group">
                        <label for="ttl">Ttl:</label>
                        <input type="text" v-model.trim="$v.form.ttl.$model" id="ttl" class="form-control mb-0">
                        <div class="error" v-if="!$v.form.ttl.required && $v.form.ttl.$anyDirty">Ttl is required.</div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="cm">Address:</label>
                    <textarea v-model.trim="$v.form.alamat.$model" class="form-control form-control-sm mb-0" id="cm" cols="30" rows="6"></textarea>
                    <div class="error" v-if="!$v.form.alamat.required && $v.form.alamat.$anyDirty">Address is required.</div>
                </div>

                <div>
                    <div class="form-group">
                        <label for="id_kelas">Class</label>
                        <select name="" v-model.trim="$v.form.id_kelas.$model" class="custom-select mb-0" id="id_kelas">
                            <option disabled value="" style="">- - -</option>
                            <option v-for="k in kelas" :key="k.id_kelas" :value="k.id_kelas">{{k.nama_kelas}}</option>
                        </select>
                        <div class="error" v-if="!$v.form.id_kelas.required && $v.form.id_kelas.$anyDirty">Class is required.</div>
                    </div>
                </div>


                <div>
                    <div class="form-group">
                        <label for="sc">Search computer :</label>
                        <input type="text" placeholder="search for computer and choose option" v-model="keyword_nama_for_add" class="form-control mb-0" id="sc">
                    </div>
                    <div class="error" v-if="!$v.form.id_komputer.required && $v.form.id_komputer.$anyDirty">Please choose computer option below.</div>
                </div>

                <div class="mb-3" v-if="results_search_nama_add == ''">
                    <small style="color: darkgray">No result..</small>
                </div>

                <div v-if="isLoadingSearch" class="mb-2">
                    <b-spinner variant="info" type="grow" label="Spinning" small></b-spinner>
                    <b-spinner variant="info" type="grow" label="Spinning" small></b-spinner>
                    <b-spinner variant="info" type="grow" label="Spinning" small></b-spinner>
                </div>
                <div class="mb-3" v-for="result in results_search_nama_add" :key="result.id">
                    <div class="d-flex justify-content-start align-items-center bg-light p-1 rounded shadow-sm mb-1">
                        <input type="radio" name="" :id="'komputer-' + result.id_komputer" :value="result.id_komputer" v-model.trim="$v.form.id_komputer.$model" class="mr-3">
                        <label :for="'komputer-' + result.id_komputer" class="mb-0 container p-1">id : {{result.id_komputer}} - name : {{result.nama_komputer}} - ip address : {{result.ip_address}} - Lab : {{result.nama_lab}}</label>
                    </div>
                </div>

                <div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="d-flex justify-content-start align-items-center">
                            <div style="width: 95%">
                                <input :type="passwordFieldType" class="form-control mb-0" id="password" v-model.trim="$v.form.password.$model"/>
                            </div>
                            <div>
                                <b-button class="btn btn-sm py-1 px-2 rounded" @click="switchVisibility">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye align-middle"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                </b-button>
                            </div>
                        </div>
                        <div class="error" v-if="!$v.form.password.required && $v.form.password.$anyDirty">Password is required.</div>
                        <div class="error" v-if="!$v.form.password.minLength">Password must have at least {{ $v.form.password.$params.minLength.min }} letters.</div>
                    </div>
                </div>

                <div>
                    <b-button type="submit" class="btn btn-info btn-sm px-5">
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
        <b-toast id="toast-updated" title="Student successfuly to updated." variant="success" toaster="b-toaster-bottom-right">
            Row will be updated.
        </b-toast>
    </b-modal>
</template>

<script>
import { mapState  } from 'vuex'
import { required, minLength } from 'vuelidate/lib/validators'
export default {
    data(){
        return{
            form: '',
            keyword_nama_for_add: null,
            results_search_nama_add: '',
            pre_nis: '',
            pre_username: '',
            passwordFieldType: 'password'
        }
    },
    computed: {
        ...mapState({
            data (state) {
                const info = Object.assign({}, state.siswa.info)
                
                return info
            },
            kelas: state => state.kelas.items,
            siswa: state => state.siswa.items,
            komputer: state => state.komputer.items,
            isLoadingAction: state => state.isLoadingAction,
            isLoadingSearch: state => state.isLoadingSearch
            
        })
    },
    watch:{
        data(){
            this.pre_nis = this.data.nis
            this.pre_username = this.data.username
            this.form = this.data
            this.keyword_nama_for_add = this.data.nama_komputer
        },
        keyword_nama_for_add() {
            this.searchAddDokter();
        }
    },
    validations: {
        form: {
            nis: {
                required,
                isUnique (value) {

                    if (this.pre_nis == value) {
                        return true
                    }
                    else{
                        if (value === '') return true
        
                        if (this.siswa.find(user => user.nis === value)) {
                            return false
                        }
                        else{
                            return true
                        }
                    }
                }
            },
            nama: {required},
            no_telepon: {required},
            id_kelas: {required},
            jenis_kelamin: {required},
            ttl: {required},
            alamat: {required},
            email: {required},
            id_komputer: {required},
            username: {
                required,
                isUnique (value) {
                    if (this.pre_username == value) {
                        return true
                    }
                    else{
                        if (value === '') return true
        
                        if (this.siswa.find(user => user.username === value)) {
                            return false
                        }
                        else{
                            return true
                        }
                    }
                }   
            },
            password: {
                required,
                minLength: minLength(6)
            }
        },
    },
    methods:{

        switchVisibility(){
			this.passwordFieldType = this.passwordFieldType === 'password' ? 'text' : 'password'
		},
        async searchAddDokter(){
            try {
                console.log(this.keyword_nama_for_add);
                await this.$store.commit('SET_ISLOADING_SEARCH', true, { root: true })

                let query = this.keyword_nama_for_add.toLowerCase();
                const result = this.komputer.filter(item => item.nama_komputer.toLowerCase().indexOf(query) >= 0 || item.ip_address.indexOf(query) >= 0);
                this.results_search_nama_add = result.slice(0,5)
                await this.$store.commit('SET_ISLOADING_SEARCH', false, { root: true })
            } catch (error) {
                alert(error)
            }
        },

        async update(){

            this.$v.form.$touch();

            if (this.$v.form.$anyError) {
                return;
            }

            try{
                await this.$store.dispatch('siswa/UPDATE', this.form)
                this.$bvToast.show('toast-updated')
            }catch(err){
                alert(err);
            }
        }
    },
}
</script>