<template>
    <div>
        <b-btn @click="redirectCreate" class="m-1 btn" style="border: none">Create new</b-btn>
        <b-modal id="modalCreate" title="Create Computer" size="lg" :hide-footer="true">
            <b-form @submit.stop.prevent="create">
                <div class="form-group">
                    <label for="lab">Computer name:</label>
                    <input type="text" v-model.trim="$v.form.nama_komputer.$model" class="form-control form-control-sm mb-0" id="lab">
                    <div class="error" v-if="!$v.form.nama_komputer.required && $v.form.nama_komputer.$anyDirty">Computer name is required.</div>
                </div>
                <div class="form-group">
                    <label for="ip_address">Ip address:</label>
                    <div>
                        <vue-ip :ip="ip" :port="false" :on-change="change" theme="material" id="ip_address"></vue-ip>
                    </div>
                    <div class="error" v-if="!valid">Ip address is invalid.</div>
                    <div class="error" style="color: blue" v-if="!unique">Ip address is already exist.</div>
                </div>
                <div class="form-group">
                    <label for="cm">Spesification:</label>
                    <textarea v-model.trim="$v.form.spesifikasi.$model" class="form-control form-control-sm mb-0" id="cm" cols="30" rows="6"></textarea>
                    <div class="error" v-if="!$v.form.spesifikasi.required && $v.form.spesifikasi.$anyDirty">Spesification is required.</div>
                </div>
                <div class="form-group">
                    <label for="id_lab">Lab</label>
                    <select name="" v-model.trim="$v.form.id_lab.$model" class="custom-select mb-0" id="id_lab">
                        <option disabled value="" style="">- - -</option>
                        <option v-for="lab in labs" :key="lab.id_lab" :value="lab.id_lab">{{lab.nama_lab}}</option>
                    </select>
                    <div class="error" v-if="!$v.form.id_lab.required && $v.form.id_lab.$anyDirty">Lab is required.</div>
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

import { required } from 'vuelidate/lib/validators'
import { mapState  } from 'vuex'
import VueIp from 'vue-ip';

export default {
    data(){
        return{
            form: {
                nama_komputer: '',
                spesifikasi: '',
                id_lab: '',
                ip_address: ''
            },
            ip: '000.0.0.0', // or null
            valid: false,
            unique: false,
        }
    },
    components: {
        VueIp
    },
    computed: {
        ...mapState({
            komputer: state => state.komputer.items,
            labs: state => state.lab.items,
            isLoadingAction: state => state.isLoadingAction
        })
    },
    validations: {
        form: {
            nama_komputer: {
                required
            },
            spesifikasi: {
                required
            },
            id_lab: {
                required
            }
        },
    },
    methods:{

        async redirectCreate(){
            try{
                await this.$store.commit('SET_ISLOADING_ACTION', true, { root: true })
                await this.$store.dispatch('lab/REFRESH_GET_ALL')
                await this.$store.commit('SET_ISLOADING_ACTION', false, { root: true })
                this.$bvModal.show( "modalCreate")
            }catch(err) {
                alert(err);
            }
        },

        change(ip, port, valid) {
            this.form.ip_address = ip
            this.valid = valid
            if (this.komputer.find(komputer => komputer.ip_address === ip)) {
                this.unique = false
            }else{
                this.unique = true
            }
        },

        async create(){

            this.$v.form.$touch();

            if (this.$v.form.$anyError) {
                return;
            }

            try{
                await this.$store.dispatch('komputer/CREATE', this.form)
                await this.$bvToast.show('toast-created')
                this.form.nama_komputer = ''
                this.form.ip_address = ''
                this.form.spesifikasi = ''
                this.form.id_lab = ''
                await this.$v.$reset()
            }catch(err){
                alert(err);
            }
        }
    },
}
</script>