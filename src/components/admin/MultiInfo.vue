<template>
  <div v-if="data != {}">
    <b-modal id="multiInfoModal" ok-only size="xl">
      <div v-for="d in data" :key="d.id">
            <div style="width: 70%">
                <div style="width: 100%">
                <b-tabs
                    active-nav-item-class="font-weight-bold text-primary"
                    content-class="mt-3">
                    <b-tab title="About" active>
                    <small class="text-light">INFORMATION</small>
                    <div class="d-flex justify-content-start">
                        <p>Name: </p>
                        <p class="text-primary">{{ d.nama }}</p>
                    </div>
                    <div class="d-flex justify-content-start">
                        <p>Username : {{ d.username }}</p>
                        <p class="text-primary"></p>
                    </div>
                    <div class="d-flex justify-content-start">
                        <p>Email : {{ d.email }}</p>
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
            data: state => state.admin.multi_info
        })
    }
}
</script>

<style>

</style>

kode : eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9leGFtcGxlLm9yZyIsImF1ZCI6Imh0dHA6XC9cL2V4YW1wbGUuY29tIiwiaWF0IjoxMzU2OTk5NTI0LCJuYmYiOjEzNTcwMDAwMDAsImRhdGEiOnsiaWQiOiI5IiwiZmlyc3RuYW1lIjoiTWlrZSIsImxhc3RuYW1lIjoiRGFsaXNheSIsImVtYWlsIjoibWlrZUBjb2Rlb2ZhbmluamEuY29tIn19.h_Q4gJ3epcpwdwNCNCYxtiKdXsN34W9MEjxZ7sx21Vs