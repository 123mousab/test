<template>
    <div>
    <div class="mt-3">
        <label>
            <router-link to="/">{{$t('Home')}}</router-link>
        </label>
        <span>/</span>
        <label active>مجموعات الاشخاص</label>
    </div>
        <h4 class="mt-3">مجموعات الاشخاص</h4>
        <div class="main-card mt-3 card">
            <div class="card-body">
                <div>
                    <div class="row">
                        <div class="col-md-6">
                            <span id="addWrapper">
                                <button  class="btn mb-2 btn-transition btn-outline-primary"
                                @click='addNewItem()'>
                                    {{$t("AddNew")}}
                                </button>

                                <download-excel
                                    class = "btn mb-2 mr-2 btn-transition btn-outline-success"
                                    :data   = "items"
                                    :fields = "itemsExport"
                                    :worksheet = "'مجموعات الاشخاص'"
                                    name= "groups.xls"
                                    >
                                    {{$t('ExportExcel')}}
                                </download-excel>


                                <button  class="btn mb-2 btn-transition btn-outline-info"
                                @click='openFilters()'>
                                    {{$t("Filters")}}
                                </button>

                                <button  class="btn mb-2 btn-transition btn-outline-danger"
                                @click='clearFilters()'>
                                    {{$t("ClearFilters")}}
                                </button>


                            </span>
                        </div>
                        <div class="col-md-6">
                            <div  class="input-group input-group">
                                <input type="text" name="name" class="form-control" v-model="filter" :placeholder="$t('TypeToSearch')">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-icon btn-secondary" @click="filter = ''">
                                    {{$t("Clear")}}
                                </button>
                            </div>
                            </div>
                        </div>
                    </div>
                     <b-table show-empty
                            :empty-filtered-text="$t('emptyTable')"
                            :empty-text="$t('emptyTable')"
                            stacked="md"
                            :bordered="true"
                            :striped="true"
                            :items="items"
                            :fields="fields"
                            :per-page="pagination.perPage"
                            :filter="filter"
                            :filterIncludedFields="filterOn"
                            :sort-by.sync="sortBy"
                            :sort-desc.sync="sortDesc"
                            :sort-direction="sortDirection"
                            @filtered="onFiltered">
                         <template v-slot:cell(status)="row">
                            <span v-if="row.item.status" style="color: green; font-weight: bold">
                                {{$t('Active')}}
                                <div @click="updateStatus(row.item)"
                                     :style="row.item.status?'background:green': 'background:red'"
                                     style="display:inline-block;cursor:pointer;width:10px;height:10px;border-radius:50%;">
                                </div>
                            </span>
                             <span v-else style="color: red; font-weight: bold">
                                {{$t('InActive')}}
                                 <div @click="updateStatus(row.item)"
                                      :style="row.item.status?'background:green': 'background:red'"
                                      style="display:inline-block;cursor:pointer;width:10px;height:10px;border-radius:50%;">
                                </div>
                                </span>
                         </template>
                        <template v-slot:cell(actions)="row" >
                            <b-dropdown no-flip :text='$t("Actions")' class="" variant="primary">
                                <span :id="'editWrapper'+row.index">
                                    <button type="button"  class="dropdown-item text-primary"
                                        @click='editRow(row.item)'>
                                        {{$t("Edit")}}
                                    </button>
                                </span>
                                <span :id="'deleteWrapper'+row.index">
                                    <button type="button"  class="dropdown-item text-danger"
                                        @click='deleteRow(row.item.id)'>
                                        {{$t("Delete")}}
                                    </button>
                                </span>
                                <span :id="'detailsWrapper'+row.index">
                                    <button type="button"  class="dropdown-item text-success"
                                        @click='detailsRow(row.item)'>
                                        {{$t("Details")}}
                                    </button>
                                </span>
                            </b-dropdown>
                        </template>
                    </b-table>
                    <div class="row">
                        <div class="col-md-6">
                                <b-pagination
                                    v-model="pagination.currentPage"
                                    :total-rows="pagination.total"
                                    :per-page="pagination.perPage"
                                    @change="paginationChange"
                                    class="my-0"
                                >
                                </b-pagination>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <b-modal
            v-model="infoModalShow"
            id="info-modal"
            :title="infoModalTitle"
             ok-only
             hide-footer
             size="lg"
        >
            <addEdit
                :items="items"
                :idSelected="idSelected"
                @saveAddEdit="saveAddEdit"
                @closeAddEdit="infoModalShow=false;"
                :editMode="editMode"
                :viewMode="viewMode"
                >
            </addEdit>
        </b-modal>

        <el-drawer
            :title="$t('Filters')"
            :visible.sync="filterDrawer"
            :direction="'ltr'"
            :size="'20%'"
        >
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                <div class="form-group"
                >
                    <label class="control-label">{{$t("Name")}}</label>
                    <input type="text" name="name"
                        class="form-control"
                        v-model="filters.name"
                    >
                </div>
            </div>
            </div>
              <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                <div class="form-group"
                >
                    <label class="control-label">{{$t("Status")}}</label>
                    <el-select
                        v-model="filters.status"
                        clearable
                        filterable
                    >
                        <el-option v-for="option in statuses"
                            :value="option.value"
                            :label="option.label"
                            :key="option.value"
                        >
                        </el-option>
                    </el-select>
                </div>
            </div>
            </div>
            <div class="row">
                 <div class="col-md-1"></div>
                  <div class="col-md-10">
                      <b-button @click="filterData" variant="success">{{$t('Filter')}}</b-button>
                      <b-button @click="closeFilters" variant="secondary">{{$t('Close')}}</b-button>
                  </div>
            </div>
        </el-drawer>
    </div>
