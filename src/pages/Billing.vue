<template>
  <Main>
    <b-container fluid class="content-wrapper w-content">
      <div class="page-header my-4 ml-2">
        <h5 class="page-title" style="color: darkblue; font-weight: bold;">
            <span class="mr-2">
            <!-- <i class="align-middle" data-feather="align-left" style="font-size: 25pt"></i> -->
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-align-center align-middle mr-2"><line x1="18" y1="10" x2="6" y2="10"></line><line x1="21" y1="6" x2="3" y2="6"></line><line x1="21" y1="14" x2="3" y2="14"></line><line x1="18" y1="18" x2="6" y2="18"></line></svg>
          </span> Billings Table 
        </h5>
      </div>
      <div v-if="isLoading && data != ''" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 999">
          <b-spinner style="width: 3rem; height: 3rem;" variant="info" label="Large Spinner"></b-spinner>
      </div>
      <div v-else>
        <div class="card p-4">
          <div class="">
            <div class="card-body d-flex justify-content-start flex-wrap">
              <!-- <Create /> -->
              <router-link :to="{ name: 'trash-billings' }" class="m-1"><b-btn v-b-modal.modal1 class="btn" style="border: none">Trash</b-btn></router-link>
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
                  >
                    <label style="font-size: 9pt" for="filterInput">Search:</label>
                    <b-input-group class="input-group"> 
                      <b-form-input
                        v-model="search"
                        type="search"
                        id="filterInput"
                        placeholder="What data you need.."
                        class="form-control form-control-sm"
                        
                      ></b-form-input>
                      <b-input-group-append class="input-group-append">
                        <b-button :disabled="!search" @click="search = ''" class="btn btn-sm btn-secondary">x</b-button>
                      </b-input-group-append>
                    </b-input-group>
                  </b-form-group>
                </div>

                <div class="form-group">
                    <label style="font-size: 9pt" for="start_dt">Start date:</label>
                    <div class="d-flex justify-content-start align-items-center">
                      <input type="datetime-local" name="" id="start_dt" v-model="start_dt" class="form-control form-control-sm mb-0">
                      <b-input-group-append class="input-group-append">
                        <b-button :disabled="!start_dt" @click="start_dt = ''" class="btn btn-sm btn-secondary">x</b-button>
                      </b-input-group-append>
                    </div>
                </div>
                <div class="form-group">
                    <label style="font-size: 9pt" for="end_dt">End date:</label>
                    <div class="d-flex justify-content-start align-items-center">
                      <input type="datetime-local" name="" id="end_dt" v-model="end_dt" class="form-control form-control-sm mb-0">
                      <b-input-group-append class="input-group-append">
                        <b-button :disabled="!end_dt" @click="end_dt = ''" class="btn btn-sm btn-secondary">x</b-button>
                      </b-input-group-append>
                    </div>
                </div>

                <b-form-group
                  label-align-sm="right"
                  label-for="sortBySelect"
                  label-size="sm"
                  class="mb-0 mx-1"
                >
                  <label style="font-size: 9pt" for="sortBySelect">Sort:</label>
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
                  label-align-sm="right"
                  label-size="sm"
                  label-for="perPageSelect"
                  class=""
                >
                  <label style="font-size: 9pt" for="perPageSelect">Per page:</label>
                  <b-form-select
                    v-model="perPage"
                    id="perPageSelect"
                    :options="pageOptions"
                    class="custom-select custom-select-sm"
                  ></b-form-select>
                </b-form-group>

                <div>

                  <b-button v-b-modal.modal-report class="btn btn-sm rounded btn-secondary py-2 px-3" v-b-tooltip.hover title="export to pdf">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer align-middle"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                  </b-button>
                  <b-modal id="modal-report" :hide-footer="true">
                    <b-form @submit.stop.prevent="exportPdf">
                      <div class="form-group">
                          <label for="lab" style="font-size: 8pt"><b>File name without extension : </b></label>
                          <input type="text" v-model.trim="$v.pdf.nama_file.$model" class="form-control form-control-sm mb-0" id="lab">
                          <div style="font-size: 8pt" class="error" v-if="!$v.pdf.nama_file.required && $v.pdf.nama_file.$anyDirty">File name is required.</div>
                      </div>
                      <div class="form-group">
                          <label for="title" style="font-size: 8pt"><b>Title : </b></label>
                          <input type="text" v-model.trim="$v.pdf.title.$model" class="form-control form-control-sm mb-0" id="title">
                          <div style="font-size: 8pt" class="error" v-if="!$v.pdf.title.required && $v.pdf.title.$anyDirty">File name is required.</div>
                      </div>
                      <div class="form-group">
                          <label for="note" style="font-size: 8pt"><b>Description : </b></label>
                          <textarea style="white-space: pre-line" v-model.trim="pdf.note" class="form-control form-control-sm mb-0" id="note" cols="30" rows="6"></textarea>
                      </div>
                      <div class="d-flex justify-content-end">
                          <b-button type="submit" class="btn btn-info btn-sm px-3" style="font-size: 8pt">
                              <div v-if="isLoadingAction">
                                  <b-spinner variant="light" type="grow" label="Spinning" small></b-spinner>
                                  <b-spinner variant="light" type="grow" label="Spinning" small></b-spinner>
                                  <b-spinner variant="light" type="grow" label="Spinning" small></b-spinner>
                              </div>
                              <div v-else>
                                  Save
                              </div>
                          </b-button>
                      </div>
                    </b-form>
                  </b-modal>
                </div>
                
              </div>

              <!-- {{items}} -->

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
                  <b-button size="sm" @click="redirectInfo(row.item.id_billing)" class="btn btn-table btn-info btn-sm mx-2" style="border: none;">Info</b-button>
                  <b-button size="sm" @click="softDelete(row.item.id_billing)" class="btn btn-table btn-danger btn-sm mx-2" style="border: none;">Delete</b-button>
                  <!-- <b-button size="sm" @click="redirectEdit(row.item.id_billing)" class="btn btn-table btn-warning btn-sm mx-2" style="border: none;">Edit</b-button> -->
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
              <!-- <Update /> -->
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
      <!-- <img :src="logo" alt="asdf"> -->
    </b-container>
  </Main>
