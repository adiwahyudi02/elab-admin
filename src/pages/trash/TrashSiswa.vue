<template>
    <Main>
        <b-container fluid class="content-wrapper w-content">
            <div class="page-header my-4 ml-2">
                <h5 class="page-title" style="color: darkblue; font-weight: bold;">
                    <span class="mr-2">
                    <!-- <i class="align-middle" data-feather="align-left" style="font-size: 25pt"></i> -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-align-center align-middle mr-2"><line x1="18" y1="10" x2="6" y2="10"></line><line x1="21" y1="6" x2="3" y2="6"></line><line x1="21" y1="14" x2="3" y2="14"></line><line x1="18" y1="18" x2="6" y2="18"></line></svg>
                    </span> Students trash Table 
                </h5>
            </div>
            <div v-if="isLoading" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 999">
                <b-spinner style="width: 3rem; height: 3rem;" variant="info" label="Large Spinner"></b-spinner>
            </div>
            <div v-else>
                <div class="d-flex justify-content-center mt-3">
                    <div style="width: 100%">
                        <div class="card p-4">
                            <div class="align-items-center">
                                <div class="card-body d-flex justify-content-start flex-wrap">
                                    <b-button @click="selectAllRows" variant="info" class="m-1 btn" style="border: none">Select all</b-button>
                                    <b-button @click="clearSelected" variant="info" class="m-1 btn" style="border: none">Clear selected</b-button>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center flex-wrap">
                                    <div>
                                    <b-form-group
                                        class="mt-2"
                                    >
                                        <b-input-group class="input-group"> 
                                        <b-form-input
                                            v-model="filter"
                                            type="search"
                                            id="filterInput"
                                            placeholder="Type to Search"
                                            class="form-control form-control-sm"
                                            
                                        ></b-form-input>
                                        <b-input-group-append class="input-group-append">
                                            <b-button :disabled="!filter" @click="filter = ''" class="btn btn-sm btn-danger">Clear</b-button>
                                        </b-input-group-append>
                                        </b-input-group>
                                    </b-form-group>
                                    </div>

                                    <div class="d-flex justify-content-end flex-wrap">
                                    <b-form-group
                                        label="Sort"
                                        label-cols-sm="3"
                                        label-align-sm="right"
                                        label-for="sortBySelect"
                                        label-size="sm"
                                        class="mb-0 mx-1"
                                    >
                                        <b-input-group class="form-group">
                                        <b-form-select v-model="sortBy" id="sortBySelect" :options="sortOptions" class="w-75 custom-select custom-select-sm">
                                            <template v-slot:first>
                                            <option value="">-- none --</option>
                                            </template>
                                        </b-form-select>
                                        <b-form-select v-model="sortDesc" :disabled="!sortBy" class="w-25 custom-select custom-select-sm">
                                            <option :value="false">Asc</option>
                                            <option :value="true">Desc</option>
                                        </b-form-select>
                                        </b-input-group>
                                    </b-form-group>

                                    <b-form-group
                                        label="Per page"
                                        label-cols-sm="7"
                                        label-cols-md="7"
                                        label-cols-lg="7"
                                        label-align-sm="right"
                                        label-size="sm"
                                        label-for="perPageSelect"
                                        class="mb-0 mx-1"
                                    >
                                        <b-form-select
                                        v-model="perPage"
                                        id="perPageSelect"
                                        :options="pageOptions"
                                        class="custom-select custom-select-sm"
                                        ></b-form-select>
                                    </b-form-group>
                                    </div>
                                    
                                </div>

                                <b-table class="table table-hover table-striped mt-3"
                                show-empty
                                selectable
                                ref="selectableTable"
                                :select-mode="selectMode"
                                stacked="md"
                                :items="items"
                                :fields="fields"
                                :current-page="currentPage"
                                :per-page="perPage"
                                :filter="filter"
                                :filterIncludedFields="filterOn"
                                :sort-by.sync="sortBy"
                                :sort-desc.sync="sortDesc"
                                :sort-direction="sortDirection"
                                @filtered="onFiltered"
                                @row-selected="onRowSelected"
                                >

                                    <template v-slot:cell(selected)="{ rowSelected }">
                                        <template v-if="rowSelected" variant="success">
                                        <span aria-hidden="true">&check;</span>
                                        <span class="sr-only">Selected</span>
                                        </template>
                                        <template v-else>
                                        <span aria-hidden="true">&nbsp;</span>
                                        <span class="sr-only">Not selected</span>
                                        </template>
                                    </template>
                                    
                                    <template v-slot:cell(index)="data">
                                        {{ data.index + 1 }}
                                    </template>
                                    <template v-slot:cell(name)="row">
                                        {{ row.value.first }} {{ row.value.last }}
                                    </template>

                                    <template v-slot:cell(actions)="row">
                                        <b-button size="sm" @click="redirectInfo(row.item.nis)" class="mx-1 btn-table btn btn-sm btn-info" style="border: none;">Info</b-button>
                                        <b-button size="sm" @click="restore(row.item.nis)" class="mx-1 btn-table btn btn-sm btn-warning" style="border: none;">Restore</b-button>
                                        <b-button size="sm" @click="forceDelete(row.item.nis)" class="mx-1 btn-table btn btn-sm btn-danger" style="border: none;">Force Delete</b-button>
                                        
                                    </template>

                                    <template v-slot:row-details="row">
                                        <b-card>
                                        <ul>
                                            <li v-for="(value, key) in row.item" :key="key">{{ key }}: {{ value }}</li>
                                        </ul>
                                        </b-card>
                                    </template>
                                </b-table>

                                <div v-if="isLoadingAction" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 999">
                                    <b-spinner variant="primary" type="grow" label="Spinning" small></b-spinner>
                                    <b-spinner variant="primary" type="grow" label="Spinning" small></b-spinner>
                                    <b-spinner variant="primary" type="grow" label="Spinning" small></b-spinner>
                                </div>

                                <!-- pagination -->
                                <div class="d-flex justify-content-end">                
                                    <b-pagination
                                        v-model="currentPage"
                                        :total-rows="totalRows"
                                        :per-page="perPage"
                                        align="fill"
                                        size="ml"
                                        class="my-0"
                                    ></b-pagination>
                                </div>

                                <!-- components -->
                                <Info />
                                <MultiInfo />

                                <!-- toast -->
                                <b-toast id="toast-restore" title="Row successfuly to restore." variant="success" toaster="b-toaster-bottom-right">
                                    Row will be restore to Class table.
                                </b-toast>
                                <b-toast id="toast-force-delete" title="Row successfuly to force delete." variant="success" toaster="b-toaster-bottom-right">
                                    Row will be permanently removed.
                                </b-toast>
                                <b-toast id="toast-selected" :title="'Selected ' + selected.length + ' row.'" variant="secondary" toaster="b-toaster-bottom-right" no-auto-hide>
                                    <div class="d-flex justify-content-center">
                                        <button @click="redirectMultiInfo" type="button" class="btn btn-sm btn-info mx-1" style="width: 30%">
                                        Info
                                        </button>
                                        <button @click="multiRestore" type="button" class="btn btn-sm btn-warning mx-1" style="width: 30%">
                                        Restore
                                        </button>
                                        <button @click="multiForceDelete" type="button" class="btn btn-sm btn-danger mx-1" style="width: 30%">
                                        Force Delete
                                        </button>
                                    </div>
                                </b-toast>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </b-container>
    </Main>
