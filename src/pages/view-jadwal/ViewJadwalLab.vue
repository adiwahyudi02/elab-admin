<template>
    <Main>
        <div v-if="isLoading" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 999">
            <b-spinner style="width: 3rem; height: 3rem;" variant="info" label="Large Spinner"></b-spinner>
        </div>
        <div class="mt-3" @keydown.esc="CancelSwitch">
            <div v-if="isLoading && jadwals == {}" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 999">
                <b-spinner style="width: 3rem; height: 3rem;" variant="info" label="Large Spinner"></b-spinner>
            </div>
            <div v-else class="card-scene p-3">
                <div v-if="isLoadingAction" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 999">
                    <b-spinner variant="primary" type="grow" label="Spinning" small></b-spinner>
                    <b-spinner variant="primary" type="grow" label="Spinning" small></b-spinner>
                    <b-spinner variant="primary" type="grow" label="Spinning" small></b-spinner>
                </div>

            <div class="d-flex justify-content-between align-items-center">
                <p class="ml-5" style="color: lightseagreen"><b>{{jadwals.nama_lab}} Schedule</b></p>
                <div>
                    <b-button id="popover-target-1" class="btn-info">
                        ?
                    </b-button>
                    <b-popover target="popover-target-1" triggers="hover" placement="top">
                        <template v-slot:title>Information</template>
                        <p>
                            Drag and drop to <b> move schedule</b>
                            Shift + left click mouse to <b> Switch schedule</b>
                        </p>
                    </b-popover>
                </div>
            </div>

            <b-toast id="toast-other-switch" title="Information switch schedule." no-auto-hide :noCloseButton="true" variant="info" toaster="b-toaster-bottom-right">
                <div class="d-flex justify-content-between">
                    <p class="mr-2">
                        Choose one other schedule you want switch. 
                    </p>
                    <div>
                        <b-button variant="danger" @click="CancelSwitch" title="esc">Cancel</b-button>
                    </div>
                </div>
            </b-toast>

            
            <b-toast id="toast-switched" title="Successfuly to exchanged." variant="success" toaster="b-toaster-bottom-right">
                Schedule will be exchanged.
            </b-toast>
            
            <Container
            style="width: 100%;"
            class="px-2 mt-3 d-flex justify-content-start flex-row flex-wrap my-flex-container"
            >
            <!-- <Draggable v-for="column in scene.children" :key="column.id"> -->
            <div v-for="(column, index) in jadwals.jadwal_hari" :key="column.id">
                <!-- <div :class="column.props.className"> -->
                <div class="card-container scrollbar rounded" orientation="vertical" id="style-2">
                <div  class="mx-1 pb-2 px-2 rounded force-overflow"  style="width: 270px;">
                    <div class="card-column-header rounded-top py-1" style="color:lightseagreen; z-index: 99; background: #f5f5f7; width: 260px">
                    <span class="column-drag-handle d-flex justify-content-between align-items-center p-2">
                        <p class="mb-0" style="font-size: 10pt;color: lightseagreen">
                        <b>
                            {{column.hari}}
                        </b>
                        </p>
                        <div>
                            <i class="fas fa-ellipsis-h p-2 rounded icon-list-actions" @click="openListActions(column.id)"></i>

                            <div class="" style="" v-if="ConditionListActions == column.id">
                                <a @click="closeListActions">
                                    <div class="" style="width: 100%; height: 100vh; position: fixed; z-index: 9; top: 0; left:0;"></div>
                                </a>
                                <div class="bg-white shadow shadow-lg rounded p-2 pt-2 pb-3 mt-2" style="width: 270px;font-size: 8pt; cursor: pointer; position: absolute; z-index: 99;">
                                    <div class="d-flex justify-content-between align-items-center p-2 mb-1" style="border-bottom: 1px solid darkgray;">
                                        <p class="mb-0 text-dark" style="font-size:9pt">
                                            <b>
                                                List Actions
                                            </b>
                                            </p>
                                        <div class="d-flex justify-content-end">
                                            <i class="fas fa-times text-dark" style="cursor: pointer; font-size: 10pt;" id="icon-close-create-team" @click="closeListActions"></i>
                                        </div>
                                    </div>
                                    <!-- <div class="chooseTeam rounded p-2">
                                        <div class="d-flex justify-content-start align-items-center">
                                            <p class="mb-0 text-dark">Edit List</p>
                                        </div>
                                    </div> -->

                                    <div>
                                        <b-button id="popover-button-variant-list" class="btn btn-sm btn-danger container" href="#" tabindex="0">Remove this list</b-button>
                                        <b-popover placement="bottom" target="popover-button-variant-list" variant="seondary" triggers="focus">
                                            <p class="mb-1"> Are you sure ?</p>
                                            <b-button class="btn btn-sm container" variant="primary" @click="removeList(column.id)">
                                                Yes
                                            </b-button>
                                        </b-popover>
                                    </div>

                                </div>
                            <div>
                                
                            </div>
                        </div>     
                        </div>
                    </span>
                    </div>
                    <Container
                    group-name="col"
                    @drop="(e) => onCardDrop(column.id, e, index, column)"
                    @drag-start="(e) => log('drag start', e)"
                    @drag-end="(e) => log('drag end', e)"
                    :get-child-payload="getCardPayload(column.id)"
                    drag-class="card-ghost"
                    drop-class="card-ghost-drop"
                    :drop-placeholder="dropPlaceholderOptions"
                    >
                        
                    <Draggable v-for="card in column.jadwal_lab" :key="column.id + card.id" class="py-1" >
                        <!-- modalDetailJadwal -->
                        <b-btn @click.left.exact="redirectInfoJD(card.id, column)"  @click.shift="switchDokter(card, {'id':column.id, 'hari': column.hari})" variant="light" class="rouded p-0" style="width: 100%">
                            <div class="card shadow-sm p-2 card-list card-dd" style="margin-bottom: 0" :id="column.id + '-' + card.id">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <small style="color: lightseagreen">{{card.jam_mulai}} - {{card.jam_selesai}}</small>
                                    </div>
                                </div>
                                
                            <div style="font-size: 10pt;" class="d-flex justify-content-between">
                                <p>
                                {{ card.nama_kelas }}
                                </p>
                                <div>
                                <i class="fas fa-pencil-alt ml-1 icon-edit-card p-2 rounded" style="font-size: 9pt;"></i>
                                </div>
                            </div>
                            
                            </div>
                        </b-btn>
                        
                    </Draggable>
                    </Container>
                </div>
                </div>
                
                <CreateJadwalLab :column="column" />
                <!-- <button @click="openAddClass(column.id)">Create new class schedule</button>
                <div v-if="conditionAddClass == column.id">
                    
                </div> -->
            </div>    
                <CreateHari />
                <InfoJadwalKelas :column="column"/>
            </Container>
            <b-toast id="toast-softdelete-hari" title="Schedule successfuly to softdelete." variant="success" toaster="b-toaster-bottom-right">
                Schedule will be put in trash.
            </b-toast>
            <b-toast id="bentrok-jl" :title="'You have a conflict schedule. in ' + bentrok.columnId.hari + ' - ' + bentrok.data.nama_kelas + ' | ' + bentrok.data.jam_mulai + '-' + bentrok.data.jam_selesai" :no-auto-hide="true" variant="danger" toaster="b-toaster-bottom-full">
                Because in this schedule started at and ended at accros a schedule. please check again !
            </b-toast>
        </div>
        </div>
    </Main>
