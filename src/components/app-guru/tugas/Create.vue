<template>
  <div>
    <div>
        <b-button v-b-modal.modal-create-tugas variant="info" class="btn btn-sm mr-2">Create new</b-button>
        <b-modal id="modal-create-tugas" title="Create task" :hide-footer="true">
            <b-form>
                <div>
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input type="text" v-model.trim="$v.form.title.$model" id="title" class="form-control mb-0">
                        <div class="error" v-if="!$v.form.title.required && $v.form.title.$anyDirty">Title is required.</div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="cm">Description:</label>
                    <textarea v-model="form.description" class="form-control form-control-sm mb-0" id="cm" cols="30" rows="6"></textarea>
                </div>
                <div>
                    <div class="form-group">
                        <label for="due_date">Due date:</label>
                        <input type="datetime-local" name="" id="due_date" v-model.trim="$v.form.due_date.$model" class="form-control form-control-sm mb-0">
                        <div class="error" v-if="!$v.form.due_date.required && $v.form.due_date.$anyDirty">Due date is required.</div>
                    </div>
                </div>
                <div>
                    <div class="form-group">
                        <label for="files">Attachment: </label>
                        <input type="file" id="files" ref="files" multiple v-on:change="handleFilesUpload()"/>
                    </div>

                    <div class="d-flex justify-content-start align-items-center flex-wrap">
                        <div class="d-flex justify-content-start align-items-center flex-wrap">
                            <div v-for="(file, key) in files" :key="key" class="mr-2 rounded px-3 py-2" style="max-width: 200px; border: solid 1px blue;">
                                <div class="d-flex justify-content-between align-items-center flex-wrap">
                                    <p class="mb-0 mr-2">{{ file.name }} </p>
                                    <div>
                                        <span class="remove-file" v-on:click="removeFile( key )"><b>x</b></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <b-button class="btn-sm p-2 px-3" variant="outline-primary" v-on:click="addFiles()"><b> + </b></b-button>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <b-button type="submit" class="btn btn-info btn-sm px-5 mt-3" @click="create">
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
        </b-modal>
        <b-toast id="toast-created" title="Task successfuly to created." variant="success" toaster="b-toaster-bottom-full">
            Row will be share to students.
        </b-toast>
    </div>
  </div>
</template>

<script>
import { mapState  } from 'vuex'
import { required } from 'vuelidate/lib/validators'
export default {
    data(){
        return{
            form: {
                id_jadwal_mapel: '',
                title: '',
                description: '',
                due_date: ''
            },
            files: []
        }
    },
    computed: {
        ...mapState({
            isLoadingAction: state => state.isLoadingAction
        })
    },
    validations: {
        form: {
            title: {required},
            due_date: {required}
        },
    },
    methods: {
        addFiles(){
            this.$refs.files.click();
        },
        /*
            Handles the uploading of files
        */
        handleFilesUpload(){
            let uploadedFiles = this.$refs.files.files;

            /*
            Adds the uploaded file to the files array
            */
            for( var i = 0; i < uploadedFiles.length; i++ ){
            this.files.push( uploadedFiles[i] );
            }
        },

        /*
            Removes a select file the user has uploaded
        */
        removeFile( key ){
            this.files.splice( key, 1 );
        },

        async create(){

            this.$v.form.$touch();

            if (this.$v.form.$anyError) {
                return;
            }

            try{

                let formData = new FormData();

                for( var i = 0; i < this.files.length; i++ ){
                let file = this.files[i];

                formData.append('files[' + i + ']', file);
                }

                formData.append('id_jadwal_mapel', this.$route.params.id);
                formData.append('title', this.form.title);
                formData.append('description', this.form.description);
                formData.append('status', 'On progress');
                formData.append('due_date', this.form.due_date);

                await this.$store.dispatch('tugas/CREATE', {'data': formData, 'id_jadwal_mapel': this.$route.params.id})
                this.form.title = ''
                this.form.description = ''
                this.form.due_date = ''
                this.files = []
                await this.$bvToast.show('toast-created')
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