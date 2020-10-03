<template>
    <div>
        <b-btn v-b-modal.modalCreate class="m-1 btn" style="border: none">Create new</b-btn>
        <b-modal id="modalCreate" title="Create Class" size="lg" :hide-footer="true">
            <b-form @submit.stop.prevent="create">
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
            <b-toast id="toast-created" title="Admin successfuly to created." variant="success" toaster="b-toaster-bottom-right">
                Row will be added in the Admins table.
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
                nama: '',
                username: '',
                email: '',
                password: '',
                repeatPassword: ''
            }
        }
    },
    computed: {
        ...mapState({
            admin: state => state.admin.items,
            isLoadingAction: state => state.isLoadingAction
        })
    },
    validations: {
        form: {
            nama: {required},
            email: {
                required,
                isUnique (value) {

                    if (value === '') return true
    
                    if (this.admin.find(user => user.email === value)) {
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

        async create(){

            this.$v.form.$touch();

            if (this.$v.form.$anyError) {
                return;
            }

            try{
                await this.$store.dispatch('admin/CREATE', this.form)
                await this.$bvToast.show('toast-created')
                this.form.nama = ''
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