</template>

<script>

import CreateHari from '../../components/view-jadwal/CreateHari'
import InfoJadwalKelas from '../../components/view-jadwal/InfoJadwalKelas'
import CreateJadwalLab from '../../components/view-jadwal/CreateJadwalLab'
import Main from '../../layouts/Main'

import { Container, Draggable } from 'vue-smooth-dnd'
import { mapState } from 'vuex'

export default {
    name: 'Cards',
    components: {Container, Draggable, CreateHari, CreateJadwalLab, InfoJadwalKelas, Main},
    data(){
        return {
            column: '',

            upperDropPlaceholderOptions: {
                className: 'cards-drop-preview',
                animationDuration: '150',
                showOnTop: true
            },
            dropPlaceholderOptions: {
                className: 'drop-preview',
                animationDuration: '150',
                showOnTop: true
            },

            dataSwitch: [],
            conditionAddClass: '',
            ConditionListActions: ''
        }
    },
    computed: {
        ...mapState({
            isLoading: state => state.isLoading,
            isLoadingAction: state => state.isLoadingAction,
            bentrok: state => state.view_jadwal.bentrok,
            jadwals: state => state.view_jadwal.items,
        }),
    },
    methods : {

        openListActions(e) {
            this.ConditionListActions = e;
        },
        closeListActions(e) {
            this.ConditionListActions = e;
        },

        async removeList(id){
            try{
                await this.$store.dispatch('view_jadwal/SOFTDELETE_ONE_JADWAL_HARI', {
                    'id': id,
                    'id_lab': this.$route.params.id
                })
                await this.$bvToast.show('toast-softdelete-hari')
            }catch(err) {
                alert(err);
            }
        },

        async switchDokter(card, column){
            
            await this.dataSwitch.push({
                'card': card, 
                'column': column
            })
            console.log('b', column.id + '-' + card.id)
            document.getElementById(column.id + '-' + card.id).style.backgroundColor = '#e6f1fc';
            if (this.dataSwitch.length == 2) {
                try {
                    console.log(this.dataSwitch);
                    console.log(column.id + '-' + card.id)
                    await this.$store.dispatch('view_jadwal/SWITCH_KELAS', this.dataSwitch)
                    const all = document.getElementsByClassName('card-dd');
                    for (var i = 0; i < all.length; i++) {
                        all[i].style.backgroundColor = 'white';
                    }
                    this.dataSwitch = []
                    this.$bvToast.hide('toast-other-switch')
                    this.$bvToast.show('toast-switched')
                } catch (error) {
                    alert(error)
                }
                
            }else if (this.dataSwitch.length < 2) {
                this.$bvToast.show('toast-other-switch')
            }else {
                const all = document.getElementsByClassName('card-dd');
                for (let i = 0; i < all.length; i++) {
                    all[i].style.backgroundColor = 'white';
                }
                this.dataSwitch = []
                this.dataSwitch.push({
                    'card': card, 
                    'column': column
                })
                document.getElementById(column.id + '-' + card.id).style.backgroundColor = '#e6f1fc';
                this.$bvToast.show('toast-other-switch')
            }
        },
        
        async CancelSwitch(){
            const all = document.getElementsByClassName('card-dd');
            for (var i = 0; i < all.length; i++) {
                all[i].style.backgroundColor = 'white';
            }
            this.$bvToast.hide('toast-switching')
            this.$bvToast.hide('toast-other-switch')
            this.dataSwitch = []
            console.log(this.dataSwitch);
        },

        async redirectInfoJD(id, column){
            try{
                this.column = column
                await this.$store.dispatch('view_jadwal/GET_INFO_JADWAL_KELAS_BY_ID', id)
                await this.$store.dispatch('kelas/REFRESH_GET_ALL')
                this.$bvModal.show("modalInfo")
            }catch(err) {
                alert(err);
            }
        },

        async onCardDrop (columnId, dropResult, index) {
            try {
                await this.$store.commit('SET_ISLOADING_ACTION', true, { root: true })
                let id = this.$route.params.id;
                await this.$store.dispatch('view_jadwal/ON_CARD_DROP', { columnId, dropResult, id, index})

                if (!this.bentrok.conditionCekLintasJadwalBentrok) {
                    this.$bvToast.show('bentrok-jl')
                }else{
                    this.$bvToast.hide('bentrok-jl')
                }

                await this.$store.commit('SET_ISLOADING_ACTION', false, { root: true })
            } catch (error) {
                alert(error)
            }
        },

        getCardPayload (columnId) {
            console.log(columnId);
            
            return index => {
                return this.jadwals.jadwal_hari.filter(p => p.id === columnId)[0].jadwal_lab[index]
            }
        },

        dragStart () {
            console.log('drag started')
        },
        log (...params) {
            console.log(...params)
        },

        countDownChanged(dismissCountDown) {
            this.dismissCountDown = dismissCountDown
        }
        
    },

    async created(){
        try {
            let id = this.$route.params.id;
            await this.$store.dispatch('view_jadwal/GET_ALL', id)
        } catch (error) {
            alert(error)   
        }
    }
}
</script>

<style>

</style>