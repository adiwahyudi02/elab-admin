<template>
  <div>
    <b-modal v-if="data != {}" id="info" ok-only size="xl">
                <b-tabs
                    active-nav-item-class="font-weight-bold text-primary"
                    content-class="mt-3">
                    <b-tab title="About" active>
                    <small class="text-light">INFO</small>
                    <div class="d-flex justify-content-start">
                        <p>Name Class: {{ data.nama_kelas }}</p>
                        <p class="text-primary"></p>
                        <!-- {{ data.nama_kelas }} -->                   
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
            data: state => state.kelas.info,
        })
    }
}
</script>

<style>

</style>