</template>

<script>
import { mapState } from 'vuex'
import { required } from 'vuelidate/lib/validators'
import jsPDF from 'jspdf'
import 'jspdf-autotable'

import Info from '../components/billing/Info'
import MultiInfo from '../components/billing/MultiInfo'
// import All from '../components/billing/report/All'
import Main from '../layouts/Main'
import logo_jurusan from '../assets/logo-rpl_min.png'
import logo_sekolah from '../assets/logo_sekolah.jpg'


export default {
  data() {
    return {
      items: [],
      pre_items: [],
      pre_items_pisan: [],
      fields: [
          'index',
        { key: 'nis', label: 'NIS', sortable: true},
        { key: 'nama', label: 'Student', sortable: true},
        { key: 'nama_kelas', label: 'Class', sortable: true},
        { key: 'nama_komputer', label: 'Computer name', sortable: true},
        { key: 'ip_address', label: 'Ip Address', sortable: true},
        { key: 'nama_lab', label: 'Lab name', sortable: true},
        { key: 'date_time', label: 'Date time in', sortable: true},
        { key: 'jam_keluar', label: 'Out', sortable: true},
        { key: 'actions', label: 'Actions' }
      ],
      // totalRows: '',
      currentPage: 1,
      perPage: 5,
      pageOptions: [5, 10, 15],
      sortBy: '',
      sortDesc: false,
      sortDirection: 'asc',
      filterOn: [],
      selectMode: 'multi',
      selected: '',
      keyCreate: 0,

      search: '',
      start_dt: '',
      end_dt: '',
      pdf: {
        nama_file: '',
        title: '',
        note: '-'
      }
    }
  },
  components: {
    Main, Info, MultiInfo
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
      // items: state => state.billing.items,
      data(state){
        const info = state.billing.items
        return info
      },
      isLoading: state => state.isLoading,
      isLoadingAction: state => state.isLoadingAction
    })
  },
  watch: {
    data(){
        this.items = this.data
        this.pre_items = this.data
        this.pre_items_pisan = this.data
    },
    search(){
      this.filterFunction()
    },
    start_dt(){
      this.filterFunction()
    },
    end_dt(){
      this.filterFunction()
    },
  },

  validations: {
    pdf: {
      nama_file: {required},
      title: {required}
    }
  },

  methods: {
    async exportPdf(){
      this.$v.pdf.$touch();

      if (this.$v.pdf.$anyError) {
          return;
      }
      try {

        var columns = [
          { dataKey: 'nis', title: 'NIS'},
          { dataKey: 'nama', title: 'Student'},
          { dataKey: 'nama_kelas', title: 'Class'},
          { dataKey: 'nama_komputer', title: 'Computer name'},
          { dataKey: 'ip_address', title: 'Ip Address'},
          { dataKey: 'nama_lab', title: 'Lab name'},
          { dataKey: 'date_time', title: 'Date time in'},
          { dataKey: 'jam_keluar', title: 'Out'}
        ];
        var doc = new jsPDF('p', 'pt');

        //kop
        doc.addImage(logo_sekolah, 'JPEG', 60, 30, 55, 55);
        doc.setFontSize(14);
        doc.text('E-lab Application Report', 210, 50);
        doc.setFontSize(16);
        doc.text('SMK NEGERI 2 CIMAHI', 200, 70);        
        doc.setFontSize(9);
        doc.text('Jl. Kamarung Km. 1,5 No. 69 Kel. Citeureup Kec. Cimahi Utara Kota Cimahi', 140, 85);      
        doc.addImage(logo_jurusan, 'JPEG', 470, 30, 55, 55);
        doc.line(40, 100, 550, 100);

        doc.setFontSize(11);
        doc.text(this.pdf.title, 40, 120);
        doc.setFontSize(8);
        doc.text('Description : ' + this.pdf.note, 40, 140, {
          styles: { whiteSpace: 'pre-line' },
        })

        doc.setFontSize(8);
        
          const monthNames = ["January", "February", "March", "April", "May", "June",
              "July", "August", "September", "October", "November", "December"];
          let dateObj = new Date();
          let month = monthNames[dateObj.getMonth()];
          let day = String(dateObj.getDate()).padStart(2, '0');
          let year = dateObj.getFullYear();
          let output = month  + ' '+ day  + ',' + year;

        doc.text('' + output, 465, 120);
        doc.setFontSize(8);
        doc.autoTable(columns, this.items, {
          margin: {top: 160},
          styles: { fontSize: 8.5 },
        });

        
        doc.save( this.pdf.nama_file + '.pdf');

        this.pdf.nama_file = ''
        this.pdf.title = ''
        this.pdf.note = ''
        await this.$v.pdf.$reset()

      } catch (error) {
        alert(error)
      }
    },
    async filterFunction(){

      if (this.search != '') {
        const r_search = this.pre_items.filter(i => {
          return (i.nama_komputer.toLowerCase().indexOf(this.search.toLowerCase()) >= 0 ||
          i.nama.toLowerCase().indexOf(this.search.toLowerCase()) >= 0 ||
          i.ip_address.toLowerCase().indexOf(this.search.toLowerCase()) >= 0 ||
          i.nama_kelas.toLowerCase().indexOf(this.search.toLowerCase()) >= 0 ||
          i.date_time.toLowerCase().indexOf(this.search.toLowerCase()) >= 0 ||
          i.nis.toLowerCase().indexOf(this.search.toLowerCase()) >= 0 ||
          i.nama_lab.toLowerCase().indexOf(this.search.toLowerCase()) >= 0)
        })
        this.items = r_search

        if (this.start_dt != '' || this.end_dt != '') {

          const r_date_time = r_search.filter(i => {
            return this.start_dt.split("T").join(' ') + ':00' <= i.date_time && this.end_dt.split("T").join(' ') + ':00' >= i.date_time
          })
          this.items = r_date_time

        }
      } else {

        if (this.start_dt != '' || this.end_dt != '') {

          this.pre_items = this.pre_items_pisan
          const r_date_time = this.pre_items.filter(i => {
            return this.start_dt.split("T").join(' ') + ':00' <= i.date_time && this.end_dt.split("T").join(' ') + ':00' >= i.date_time
          })
          this.items = r_date_time
        }else{ 
          this.items = this.pre_items_pisan
        }
        
      }

      // console.log('ii', this.items);
      
    },
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
        await this.$store.dispatch('billing/SOFTDELETE_ONE', id)
        await this.$bvToast.show('toast-softdelete')
      }catch(err) {
        alert(err);
      }
    },

    async redirectInfo(id){
      try{
        await this.$store.dispatch('billing/GET_INFO_BY_ID', id)
        this.$bvModal.show( "info")
      }catch(err) {
        alert(err);
      }
    },

    async redirectMultiInfo(){
      try{
        await this.$store.commit('billing/SET_MULTI_INFO', this.selected)
        this.$bvModal.show( "multiInfoModal")
        this.$bvToast.hide('toast-selected')
      }catch(err) {
        alert(err);
      }
    },
    async multiSoftDelete(){
      try{
        await this.$store.dispatch('billing/SOFTDELETE_MULTI', this.selected)
        await this.$bvToast.show('toast-softdelete')
      }catch(err) {
        alert(err);
      }
    },
  },
  async created(){
    try {
      await this.$store.dispatch('billing/GET_ALL')
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