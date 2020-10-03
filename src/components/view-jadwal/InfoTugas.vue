<template>
  <div>
    <b-modal id="infoTugas" hide-footer ok-only size="md" v-if="info != ''">
        <b-tabs fill
            active-nav-item-class="font-weight-bold text-primary"
            >
            <b-tab active>
                <template v-slot:title>
                    <p class="mb-0">Task</p>
                </template>
                <div>
                    <div>
                        <b-form class="px-2">
                            <div>
                                <div class="form-group">
                                    <label for="title" style="font-size: 9pt;color: blue;margin-left: 5px;">Title:</label>
                                    <div>
                                        <small>{{info.title}}</small>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="cm" style="font-size: 9pt;color: blue;margin-left: 5px;">Description:</label>
                                <div>
                                    <small>{{info.description}}</small>
                                </div>
                            </div>
                            <div>
                                <div class="form-group">
                                    <label for="due_date" style="font-size: 9pt;color: blue;margin-left: 5px;">Due date:</label>
                                    <div>
                                        <small>{{info.due_date}}</small>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="form-group">
                                    <label for="due_date" style="font-size: 9pt;color: blue;margin-left: 5px;">Status:</label>
                                    <div>
                                        <small>{{info.due_date}}</small>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="form-group">
                                    <label for="due_date" style="font-size: 9pt;color: blue;margin-left: 5px;">Created at:</label>
                                    <div>
                                        <small>{{info.created_at}}</small>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="form-group">
                                    <label for="files" style="font-size: 9pt;color: blue;margin-left: 5px;">Attachment: </label>
                                    <div class="d-flex justify-content-start align-items-center flex-wrap">
                                        <div v-for="attach in info.attachments" :key="attach.id_attachment" @click="downloadBase64File(attach.type, attach.attachment, attach.nama_file)" class="mr-2 rounded px-3 py-1 mt-1" style="max-width: 200px; border: solid 1px blue;cursor: pointer">
                                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                                <p class="mb-0 mr-2">{{ attach.nama_file }} </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </b-form>
                    </div>
                
                    <div class="d-flex justify-content-end">
                        <b-button variant="danger" class="btn-sm" @click="$bvToast.show('example-toast')">Delete this task</b-button>
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
                        <!-- <div v-if="listWork.records != ''" class="d-flex justify-content-end my-2 mr-2">
                            <p>Total: {{listWork.records.length}}</p>
                        </div> -->
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
    </b-modal>
  </div>
</template>

<script>
import { mapState } from 'vuex'
export default {
    data(){
        return {
            showDismissibleAlert: false,
            file: ''
        }
    },
    computed: {
        ...mapState({
            listWork: state => state.work.listWork,
            info: state => state.tugas.info,
            isLoading: state => state.isLoading,
            isLoadingAction: state => state.isLoadingAction
        }),
    },
    methods: {
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
                await this.$store.dispatch('tugas/GET_INFO_BY_ID_JADWAL_MAPEL', this.info.id_jadwal_mapel)
                this.$bvModal.hide( "infoTugas")
            }catch(err) {
                alert(err);
            }
        }
    }
}
</script>

<style>

</style>