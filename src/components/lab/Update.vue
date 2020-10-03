<template>
    <b-modal v-if="data != {}" id="modalUpdate" title="Edit lab" size="lg" :hide-footer="true">
        <b-form @submit.stop.prevent="update">
            <div class="form-group">
                <label for="lab">Lab name:</label>
                <input type="text" v-model.trim="$v.form.nama_lab.$model" class="form-control form-control-sm mb-0" id="lab">
                <div class="error" v-if="!$v.form.nama_lab.required && $v.form.nama_lab.$anyDirty">Lab name is required.</div>
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
export default {
    data(){
        return{
            form: ''
        }
    },
    computed: {
        ...mapState({
            data (state) {
                const info = Object.assign({}, state.lab.info)
                this.form = info
            },
            isLoadingAction: state => state.isLoadingAction,
            
        })
    },
    validations: {
        form: {
            nama_lab: {
                required
            }
        },
    },
    methods:{

        async update(){

            this.$v.form.$touch();

            if (this.$v.form.$anyError) {
                return;
            }

            try{
                await this.$store.dispatch('lab/UPDATE', this.form)
                this.$bvToast.show('toast-updated')
            }catch(err){
                alert(err);
            }
        }
    },
}
</script>