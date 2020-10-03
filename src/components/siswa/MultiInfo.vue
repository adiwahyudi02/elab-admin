<template>
  <div>
    <b-modal id="multiInfoModal" ok-only size="xl">
          <div v-for="d in data" :key="d.nis">
            <div style="width: 70%">
                <div style="width: 100%">
                <b-tabs
                    active-nav-item-class="font-weight-bold text-primary"
                    content-class="mt-3">
                    <b-tab title="About" active>
                    <small class="text-light">INFO</small>
                    <div class="d-flex justify-content-start">
                        <p>NIS: {{ d.nis }}</p>
                        <p class="text-primary"></p>
                    </div>
                    <div class="d-flex justify-content-start">
                        <p>id Kelas : {{ d.id_kelas }}</p>
                        <p class="text-primary"></p>
                    </div>
                    <div class="d-flex justify-content-start">
                        <p>Name : {{ d.nama }}</p>
                        <p class="text-primary"></p>
                    </div>
                     <div class="d-flex justify-content-start">
                        <p>Jenis Kelamin : {{ d.jenis_kelamin }}</p>
                        <p class="text-primary"></p>
                    </div>
                     <div class="d-flex justify-content-start">
                        <p>TTL : {{ d.ttl }}</p>
                        <p class="text-primary"></p>
                    </div>
                     <div class="d-flex justify-content-start">
                        <p>Alamat : {{ d.alamat }}</p>
                        <p class="text-primary"></p>
                    </div>
                     <div class="d-flex justify-content-start">
                        <p>No.Telepon : {{ d.no_telepon }}</p>
                        <p class="text-primary"></p>
                    </div>
                     <div class="d-flex justify-content-start">
                        <p>Email : {{ d.email }}</p>
                        <p class="text-primary"></p>
                    </div>
                     <div class="d-flex justify-content-start">
                        <p>Username : {{ d.username }}</p>
                        <p class="text-primary"></p>
                    </div>
                     <div class="d-flex justify-content-start">
                        <p>Password : {{ d.password }}</p>
                        <p class="text-primary"></p>
                    </div>
                     <div class="d-flex justify-content-start">
                        <p>Id Komputer : {{ d.id_komputer }}</p>
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
            data: state => state.siswa.multi_info
        })
    }
}
</script>

<style>

</style>