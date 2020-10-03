<template>
  <Main>
    <b-container fluid class="content-wrapper w-content">
      <div class="page-header my-4 ml-2">
        <h5 class="page-title" style="color: darkblue; font-weight: bold;">
            <span class="mr-2">
            <!-- <i class="align-middle" data-feather="align-left" style="font-size: 25pt"></i> -->
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-align-center align-middle mr-2"><line x1="18" y1="10" x2="6" y2="10"></line><line x1="21" y1="6" x2="3" y2="6"></line><line x1="21" y1="14" x2="3" y2="14"></line><line x1="18" y1="18" x2="6" y2="18"></line></svg>
          </span> Computer Table 
        </h5>
      </div>
      <div v-if="isLoading" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 999">
          <b-spinner style="width: 3rem; height: 3rem;" variant="info" label="Large Spinner"></b-spinner>
      </div>
      <div v-else>
        <div class="card p-4">
          <div class="">
            <div class="card-body d-flex justify-content-start flex-wrap">
              <Create />
              <router-link :to="{ name: 'trash-computers' }" class="m-1"><b-btn v-b-modal.modal1 class="btn" style="border: none">Trash</b-btn></router-link>
              <b-button size="sm" @click="selectAllRows" class="m-1 btn btn-sm" style="border: none">Select all</b-button>
              <b-button size="sm" @click="clearSelected" class="m-1 btn btn-sm" style="border: none">Clear selected</b-button>
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

              <b-table class="table table-hover mt-2"
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
                  <b-button size="sm" @click="redirectInfo(row.item.id_komputer)" class="btn btn-table btn-info btn-sm mx-2" style="border: none;">Info</b-button>
                  <b-button size="sm" @click="softDelete(row.item.id_komputer)" class="btn btn-table btn-danger btn-sm mx-2" style="border: none;">Delete</b-button>
                  <b-button size="sm" @click="redirectEdit(row.item.id_komputer)" class="btn btn-table btn-warning btn-sm mx-2" style="border: none;">Edit</b-button>
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

              <!-- component -->
              <Update />
              <Info />
              <MultiInfo />

              <!-- toast -->
              <b-toast id="toast-softdelete" title="Row successfuly to softdelete." variant="success" toaster="b-toaster-bottom-right">
                Row will be put in trash.
              </b-toast>
              <b-toast id="toast-selected" :title="'Selected ' + selected.length + ' row.'" variant="secondary" toaster="b-toaster-bottom-right" no-auto-hide>
                <div class="d-flex justify-content-center">
                  <button @click="redirectMultiInfo" type="button" class="btn btn-sm btn-info mx-1" style="width: 45%">
                    Info
                  </button>
                  <button @click="multiSoftDelete" type="button" class="btn btn-sm btn-danger mx-1" style="width: 45%">
                    Delete
                  </button>
                </div>
              </b-toast>
            </div>
        </div>
      </div>
    </b-container>
  </Main>
</template>

<script>
import { mapState } from 'vuex'
import Create from '../components/komputer/Create'
import Info from '../components/komputer/Info'
import Update from '../components/komputer/Update'
import MultiInfo from '../components/komputer/MultiInfo'
import Main from '../layouts/Main'

export default {
  data() {
    return {
      fields: [
          'index',
        { key: 'nama_komputer', label: 'Computer name', sortable: true},
        { key: 'ip_address', label: 'Ip Address', sortable: true},
        { key: 'spesifikasi', label: 'Specification'},
        { key: 'nama_lab', label: 'Lab name', sortable: true},
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
  components: {
    Main, Create, Info, Update, MultiInfo
  },
  computed: {
    sortOptions() {
      return this.fields
        .filter(f => f.sortable)
        .map(f => {
          return { text: f.label, value: f.key }
        })
    },
    totalRows() {
      return this.items.length;

    },
    ...mapState({
      items: state => state.komputer.items,
      isLoading: state => state.isLoading,
      isLoadingAction: state => state.isLoadingAction
    })
  },
  methods: {
    onFiltered() {
      this.currentPage = 1
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

    async softDelete(id){
      try{
        await this.$store.dispatch('komputer/SOFTDELETE_ONE', id)
        await this.$bvToast.show('toast-softdelete')
      }catch(err) {
        alert(err);
      }
    },

    async redirectInfo(id){
      try{
        await this.$store.dispatch('komputer/GET_INFO_BY_ID', id)
        this.$bvModal.show( "info")
      }catch(err) {
        alert(err);
      }
    },

    async redirectEdit(id){
      try{
        await this.$store.dispatch('komputer/GET_INFO_BY_ID', id)
        await this.$store.dispatch('lab/REFRESH_GET_ALL')
        this.$bvModal.show( "modalUpdate")
      }catch(err) {
        alert(err);
      }
    },

    async redirectMultiInfo(){
      try{
        await this.$store.commit('komputer/SET_MULTI_INFO', this.selected)
        this.$bvModal.show( "multiInfoModal")
        this.$bvToast.hide('toast-selected')
      }catch(err) {
        alert(err);
      }
    },
    async multiSoftDelete(){
      try{
        await this.$store.dispatch('komputer/SOFTDELETE_MULTI', this.selected)
        await this.$bvToast.show('toast-softdelete')
      }catch(err) {
        alert(err);
      }
    },
  },
  async created(){
    try {
      await this.$store.dispatch('komputer/GET_ALL')
    } catch (error) {
      alert(error);
    }
  }
}
</script>

<style>
  th{
    font-size: 13px;
  }
  td{
    font-size: 12px;
  }
  .btn-table{
    font-size: 11px;
  }
  .error{
    font-size: 12px;
    color: red;
  }
</style>