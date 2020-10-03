<template>
  <div>
    <div class="bg-info navbar fixed-top d-flex justify-content-between align-items-center">
        <div class="d-flex justify-content-start">
            <div class="mr-2">
                <a @click="$router.go(-1)"><i class="fas fa-arrow-left text-white" style="font-size: 13pt;"></i></a>
            </div>
            <div>
                <h5 class="text-white mb-0 ml-3">Task detail</h5>
            </div>
        </div>
        <div>
            <div>
                <i v-on:click='showMobileMenu = !showMobileMenu' class="fa fa-ellipsis-v text-white" aria-hidden="true"></i>
            </div>
            <div v-if="showMobileMenu" class="mt-1">
                <div style="z-index: 99; position: fixed; margin-left: -100px">
                    <b-button class="btn btn-light btn-sm px-4 py-2" @click="$bvToast.show('example-toast')">Delete this</b-button>
                </div>
                <div class="" style="width: 100%; height: 100vh; position: fixed; z-index: 9; top: 0; left:0;" @click="showMobileMenu = false"></div>
            </div>        
        </div>
    </div>
    <b-tabs v-if="info != ''"
        fill
        active-nav-item-class="font-weight-bold text-primary"
        class="mt-5">
        <b-tab active>
            <template v-slot:title>
                <p class="mb-0">Task</p>
            </template>
            <div>
                <div>
                    <b-form class="px-2" @submit.stop.prevent="update">
                        <div>
                            <div class="form-group">
                                <label for="title" style="font-size: 9pt;color: blue;margin-left: 5px;">Title:</label>
                                <input type="text" v-model.trim="$v.info.title.$model" id="title" class="form-control form-control-sm mb-0">
                                <div class="error" v-if="!$v.info.title.required && $v.info.title.$anyDirty">Title is required.</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="cm" style="font-size: 9pt;color: blue;margin-left: 5px;">Description:</label>
                            <textarea v-model="info.description" class="form-control form-control-sm mb-0" id="cm" cols="30" rows="4"></textarea>
                        </div>
                        <div>
                            <div class="form-group">
                                <label for="due_date" style="font-size: 9pt;color: blue;margin-left: 5px;">Due date:</label>
                                <input type="datetime-local" name="" id="due_date" v-model.trim="$v.info.due_date.$model" class="form-control form-control-sm mb-0">
                                <div class="error" v-if="!$v.info.due_date.required && $v.info.due_date.$anyDirty">Due date is required.</div>
                            </div>
                        </div>
                        <div>
                            <div class="form-group">
                                <label for="status" style="font-size: 9pt;color: blue;margin-left: 5px;">Status: </label>
                                <select name="" v-model.trim="$v.info.status.$model" class="custom-select custom-select-sm mb-0" id="status">
                                    <option disabled value="" style="">- - -</option>
                                    <option v-for="value in status" :key="value" :value="value">{{value}}</option>
                                </select>
                                <div class="error" v-if="!$v.info.status.required && $v.info.status.$anyDirty">Status is required.</div>
                            </div>
                        </div>
                        <div>
                            <div class="form-group mb-0">
                                <label for="files" style="font-size: 9pt;color: blue;margin-left: 5px;">Attachment: </label>
                            </div>

                            <div class="d-flex justify-content-start align-items-center flex-wrap">
                                <div class="d-flex justify-content-start align-items-center flex-wrap">
                                    <div v-for="data in info.attachments" :key="data.id_attachment" class="mr-2 rounded px-3 py-2 mb-1" style="max-width: 200px; border: solid 1px blue;">
                                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                                            <p class="mb-0 mr-2" @click="downloadBase64File(data.type, data.attachment, data.nama_file)">{{ data.nama_file }} </p>
                                            <div>
                                                <span class="remove-file" v-on:click="removeFile( data.id_attachment )"><b>x</b></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="btn btn-sm btn-primary p-2 px-3 rounded" for="file" style="cursor: pointer">
                                        <div class="d-flex justify-content-start align-items-center">
                                            <input type="file" id="file" ref="file" v-on:change="submitFile()" class="custom-file-input" style="display: none;">
                                            <b> + </b>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <b-button type="submit" class="btn btn-info btn-sm px-5 mt-3">
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
                    <b-toast id="toast-updated" title="Task successfuly to updated." variant="success" toaster="b-toaster-bottom-right">
                        Row will be updated.
                    </b-toast>
                </div>

                <b-toast id="example-toast" title="Information" variant="secondary" toaster="b-toaster-bottom-full">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-0"> Are you sure to delete this one.</p>
                        </div>
                        <div>
                            <b-button @click="softDelete" class="btn-sm" variant="info" >Yes</b-button>
                        </div>
                    </div>
                </b-toast>
            </div>
        </b-tab>

        <b-tab @click="getStudentWork">
            <template v-slot:title>
                <div v-if="isLoadingAction">
                    <b-spinner variant="light" type="grow" label="Spinning" small></b-spinner>
                    <b-spinner variant="light" type="grow" label="Spinning" small></b-spinner>
                    <b-spinner variant="light" type="grow" label="Spinning" small></b-spinner>
                </div>
                <div v-else>
                    Student work
                </div>
            </template>
            <div class="px-2">
                
                <div v-if="listWork.records == ''">
                    <p class="text-center mt-3" style="color: grey">Student work is empty.</p>
                </div>
                <div v-else>
                    <div v-for="(work,index) in listWork.records" :key="work.id_work">
                        <div class="d-flex justify-content-start align-items-center my-1">
                            <div class="container bg-white rounded mb-0 py-3 shadow">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="mb-0">
                                            {{index + 1}}. {{work.nama}}
                                        </p>
                                        <small class="mb-0">
                                            {{work.date_time}}
                                        </small>
                                    </div>
                                    <div>
                                        <p class="mb-0" :style="[work.status !== 'Late' ? {'color': 'green'} : {'color': 'red'}]">
                                            {{work.status}}
                                        </p>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-start align-items-center flex-wrap">
                                    <div v-for="data in work.attachments" :key="data.id_attachment" @click="downloadBase64File(data.type, data.attachment, data.nama_file)" class="mr-2 rounded px-3 py-1 mt-1" style="max-width: 200px; border: solid 1px blue;">
                                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                                            <p class="mb-0 mr-2">{{ data.nama_file }} </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </b-tab>
    </b-tabs>
    <!-- toast -->
    <b-toast id="toast-softdelete" title="Task successfuly to softdelete." variant="success" toaster="b-toaster-bottom-full">
        Row will be deleted in list.
    </b-toast>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import { required } from 'vuelidate/lib/validators'
