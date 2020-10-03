<template>
  <div>
    <b-modal id="multiInfoModal" ok-only size="xl">
    <div v-for="d in data" :key="d.id_kelas">
            <div style="width: 70%" class="mb-5">
                <div style="width: 100%">
                <b-tabs
                    active-nav-item-class="font-weight-bold text-primary"
                    content-class="mt-3">
                    <b-tab title="About" active>
                    <small class="text-light">INFORMATION</small>
                    <div>
                        <div class="form-group">
                            <label for="title" style="font-size: 9pt;color: blue;margin-left: 5px;">Title:</label>
                            <div>
                                <small>{{d.title}}</small>
                            </div>
                            
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cm" style="font-size: 9pt;color: blue;margin-left: 5px;">Description:</label>
                        <div>
                            <small>{{d.description}}</small>
                        </div>
                    </div>
                    <div>
                        <div class="form-group">
                            <label for="due_date" style="font-size: 9pt;color: blue;margin-left: 5px;">Due date:</label>
                            <div>
                                <small>{{d.due_date}}</small>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="form-group">
                            <label for="due_date" style="font-size: 9pt;color: blue;margin-left: 5px;">Status:</label>
                            <div>
                                <small>{{d.due_date}}</small>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="form-group">
                            <label for="due_date" style="font-size: 9pt;color: blue;margin-left: 5px;">Created at:</label>
                            <div>
                                <small>{{d.created_at}}</small>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="form-group">
                            <label for="files" style="font-size: 9pt;color: blue;margin-left: 5px;">Attachment: </label>
                            <div class="d-flex justify-content-start align-items-center flex-wrap">
                                <div v-for="attach in d.attachments" :key="attach.id_attachment" @click="downloadBase64File(attach.type, attach.attachment, attach.nama_file)" class="mr-2 rounded px-3 py-1 mt-1" style="max-width: 200px; border: solid 1px blue;cursor: pointer">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                                        <p class="mb-0 mr-2">{{ attach.nama_file }} </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </b-tab>
                </b-tabs>
                </div>
            </div>
        </div>
      </b-modal>
  </div>
</template>

<script>

import { mapState } from 'vuex'


export default {
    computed:{
        ...mapState({
            data: state => state.tugas.multi_info
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
    }
}
</script>

<style>

</style>