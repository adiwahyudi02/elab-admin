<template>
  <div>
    <b-modal id="multiInfoModal" ok-only size="xl">
          <div v-for="d in data" :key="d.id_mapel">
            <div style="width: 70%">
                <div style="width: 100%">
                <b-tabs
                    active-nav-item-class="font-weight-bold text-primary"
                    content-class="mt-3">
                    <b-tab title="About" active>
                    <small class="text-light">INFORMATION</small>
                    <div class="d-flex justify-content-start">
                        <p>Name Mapel: {{ d.nama_mapel }}</p>
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
            data: state => state.mapel.multi_info
        })
    }
}
</script>

<style>

</style>