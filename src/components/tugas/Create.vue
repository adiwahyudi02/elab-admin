<template>
    <div>
        <b-btn v-b-modal.modalCreate class="m-1 btn" style="border: none">Create new</b-btn>
        <b-modal id="modalCreate" title="Create Class" size="lg" :hide-footer="true">
            <b-form @submit.stop.prevent="create">
                <div class="form-group">
                    <label for="lab">Class name:</label>
                    <input type="text" v-model.trim="$v.form.kelas.$model" class="form-control form-control-sm mb-0" id="lab">
                    <div class="error" v-if="!$v.form.kelas.required && $v.form.kelas.$anyDirty">Class name is required.</div>
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

export default {
    data(){
        return{
            form: {
                kelas: ''
            }
        }
    },
    computed: {
        ...mapState({
            isLoadingAction: state => state.isLoadingAction
        })
    },
    validations: {
        form: {
            kelas: {
                required
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
                await this.$store.dispatch('kelas/CREATE', this.form)
                await this.$bvToast.show('toast-created')
                this.form.kelas = ''
                await this.$v.$reset()
            }catch(err){
                alert(err);
            }
        }
    },
}
</script>