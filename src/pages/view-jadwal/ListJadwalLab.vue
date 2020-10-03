<template>
  <Main>
      <div class="m-4">
        <div class="d-flex justify-content-start align-items-center flex-wrap">
            <div v-for="item in items" :key="item.id_lab">
                <router-link :to="{ name: 'view-schedule', params: { id: item.id_lab }}">
                    <b-btn class="py-4 px-5 shadow-sm rounded m-2">
                        {{item.nama_lab}}
                    </b-btn>
                </router-link>
            </div>
        </div>
      </div>
  </Main>
</template>

<script>
import { mapState } from 'vuex'
import Main from '../../layouts/Main'
export default {
    components: {
        Main
    },
    computed: {
        ...mapState({
            items: state => state.lab.items,
            isLoading: state => state.isLoading,
            isLoadingAction: state => state.isLoadingAction
        })
    },
    async created(){
        try {
            await this.$store.dispatch('lab/GET_ALL')
        } catch (error) {
            alert(error);
        }
    }
}
</script>

<style>

</style>