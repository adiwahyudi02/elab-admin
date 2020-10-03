<template>
  <div>
    <b-modal v-if="data != {}" id="info" ok-only size="xl">
        <b-tabs
            active-nav-item-class="font-weight-bold text-primary"
            content-class="mt-3">
            <b-tab title="About" active>
            <small class="text-light">INFO</small>
            <div>
                <div class="form-group">
                    <label for="title" style="font-size: 9pt;color: blue;margin-left: 5px;">Title:</label>
                    <div>
                        <small>{{data.title}}</small>
                    </div>
                    
                </div>
            </div>
            <div class="form-group">
                <label for="cm" style="font-size: 9pt;color: blue;margin-left: 5px;">Description:</label>
                <div>
                    <small>{{data.description}}</small>
                </div>
            </div>
            <div>
                <div class="form-group">
                    <label for="due_date" style="font-size: 9pt;color: blue;margin-left: 5px;">Due date:</label>
                    <div>
                        <small>{{data.due_date}}</small>
                    </div>
                </div>
            </div>
            <div>
                <div class="form-group">
                    <label for="due_date" style="font-size: 9pt;color: blue;margin-left: 5px;">Status:</label>
                    <div>
                        <small>{{data.due_date}}</small>
                    </div>
                </div>
            </div>
            <div>
                <div class="form-group">
                    <label for="due_date" style="font-size: 9pt;color: blue;margin-left: 5px;">Created at:</label>
                    <div>
                        <small>{{data.created_at}}</small>
                    </div>
                </div>
            </div>
            <div>
                <div class="form-group">
                    <label for="files" style="font-size: 9pt;color: blue;margin-left: 5px;">Attachment: </label>
                    <div class="d-flex justify-content-start align-items-center flex-wrap">
                        <div v-for="attach in data.attachments" :key="attach.id_attachment" @click="downloadBase64File(attach.type, attach.attachment, attach.nama_file)" class="mr-2 rounded px-3 py-1 mt-1" style="max-width: 200px; border: solid 1px blue;cursor: pointer">
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <p class="mb-0 mr-2">{{ attach.nama_file }} </p>
                            </div>
                        </div>
                    </div>
                </div>
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
                            <div class="d-flex justify-content-start align-items-center my-2">
                                <div class="container bg-light rounded mb-0 py-3 shadow">
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
                                        <div v-for="data in work.attachments" :key="data.id_attachment" @click="downloadBase64File(data.type, data.attachment, data.nama_file)" class="mr-2 rounded px-3 py-1 mt-1" style="max-width: 200px; border: solid 1px blue;cursor: pointer">
                                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                                <p class="mb-0 mr-2">{{ data.nama_file }} </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <InfoWork :data="" /> -->
                </div>
            </b-tab>
        </b-tabs>
    </b-modal>
  </div>
</template>

<script>

import { mapState  } from 'vuex'

export default {
    computed: {
        ...mapState({
            data: state => state.tugas.info,
            listWork: state => state.work.listWork,
            isLoading: state => state.isLoading,
            isLoadingAction: state => state.isLoadingAction
        })
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
                await this.$store.dispatch('work/GET_INFO_BY_ID_TUGAS', this.data.id_tugas)
            } catch (error) {
                alert(error)
            }
        },
    }
}
</script>

<style>

</style>