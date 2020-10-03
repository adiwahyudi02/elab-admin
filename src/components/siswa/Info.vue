<template>
  <div>
    <b-modal v-if="data != {}" id="info" ok-only size="xl">
        <b-tabs
        active-nav-item-class="font-weight-bold text-primary"
        content-class="mt-3">
            <b-tab title="About" active>
                    <div>
                        <p>NIS: {{ data.nis }}</p>
                        <p class="text-primary"></p>
                    </div>
                    <div class="d-flex justify-content-start">
                        <p>id Kelas : {{ data.id_kelas }}</p>
                        <p class="text-primary"></p>
                    </div>
                    <div class="d-flex justify-content-start">
                        <p>Name : {{ data.nama }}</p>
                        <p class="text-primary"></p>
                    </div>
                     <div class="d-flex justify-content-start">
                        <p>Jenis Kelamin : {{ data.jenis_kelamin }}</p>
                        <p class="text-primary"></p>
                    </div>
                     <div class="d-flex justify-content-start">
                        <p>TTL : {{ data.ttl }}</p>
                        <p class="text-primary"></p>
                    </div>
                     <div class="d-flex justify-content-start">
                        <p>Alamat : {{ data.alamat }}</p>
                        <p class="text-primary"></p>
                    </div>
                     <div class="d-flex justify-content-start">
                        <p>No.Telepon : {{ data.no_telepon }}</p>
                        <p class="text-primary"></p>
                    </div>
                     <div class="d-flex justify-content-start">
                        <p>Email : {{ data.email }}</p>
                        <p class="text-primary"></p>
                    </div>
                     <div class="d-flex justify-content-start">
                        <p>Username : {{ data.username }}</p>
                        <p class="text-primary"></p>
                    </div>
                     <div class="d-flex justify-content-start">
                        <p>Password : {{ data.password }}</p>
                        <p class="text-primary"></p>
                    </div>
                     <div class="d-flex justify-content-start">
                        <p>Id Komputer : {{ data.id_komputer }}</p>
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
            data: state => state.siswa.info,
        })
    }
}
</script>

<style>

</style>