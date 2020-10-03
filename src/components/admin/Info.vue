<template>
  <div>
    <b-modal v-if="data != {}" id="info" ok-only size="xl">
        <div>
            <div style="width: 70%">
                <div style="width: 100%">
                <b-tabs
                    active-nav-item-class="font-weight-bold text-primary"
                    content-class="mt-3">
                    <b-tab title="About" active>
                    <small class="text-light">INFO</small>
                    <div class="d-flex justify-content-start">
                        <p>Name: </p>
                        <p class="text-primary">{{ data.nama }}</p>
                    </div>
                    <div class="d-flex justify-content-start">
                        <p>Username : {{ data.username }}</p>
                        <p class="text-primary"></p>
                    </div>
                    <div class="d-flex justify-content-start">
                        <p>Email : {{ data.email }}</p>
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
                </div>
            </div>
        </div>
    </b-modal>
  </div>
</template>

<script>

import { mapState  } from 'vuex'

export default {
    computed: {
        ...mapState({
            data: state => state.admin.info,
        })
    }
}
</script>

<style>

</style>