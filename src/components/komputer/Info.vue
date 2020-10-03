<template>
  <div>
    <b-modal v-if="data != {}" id="info" ok-only size="xl">
                 <b-tabs
                    active-nav-item-class="font-weight-bold text-primary"
                    content-class="mt-3">
                    <b-tab title="About" active>
                    <small class="text-light">INFO</small>
                    <div class="d-flex justify-content-start">
                        <p>Name Komputer: {{ data.nama_komputer }}</p>
                        <p class="text-primary"></p>                 
                    </div>
                     <div class="d-flex justify-content-start">
                        <p>spesifikasi: {{ data.spesifikasi }}</p>
                        <p class="text-primary"></p>                 
                    </div>
                     <div class="d-flex justify-content-start">
                        <p>id lab: {{ data.id_lab }}</p>
                        <p class="text-primary"></p>                 
                    </div>
                     <div class="d-flex justify-content-start">
                        <p>IP Addres: {{ data.ip_address }}</p>
                        <p class="text-primary"></p>                 
                    </div>
                    <div class="d-flex justify-content-start">
                        <p>Created at : {{ data.created_at }}</p>
                        <p class="text-primary"></p>
                    </div>
                    <div class="d-flex justify-content-start">
                        <p>Updated at : <span v-if="!data.udated_at">-</span> {{ data.udated_at }}</p>
                        <p class="text-primary"></p>
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
            data: state => state.komputer.info,
        })
    }
}
</script>

<style>

</style>