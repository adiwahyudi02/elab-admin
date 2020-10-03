<template>
  <Main>
    <div>
        <div class="bg-info navbar fixed-top d-flex justify-content-between align-items-center">
            <div>
                <h5 class="text-white mb-0 ml-3">Profile</h5>
            </div>
            <div>
                <div>
                    <i v-on:click='showMobileMenu = !showMobileMenu' class="fa fa-ellipsis-v text-white" aria-hidden="true"></i>
                </div>
                <div v-if="showMobileMenu" class="mt-1">
                    <router-link to="/app-teacher/logout-teacher" style="z-index: 99; position: fixed; margin-left: -80px">
                        <b-button class="btn btn-light btn-sm px-4 py-2">Log out</b-button>                        
                    </router-link>
                    <div class="" style="width: 100%; height: 100vh; position: fixed; z-index: 9; top: 0; left:0;" @click="showMobileMenu = false"></div>
                </div>        
            </div>
        </div>
        
        <div class="mt-5">
            <div>
                <b-form @submit.stop.prevent="update" class="px-2">
                    <div>
                        <div class="form-group">
                            <label style="font-size: 9pt;color: blue;margin-left: 5px;" for="nip">NIP:</label>
                            <input type="number" v-model.trim="$v.user.nip.$model" id="nip" class="form-control mb-0">
                            <div class="error" v-if="!$v.user.nip.required && $v.user.nip.$anyDirty">NIP is required.</div>
                            <div class="error" v-if="!$v.user.nip.isUnique && $v.user.nip.$anyDirty">NIP is already registered.</div>
                        </div>
                    </div>
                    <div>
                        <div class="form-group">
                            <label style="font-size: 9pt;color: blue;margin-left: 5px;" for="name">Name:</label>
                            <input type="text" v-model.trim="$v.user.nama.$model" id="name" class="form-control mb-0">
                            <div class="error" v-if="!$v.user.nama.required && $v.user.nama.$anyDirty">Name is required.</div>
                        </div>
                    </div>

                    <div>
                        <div class="form-group">
                            <label style="font-size: 9pt;color: blue;margin-left: 5px;" for="Username">Username:</label>
                            <input type="text" v-model.trim="$v.user.username.$model" id="Username" class="form-control mb-0">
                            <div class="error" v-if="!$v.user.username.required && $v.user.username.$anyDirty">Username is required.</div>
                        </div>
                    </div>

                    <div>
                        <div class="form-group">
                            <label style="font-size: 9pt;color: blue;margin-left: 5px;" for="email">Email:</label>
                            <input type="text" v-model.trim="$v.user.email.$model" id="email" class="form-control mb-0">
                            <div class="error" v-if="!$v.user.email.required && $v.user.email.$anyDirty">Email is required.</div>
                            <div class="error" v-if="!$v.user.email.isUnique && $v.user.email.$anyDirty">Email is already registered.</div>
                        </div>
                    </div>

                    <div>
                        <div class="form-group">
                            <label style="font-size: 9pt;color: blue;margin-left: 5px;" for="password">Password</label>
                            <div class="d-flex justify-content-start align-items-center">
                                <div style="width: 95%">
                                    <input :type="passwordFieldType" class="form-control mb-0" id="password" v-model.trim="$v.user.password.$model"/>
                                </div>
                                <div>
                                    <b-button class="btn btn-sm py-1 px-2 rounded" @click="switchVisibility">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye align-middle"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    </b-button>
                                </div>
                            </div>
                            <div class="error" v-if="!$v.user.password.required && $v.user.password.$anyDirty">Password is required.</div>
                            <div class="error" v-if="!$v.user.password.minLength">Password must have at least {{ $v.user.password.$params.minLength.min }} letters.</div>
                        </div>
                    </div>
            
                    <div class="d-flex justify-content-end align-items-center">
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
                
                <b-toast id="toast-updated" title="Profile successfuly to updated." variant="success" toaster="b-toaster-bottom-right">
                    Row will be updated.
                </b-toast>
            </div>
        </div>
    </div>
  </Main>
</template>

<script>

import Main from '../../layouts/app-guru/Main'
import { required, minLength } from 'vuelidate/lib/validators'
import { mapState  } from 'vuex'

export default {
    data() {
        return {
			user: '',
            pre_email: '',
            pre_nip: '',
            passwordFieldType: 'password',
            showMobileMenu: false
        }
    },
    components: {
        Main
    },
    computed: {
        ...mapState({
            guru: state => state.guru.items,
            isLoadingAction: state => state.isLoadingAction
        })
    },
    validations: {
        user: {
            nama: {required},
            nip: {
                required,
                isUnique (value) {
                    
                    if (this.pre_nip == value) {
                        return true
                    }
                    else{
                        if (value === '') return true
                        if (this.guru.find(user => user.nip == value && user.id_guru != this.user.id)) {
                            return false
                        }
                        else{
                            return true
                        }
                    }
                }
            },
			email: {
				required,
				isUnique (value) {

					if (this.pre_email == value) {
						return true  
					}else{
						if (value === '') return true
		
						if (this.guru.find(user => user.email === value)) {
							return false
						}
						else{
							return true
						}
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
		}
    },
    methods: {
		switchVisibility(){
			this.passwordFieldType = this.passwordFieldType === 'password' ? 'text' : 'password'
		},
		async redirectUpdateProfile(){
            try{
                await this.$store.commit('SET_ISLOADING_ACTION', true, { root: true })
                await this.$store.dispatch('admin/REFRESH_GET_ALL')
                await this.$store.commit('SET_ISLOADING_ACTION', false, { root: true })
                this.$bvModal.show( "profile")
            }catch(err) {
                alert(err);
            }
		},
		async update(){

            this.$v.user.$touch();

            if (this.$v.user.$anyError) {
                return;
            }

            try{
                const form = {
                    'id_guru': this.user.id,
                    'nip': this.user.nip,
                    'nama_guru': this.user.nama,
                    "username" : this.user.username,
                    "email": this.user.email,
                    "password": this.user.password,
                    "role": this.user.role
                }
                await this.$store.dispatch('guru/UPDATE', form)

                let local = JSON.parse(localStorage.getItem('auth'));
                const data = {
                    'id': this.user.id,
                    'nip': this.user.nip,
                    'nama': this.user.nama,
                    "username" : this.user.username,
                    "email": this.user.email,
                    "password": this.user.password,
                    "role": this.user.role
                }
                localStorage.setItem('auth', JSON.stringify({
                    'data': data,
                    'token': local.token
                }));
                this.$bvToast.show('toast-updated')
            }catch(err){
                alert(err);
            }
        }
	},
    async created(){
		try {

			let data = JSON.parse(localStorage.getItem('auth'));
            this.pre_email = data.data.email
            this.pre_nip = data.data.nip
            this.user = data.data
            
            await this.$store.dispatch('guru/GET_ALL')

		} catch (error) {
			alert(error)
		}
        
    }
}
</script>

<style>

</style>