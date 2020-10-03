<template>
  <div>
        <b-btn v-b-modal.modalCreateJadwalHari variant="light" class="mx-2 rouded" style="width: 250px; height: 55px">
            <div class="d-flex justify-content-center align-items-center"><small class="mb-0" style="color: lightseagreen"><b>Create new day</b></small></div>
        </b-btn>
       <b-modal v-if="jadwals != {}" id="modalCreateJadwalHari" title="Create Poli Day Schedule" size="lg" :hide-footer="true">
            <div>
                <b-form @submit.stop.prevent="create">
                    <div class="mb-3 select-p">
                        <label for="hari">Day :</label>
                        <select name="" v-model.trim="$v.hari.$model" class="custom-select mb-0" id="hari">
                            <option disabled value="" style="">- - -</option>
                            <option v-for="hari in haris" :key="hari" v-bind:value="hari">{{hari}}</option>
                        </select>
                        <div class="error" v-if="!$v.hari.required && $v.hari.$anyDirty">Day is required.</div>
                        <div class="error" v-if="!$v.hari.isUnique && $v.hari.$anyDirty">Day is already used.</div>
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
            </div>
        </b-modal>
        <b-toast id="toast-created" title="Schedule successfuly created." variant="success" toaster="b-toaster-bottom-right">
            Day will be added in Schedule.
        </b-toast>
  </div>
</template>

<script>

import { required } from 'vuelidate/lib/validators'
import { mapState  } from 'vuex'

export default {
    data(){
        return {
            haris: [
                'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'
            ],
            hari: ''
        }
    },
    computed: {
        ...mapState({
            jadwals: state => state.view_jadwal.items,
            isLoadingAction: state => state.isLoadingAction,
        })
    },
    validations: {
        hari: {
            required,
            async isUnique (value) {

                if (value === '') return true

                if (await this.jadwals.jadwal_hari.find(p => p.hari === value)) {
                    return false
                }
                else{
                    return true
                }
            } 
        },
    },
    methods: {
        async create(){

            this.$v.$touch();

            if (this.$v.$anyError) {
                return;
            }

            try{
                let id = this.$route.params.id;
                await this.$store.dispatch('view_jadwal/CREATE_JADWAL_HARI', {'hari': this.hari, 'id_lab': id})
                await this.$bvToast.show('toast-created')
                this.hari= ''
                await this.$v.$reset()

            }catch(err){
                alert(err);
            }
        }
    }
}
</script>

<style>

</style>