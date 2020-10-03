<template>
  <div>
    <b-modal id="multiInfoModal" ok-only size="xl">
          <div v-for="d in data" :key="d.id_billing">
            <div style="width: 70%">
                <div style="width: 100%">
                <b-tabs
                    active-nav-item-class="font-weight-bold text-primary"
                    content-class="mt-3">
                    <b-tab title="About" active>
                    <small class="text-light">INFORMATION</small>
                    <div class="d-flex justify-content-start">
                        <p>NIS : {{ d.nis }}</p>
                        <p class="text-primary"></p>
                    </div>
                    <div class="d-flex justify-content-start">
                        <p>Student : {{ d.nama }}</p>
                        <p class="text-primary"></p>
                    </div>
                    <div class="d-flex justify-content-start">
                        <p>Class : {{ d.nama_kelas }}</p>
                        <p class="text-primary"></p>
                    </div>
                    <div class="d-flex justify-content-start">
                        <p>Computer name : {{ d.nama_komputer }}</p>
                        <p class="text-primary"></p>
                    </div>
                    <div class="d-flex justify-content-start">
                        <p>Ip address : {{ d.ip_address }}</p>
                        <p class="text-primary"></p>
                    </div>
                    <div class="d-flex justify-content-start">
                        <p>Lab : {{ d.nama_lab }}</p>
                        <p class="text-primary"></p>
                    </div>
                    <div class="d-flex justify-content-start">
                        <p>Date Time : {{ d.date_time }}</p>
                        <p class="text-primary"></p>
                    </div>
                    <div class="d-flex justify-content-start">
                        <p>Date Time out: {{ d.jam_keluar }}</p>
                        <p class="text-primary"></p>
                    </div>
                    <div class="d-flex justify-content-start">
                        <p>Created at : {{ d.created_at }}</p>
                        <p class="text-primary"></p>
                    </div>
                    <div class="d-flex justify-content-start">
                        <p>Updated at : <span v-if="!d.udated_at">-</span> {{ d.udated_at }}</p>
                        <p class="text-primary"></p>
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
            data: state => state.billing.multi_info
        })
    }
}
</script>

<style>

</style>