</template>

<script>

import Info from '../../components/siswa/Info'
import MultiInfo from '../../components/siswa/MultiInfo'
import { mapState } from 'vuex'
import Main from '../../layouts/Main'

export default {
    data() {
      return {
        fields: [
            'index',
          { key: 'nis', label: 'NIS', sortable: true},
        { key: 'nama', label: 'Name', sortable: true},
        { key: 'jenis_kelamin', label: 'Gender', sortable: true},
        { key: 'username', label: 'Username', sortable: true},
        { key: 'nama_kelas', label: 'Class', sortable: true},
        { key: 'nama_komputer', label: 'Computer name', sortable: true},
          { key: 'deleted_at', label: 'Deleted at', sortable: true },
          { key: 'actions', label: 'Actions' }
        ],
        // totalRows: '',
        currentPage: 1,
        perPage: 5,
        pageOptions: [5, 10, 15],
        sortBy: '',
        sortDesc: false,
        sortDirection: 'asc',
        filter: null,
        filterOn: [],
        selectMode: 'multi',
        selected: '',
        keyCreate: 0
      }
    },
    components:{
        Info, MultiInfo, Main
    },
    computed: {
      sortOptions() {
        // Create an options list from our fields
        return this.fields
          .filter(f => f.sortable)
          .map(f => {
            return { text: f.label, value: f.key }
          })
      },
      totalRows() {
        return this.items.length
      },
      ...mapState({
        items: state => state.siswa.items,
        isLoading: state => state.isLoading,
        isLoadingAction: state => state.isLoadingAction
      }),
    },
    methods:{

        onFiltered() {
            this.currentPage = 1
        },
        countDownChanged(dismissCountDown) {
            this.dismissCountDown = dismissCountDown
        },
        onRowSelected(items) {
            this.selected = items
            if (this.selected.length != 0) {
                this.$bvToast.show('toast-selected')
            }else{
                this.$bvToast.hide('toast-selected')
            }
        },
        selectAllRows() {
            this.$refs.selectableTable.selectAllRows()
        },
        clearSelected() {
            this.$refs.selectableTable.clearSelected()
        },

        async redirectInfo(id){
            try{
                await this.$store.dispatch('siswa/GET_INFO_BY_ID', id)
                this.$bvModal.show( "info")
            }catch(err) {
                alert(err);
            }
        },

        async restore(id){
            try{
                await this.$store.dispatch('siswa/RESTORE_ONE', id)
                await this.$bvToast.show('toast-restore')
            }catch(err) {
                alert(err);
            }
        },

        async forceDelete(id){
            try{
                await this.$store.dispatch('siswa/FORCEDELETE_ONE', id)
                await this.$bvToast.show('toast-force-delete')
            }catch(err) {
                alert(err);
            }
        },

        async redirectMultiInfo(){
            try{
                await this.$store.commit('siswa/SET_MULTI_INFO', this.selected)
                this.$bvModal.show( "multiInfoModal")
                this.$bvToast.hide('toast-selected')
            }catch(err) {
                alert(err);
            }
        },

        async multiRestore(){
            try{
                await this.$store.dispatch('siswa/RESTORE_MULTI', this.selected)
                this.$bvToast.hide('toast-restore')
            }catch(err) {
                alert(err);
            }
        },

        async multiForceDelete(){
            try{
                await this.$store.dispatch('siswa/FORCEDELETE_MULTI', this.selected)
                this.$bvToast.hide('toast-force-delete')
            }catch(err) {
                alert(err);
            }
        },
    },
    async created(){
        await this.$store.dispatch('siswa/GET_ALL_TRASH')
    }
}
</script>