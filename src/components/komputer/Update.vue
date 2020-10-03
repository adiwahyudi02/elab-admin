<template>
    <b-modal v-if="data != {}" id="modalUpdate" title="Edit class" size="lg" :hide-footer="true">
        <b-form @submit.stop.prevent="update">
            <div class="form-group">
                <label for="lab">Computer name:</label>
                <input type="text" v-model.trim="$v.form.nama_komputer.$model" class="form-control form-control-sm mb-0" id="lab">
                <div class="error" v-if="!$v.form.nama_komputer.required && $v.form.nama_komputer.$anyDirty">Computer name is required.</div>
            </div>
            <div class="form-group">
                <label for="ip_address">Ip address:</label>
                <div>
                    <vue-ip :ip="form.ip_address" :port="false" :on-change="change" theme="material" id="ip_address"></vue-ip>
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
        <b-toast id="toast-updated" title="lab successfuly to updated." variant="success" toaster="b-toaster-bottom-right">
            Row will be updated.
        </b-toast>
    </b-modal>
</template>

<script>
import { required } from 'vuelidate/lib/validators'
import { mapState  } from 'vuex'
import VueIp from 'vue-ip';

var ip_address_glob = '';

export default {
    data(){
        return{
            form: '',
            pre_ip_address: '',
            valid: false,
            unique: false,
        }
        
    },
    components: {
        VueIp
    },
    computed: {
        ...mapState({
            data (state) {
                const info = Object.assign({}, state.komputer.info)
                this.form = info
                this.pre_ip_address = info.ip_address
            },
            komputer: state => state.komputer.items,
            labs: state => state.lab.items,
            isLoadingAction: state => state.isLoadingAction,
            
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

        change(ip, port, valid) {
            console.log('ip', ip);
            ip_address_glob = ip
            console.log('ip', ip_address_glob);
            this.valid = valid
            if (this.pre_ip_address == ip_address_glob) {
                this.unique = true   
            }else{
                if (this.komputer.find(komputer => komputer.ip_address === ip)) {
                    this.unique = false
                }else{
                    this.unique = true
                }
            }
        },

        async update(){

            this.$v.form.$touch();

            if (this.$v.form.$anyError) {
                return;
            }

            try{
                console.log('send_ip', ip_address_glob);
                await this.$store.dispatch('komputer/UPDATE', {
                    'id_komputer': this.form.id_komputer,
                    'nama_komputer': this.form.nama_komputer,
                    'ip_address': ip_address_glob,
                    'spesifikasi': this.form.spesifikasi,
                    'id_lab': this.form.id_lab
                })
                this.$bvToast.show('toast-updated')
            }catch(err){
                alert(err);
            }
        }
    },
}
</script>