<template>
    <b-modal v-if="data != {}" id="modalUpdate" title="Edit Teacher" size="lg" :hide-footer="true">
        <b-form @submit.stop.prevent="update">
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
        <b-toast id="toast-updated" title="lab successfuly to updated." variant="success" toaster="b-toaster-bottom-right">
            Row will be updated.
        </b-toast>
    </b-modal>
</template>

<script>
import { required,minLength } from 'vuelidate/lib/validators'
import { mapState  } from 'vuex'
export default {
    data(){
        return{
            form: '',
            pre_nip: '',
            pre_email: ''
        }
    },
    computed: {
        ...mapState({
            data (state) {
                let info = JSON.parse(JSON.stringify(state.guru.info)) 
                this.form = info
                let pre = JSON.parse(JSON.stringify(state.guru.info))
                this.pre_nip = pre.nip
                this.pre_email = pre.email
                
            },
            guru: state => state.guru.items,
            isLoadingAction: state => state.isLoadingAction            
        })
    },
    validations: {
        form: {
            nip: {
                required,
                isUnique (value) {
                    
                    if (this.pre_nip == value) {
                        return true
                    }
                    else{
                        if (value === '') return true
                        if (this.guru.find(user => user.nip == value)) {
                            return false
                        }
                        else{
                            return true
                        }
                    }
                }
            },
            nama_guru: {
                required
            },
            email: {
                required,
                isUnique (value) {

                    if (this.pre_email == value) {
                        return true
                    }
                    else{
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
        },
    },
    methods:{

        async update(){

            this.$v.form.$touch();

            if (this.$v.form.$anyError) {
                return;
            }

            try{
                await this.$store.dispatch('guru/UPDATE', this.form)
                this.$bvToast.show('toast-updated')
            }catch(err){
                alert(err);
            }
        }
    },
}
</script>