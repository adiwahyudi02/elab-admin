<template>
    <div>
        <b-btn @click="redirectCreate" class="m-1 btn" style="border: none">Create new</b-btn>

        <b-modal id="modalCreate" title="Create Student" size="lg" :hide-footer="true">
            <b-form @submit.stop.prevent="create">
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
                            <input type="number" v-model.trim="$v.form.no_telp.$model" id="No.telp" class="form-control mb-0">
                            <div class="error" v-if="!$v.form.no_telp.required && $v.form.no_telp.$anyDirty">No.telp is required.</div>
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
                        <textarea v-model.trim="$v.form.address.$model" class="form-control form-control-sm mb-0" id="cm" cols="30" rows="6"></textarea>
                        <div class="error" v-if="!$v.form.address.required && $v.form.address.$anyDirty">Address is required.</div>
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
                            <input type="password" class="form-control mb-0" id="password" v-model.trim="$v.form.password.$model"/>
                            <div class="error" v-if="!$v.form.password.required && $v.form.password.$anyDirty">Password is required.</div>
                            <div class="error" v-if="!$v.form.password.minLength">Password must have at least {{ $v.form.password.$params.minLength.min }} letters.</div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="form-group">
                            <label for="r_password">Repeat password</label>
                            <input type="password" class="form-control mb-0" id="r_password" v-model.trim="$v.form.repeatPassword.$model"/>
                            <div class="error" v-if="!$v.form.repeatPassword.sameAsPassword">Passwords must be identical.</div>
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
        </b-modal>
        <b-toast id="toast-created" title="Student successfuly to created." variant="success" toaster="b-toaster-bottom-right">
            Row will be added in the students table.
        </b-toast>
    </div>
</template>

<script>
import { mapState  } from 'vuex'
import { required, minLength, sameAs } from 'vuelidate/lib/validators'

export default {
    data(){
        return{
            form: {
                nis: '',
                nama: '',
                username: '',
                jenis_kelamin: '',
                no_telp: '',
                ttl: '',
                address: '',
                email: '',
                id_kelas: '',
                id_komputer: '',
                password: '',
                repeatPassword: ''
            },
            keyword_nama_for_add: '',
            results_search_nama_add: ''
        }
    },
    computed: {
        ...mapState({
            kelas: state => state.kelas.items,
            siswa: state => state.siswa.items,
            komputer: state => state.komputer.items,
            isLoadingAction: state => state.isLoadingAction,
            isLoadingSearch: state => state.isLoadingSearch
        })
    },
    watch:{
        keyword_nama_for_add() {
            this.searchAddDokter();
        }
    },
    validations: {
        form: {
            nis: {
                required,
                isUnique (value) {

                    if (value === '') return true
    
                    if (this.siswa.find(user => user.nis === value)) {
                        return false
                    }
                    else{
                        return true
                    }
                }
            },
            nama: {required},
            no_telp: {required},
            id_kelas: {required},
            jenis_kelamin: {required},
            ttl: {required},
            address: {required},
            email: {required},
            id_komputer: {required},
            username: {
                required,
                isUnique (value) {

                    if (value === '') return true
    
                    if (this.siswa.find(user => user.username === value)) {
                        return false
                    }
                    else{
                        return true
                    }
                }   
            },
            password: {
                required,
                minLength: minLength(6)
            },
            repeatPassword: {
                sameAsPassword: sameAs('password')
            }
        },
    },
    methods:{

        async redirectCreate(){
            try{
                await this.$store.commit('SET_ISLOADING_ACTION', true, { root: true })
                await this.$store.dispatch('kelas/REFRESH_GET_ALL')
                await this.$store.dispatch('komputer/REFRESH_GET_ALL')
                await this.$store.commit('SET_ISLOADING_ACTION', false, { root: true })
                this.$bvModal.show( "modalCreate")
            }catch(err) {
                alert(err);
            }
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

        async create(){

            this.$v.form.$touch();

            if (this.$v.form.$anyError) {
                return;
            }

            try{
                await this.$store.dispatch('siswa/CREATE', this.form)
                this.form.nis = ''
                this.form.nama = ''
                this.form.username = ''
                this.form.jenis_kelamin = ''
                this.form.no_telp = ''
                this.form.ttl = ''
                this.form.address = ''
                this.form.email = ''
                this.form.id_kelas = ''
                this.form.id_komputer = ''
                this.form.password = ''
                this.form.repeatPassword = ''
                this.keyword_nama_for_add = ''
                this.results_search_nama_add = ''
                await this.$bvToast.show('toast-created')
                await this.$v.$reset()
                
            }catch(err){
                alert(err);
            }
        }
    }
}
</script>