</template>
<script>
    import addEdit from './addEdit'

    export default {
        name: '',
        components: {
            addEdit
        },
        data() {
            return {
                filters: {
                    name: null,
                    status: null,
                    page: null
                },
                lang: localStorage.getItem("lang") || 'ar',
                filterDrawer: false,
                idSelected: 0,
                statuses: [
                    {
                        label: this.$t('فعال'),
                        value: 1
                    },
                    {
                        label: this.$t('غير فعال'),
                        value: 0
                    }
                ],
                itemsExport: {
                    // 'Image': 'image',
                    'Name en': 'name.en',
                    'Name ar': 'name.ar',
                    'status': {
                        field: 'status',
                        callback: (value) => {
                            if(value) return 'فعال';
                            else return 'معطل';
                        }
                    },
                },
                itemsExportAr: {
                    //  'الصورة': 'image',
                    'الاسم عربي': 'name.ar',
                    'الاسم إنجليزي': 'name.en',
                    'الحالة': {
                        field: 'status',
                        callback: (value) => {
                            if(value) return 'فعال';
                            else return 'معطل';
                        }
                    },
                },
                filterDrawer: false,
                items: [],
                fields: [
                    // { key: 'name_translate', label: this.$t('Name'), sortable: true, sortDirection: 'desc' },
                    { key: 'name.ar', label: 'الاسم عربي', sortable: true, sortDirection: 'desc' },
                    { key: 'name.en', label: 'الاسم انجليزي', sortable: true, sortDirection: 'desc' },
                    { key: 'status', label: this.$t('Status'), sortable: true, sortDirection: 'desc' },
                    { key: 'actions', label: '','thStyle':'width:10%;','class':'action-column' }
                ],
                pagination:{
                    currentPage: 1,
                    perPage:  10,
                },
                sortBy: 'id',
                sortDesc: false,
                sortDirection: 'asc',
                filter: null,
                filterOn: ['name', 'status'],
                filteredItems: [],
                infoModalTitle: '',
                infoModalShow: false,
                editMode: false,
                viewMode: false,
            }
        },
        methods: {
             clearFilters() {
                this.filters = {
                    name: null,
                    status: null,
                    page: null
                }
                this.initData(this.filters);
            },
            openFilters() {
                this.filterDrawer= true;
            },
            closeFilters() {
                this.filterDrawer= false;
            },
            filterData() {
                this.pagination.currentPage= 1;
                this.filters.page = 1;
                this.initData(this.filters);
                this.closeFilters();
            },
            deleteRow(idVal){
                this.$confirm(this.$t('DeleteConfirmMessage'), this.$t('DeleteConfirmTitle'), {
                    confirmButtonText: this.$t('DeleteConfirmOk'),
                    cancelButtonText: this.$t('DeleteConfirmCancel'),
                    type: 'warning'
                }).then(() => {
                    this.$store.dispatch("group_names/removeData", idVal)
                    .then(_ => {
                        let index=this.items.findIndex(x=>x.id==idVal);
                        this.items.splice(index,1);
                        this.$notify.success({
                            duration: 3000,
                            message: this.$t("DeleteSuccessfully"),
                            title: this.$t("Delete"),
                            customClass: "top-center",
                        });
                    })
                    .catch(_=>{
                       this.$notify.error({
                            duration: 3000,
                            message: this.$t("DeleteFailed"),
                            title: this.$t("Delete"),
                            customClass: "top-center",
                        });
                    })

                })
            },
            onFiltered(filteredItems) {
                this.pagination.currentPage = 1;
                this.filteredItems = filteredItems;
            },
            initData(filters) {
                this.$store
                .dispatch("group_names/getData", filters)
                .then(res => {
                    this.items = res.data.resources;
                    this.pagination.from = res.data.pagination.from;
                    this.pagination.to = res.data.pagination.to;
                    this.pagination.total = res.data.pagination.total;
                })
                .catch(error => {
                    // if(error.response.status == 500) {
                    //     localStorage.removeItem("token");
                    //     window.location.href = "/";
                    // }
                    this.$notify.error({
                        duration: 3000,
                        message: this.$t("GetDataFailed"),
                        title: this.$t("GetData"),
                        customClass: "top-center",
                    });
                })
            },
            addNewItem:function(){
                this.resetInfoModal()
                this.infoModalTitle = this.$t('AddNew');
                this.infoModalShow=true;
            },
            detailsRow(item) {
                this.resetInfoModal()
                this.idSelected =item.id;
                this.viewMode= true;
                this.infoModalTitle = this.$t('Details');
                this.infoModalShow=true;
            },
            editRow(item) {
                this.resetInfoModal()
                this.editMode= true;
                this.idSelected =item.id;
                this.infoModalTitle = this.$t('Edit');
                this.infoModalShow=true;
            },
            copyRow(item) {
                this.resetInfoModal()
                this.infoModalTitle = this.$t('AddNew');
                this.infoModalShow=true;
            },
            resetInfoModal() {
                this.editMode= false;
                this.viewMode= false;
                this.idSelected = 0;
                this.infoModalTitle = ''
            },
            saveAddEdit(){
                this.infoModalShow=false;
                this.initData(null);
            },
            updateStatus(data) {
            let status = null;
            if (data.status == 1) status = 0;
            else status = 1;
            let sendData = {
                ids: data.id + "",
                status: status,
            };
            this.$store
                .dispatch("group_names/updateStatus", JSON.stringify(sendData))
                .then(() => {
                    this.$notify.success({
                        duration: 3000,
                        message: this.$t("UpdatedSuccessfully"),
                        title: this.$t("Update"),
                        customClass: "top-center",
                    });
                    this.initData(this.filters);
                })

                .catch((error) => {
                    this.$notify.error({
                      duration: 3000,
                      message: error,
                      title: this.$t("Error"),
                      customClass: "top-center",
                  });
                });
    },
        paginationChange(page) {
            this.filters.page = page;
            this.initData(this.filters);
        },
    },
        created() {
            this.initData(this.filters);
        }
    }
</script>
