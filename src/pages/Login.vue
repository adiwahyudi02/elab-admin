<template>
    <div style="
    width: 50%;
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);">
        <form @submit.prevent="login">
            <div>
                <h3 style="color: lightseagreen" class="mb-5"><b>Login.</b></h3>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="text" v-model.trim="$v.form.email.$model" class="form-control form-control-sm mb-0" id="email">
                <div class="error" v-if="!$v.form.email.required && $v.form.email.$anyDirty">Email is required.</div>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" v-model.trim="$v.form.password.$model" class="form-control form-control-sm mb-0" id="password">
                <div class="error" v-if="!$v.form.password.required && $v.form.password.$anyDirty">Password is required.</div>
            </div>
            <b-button type="submit" class="btn btn-info btn-sm px-5">
                <div v-if="isLoadingAction">
                    <b-spinner variant="light" type="grow" label="Spinning" small></b-spinner>
                    <b-spinner variant="light" type="grow" label="Spinning" small></b-spinner>
                    <b-spinner variant="light" type="grow" label="Spinning" small></b-spinner>
                </div>
                <div v-else>
                    Login
                </div>
            </b-button>
            <p v-if="error" class="error">Bad login information</p>
        </form>
    </div>
</template>

<script>
import { required } from 'vuelidate/lib/validators'
import { mapState } from 'vuex'
import Auth from '../Auth'

export default {
    data(){
        return{
            form: {
                email: '',
                password: '',
            },
            
            error: ''
        }
    },
    computed: {
        asd (){
            return Auth.loggedIn()
        },
        ...mapState({
            isLoadingAction: state => state.isLoadingAction
        })
    },
    validations: {
        form: {
            email: {
                required
            },
            password: {
                required
            }
        },
    },
    methods: {
        async login(){

            this.$v.form.$touch();

            if (this.$v.form.$anyError) {
                return;
            }

            try{
                await this.$store.commit('SET_ISLOADING_ACTION', true, { root: true })
                console.log(this.form.email, this.form.password);
                await Auth.login(this.form.email, this.form.password, loggedIn => {
                    if (!loggedIn) {
                        this.error = true
                    } else {
                        // this.$router.replace(this.$route.query.redirect || '/')
                        window.location = "/";
                    }
                })
                await this.$store.commit('SET_ISLOADING_ACTION', false, { root: true })
            }catch(err){
                this.error = err
            }
        }
    }
}
</script>