export default {
    data(){
        return {
            showDismissibleAlert: false,
            file: '',
            status: ['On progress', 'Closed'],
            showMobileMenu: false
        }
    },
    computed: {
        ...mapState({
            listWork: state => state.work.listWork,
            info(state){
                const info = Object.assign({}, state.tugas.info)
                return info
            },
            isLoading: state => state.isLoading,
            isLoadingAction: state => state.isLoadingAction
        }),
    },
    watch: {
        info(){
            this.changeFormatDueDate()
        },
    },
    
    validations: {
        info: {
            title: {required},
            due_date: {required},
            status: {required}
        },
    },
    methods: {
        changeFormatDueDate(){
            this.info.due_date = this.info.due_date.split(" ").join("T")
        },
        downloadBase64File(contentType, base64Data, fileName) {
            const linkSource = `data:${contentType};base64,${base64Data}`;
            const downloadLink = document.createElement("a");
            downloadLink.href = linkSource;
            downloadLink.download = fileName;
            downloadLink.click();
        },
        async getStudentWork(){
            try {
                await this.$store.dispatch('work/GET_INFO_BY_ID_TUGAS', this.info.id_tugas)
            } catch (error) {
                alert(error)
            }
        },
        async softDelete(){
            try{
                await this.$store.dispatch('tugas/SOFTDELETE_ONE', this.info.id_tugas)
                await this.$bvToast.show('toast-softdelete')
                await this.$router.push({ name: 'info-mapel', params: { id:  this.info.id_jadwal_mapel} })
            }catch(err) {
                alert(err);
            }
        },
        async removeFile(id){
            try{
                await this.$store.dispatch('tugas/SOFTDELETE_FILE', {'id_attachment':id, 'id_tugas': this.info.id_tugas})
            }catch(err) {
                alert(err);
            }
        },
        async submitFile(){

            try {

                this.file = this.$refs.file.files[0];
                let formData = new FormData();

                formData.append('files', this.file);
                formData.append('id_tugas', this.info.id_tugas);

                await this.$store.dispatch('tugas/CREATE_FILE_TUGAS', {'data': formData, 'id_tugas': this.info.id_tugas})
                
            } catch (error) {
                alert(error)
            }

        },
        async update(){

            this.$v.info.$touch();

            if (this.$v.info.$anyError) {
                return;
            }

            try{
                await this.$store.dispatch('tugas/UPDATE', this.info)
                this.$bvToast.show('toast-updated')
            }catch(err){
                alert(err);
            }
        }
    },
    async created(){
        try {
            let id = this.$route.params.id_tugas;
            await this.$store.dispatch('tugas/GET_FETCH_INFO_BY_ID', id)
        } catch (error) {
            alert(error)   
        }
    }
}
</script>

<style>

</style>