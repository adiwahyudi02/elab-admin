<template>
    <div>
        <b-btn @click="redirectCreate" class="m-1 btn" style="border: none">Create new</b-btn>
        <b-modal id="modalCreate" title="Create Teacher" size="lg" :hide-footer="true">
            <b-form @submit.stop.prevent="create">
                <div>
                    <div class="form-group">
                        <label for="nip">NIP:</label>
                        <input type="number" v-model.trim="$v.form.nip.$model" id="nip" class="form-control mb-0">
                        <div class="error" v-if="!$v.form.nip.required && $v.form.nip.$anyDirty">NIP is required.</div>
                        <div class="error" v-if="!$v.form.nip.isUnique && $v.form.nip.$anyDirty">NIP is already registered.</div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="name">Teacher name:</label>
                    <input type="text" v-model.trim="$v.form.nama_guru.$model" class="form-control form-control-sm mb-0" id="name">
                    <div class="error" v-if="!$v.form.nama_guru.required && $v.form.nama_guru.$anyDirty">Class name is required.</div>
                </div>

                <div>
                    <div class="form-group">
                        <label for="Username">Username:</label>
                        <input type="text" v-model.trim="$v.form.username.$model" id="Username" class="form-control mb-0">
                        <div class="error" v-if="!$v.form.username.required && $v.form.username.$anyDirty">Username is required.</div>
                    </div>
                </div>

                <div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="text" v-model.trim="$v.form.email.$model" id="email" class="form-control mb-0">
                        <div class="error" v-if="!$v.form.email.required && $v.form.email.$anyDirty">Email is required.</div>
                        <div class="error" v-if="!$v.form.email.isUnique && $v.form.email.$anyDirty">Email is already registered.</div>
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
            </b-form>
            <b-toast id="toast-created" title="class successfuly to created." variant="success" toaster="b-toaster-bottom-right">
                Row will be added in the Class table.
            </b-toast>
        </b-modal>
    </div>
</template>

<script>

import { required, minLength, sameAs } from 'vuelidate/lib/validators'
import { mapState  } from 'vuex'

export default {
    data(){
        return{
            form: {
                nip: '',
                nama_guru: '',
                username: '',
                email: '',
                password: '',
                repeatPassword: ''
            }
        }
    },
    computed: {
        ...mapState({
            guru: state => state.guru.items,
            isLoadingAction: state => state.isLoadingAction
        })
    },
    validations: {
        form: {
            nip: {
                required,
                isUnique (value) {

                    if (value === '') return true
    
                    if (this.guru.find(user => user.nip === value)) {
                        return false
                    }
                    else{
                        return true
                    }
                }
            },
            nama_guru: {
                required
            },
            email: {
                required,
                isUnique (value) {

                    if (value === '') return true
    
                    if (this.guru.find(user => user.email === value)) {
                        return false
                    }
                    else{
                        return true
                    }
                }   
            },
            username: {
                required
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
                await this.$store.dispatch('mapel/REFRESH_GET_ALL')
                await this.$store.commit('SET_ISLOADING_ACTION', false, { root: true })
                this.$bvModal.show( "modalCreate")
            }catch(err) {
                alert(err);
            }
        },

        async create(){

            this.$v.form.$touch();

            if (this.$v.form.$anyError) {
                return;
            }

            try{
                await this.$store.dispatch('guru/CREATE', this.form)
                await this.$bvToast.show('toast-created')
                this.form.nip = ''
                this.form.nama_guru = ''
                this.form.username = ''
                this.form.email = ''
                this.form.password = ''
                this.form.repeatPassword = ''
                await this.$v.$reset()
            }catch(err){
                alert(err);
            }
        }
    },
}
</script>