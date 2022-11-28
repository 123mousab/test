<template>
    <div>
    <div class="mt-3">
        <label>
            <router-link to="/">{{$t('Home')}}</router-link>
        </label>
        <span>/</span>
        <label active>{{$t('Recipies')}}</label>
    </div>
        <h4 class="mt-3">{{$t('Recipies')}}</h4>
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
                                    :fields = "lang =='ar'?itemsExportAr: itemsExport"
                                    :worksheet = "$t('Recipies')"
                                    name= "recipies.xls"
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
                            @filtered="onFiltered"
                        >

                          <template v-slot:cell(image)="row">
                            <div class="image-block">
                              <img
                                v-if="row.item.image"
                                width="80"
                                height="80"
                                style="border-radius: 50%; border: 1px solid #ccc"
                                :src="row.item.image"
                                alt=""
                              />
                              <img
                                v-else
                                src="/assets/images/default.png"
                                width="80"
                                height="80"
                                style="border-radius: 50%; border: 1px solid #ccc"
                                alt=""
                              />
                              <input
                                type="file"
                                class="chooseImage"
                                accept="image/*"
                                @change="changeImage($event, row.item.id)"
                              />
                            </div>
                        </template>

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
                                        @click='editRow(row.item.id)'>
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
                                        @click='detailsRow(row.item.id)'>
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
    export default {
        data() {
            return {
                filters: {
                    name: '',
                    status: null,
                    page: null
                },
                lang: localStorage.getItem("lang") || 'ar',
                itemsExport: {
                    // 'Image': 'image',
                    'Name en': 'name.en',
                    'Name ar': 'name.ar',
                    'Total Cost': 'total_cost',
                    'tools': 'tools',
                    'Ingredients': 'ingredients',
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
                    'السعر الإجمالي': 'total_cost',
                    'الأدوات': 'tools',
                    'المكون الغدائي': 'ingredients',
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
                    { key: 'image', label: this.$t('Image'), sortable: true, sortDirection: 'desc' },
                    // { key: 'name_translate', label: this.$t('Name'), sortable: true, sortDirection: 'desc' },
                    { key: 'name.ar', label: 'الاسم عربي', sortable: true, sortDirection: 'desc' },
                    { key: 'name.en', label: 'الاسم انجليزي', sortable: true, sortDirection: 'desc' },
                    { key: 'total_cost', label: this.$t('TotalCost'), sortable: true, sortDirection: 'desc' },
                    { key: 'group_name', label: 'الميزة', sortable: true, sortDirection: 'desc' },
                    { key: 'ingredient_primary_name', label: 'المكون الاساسي', sortable: true, sortDirection: 'desc' },
                    { key: 'is_protein_text', label: 'بروتين ام كارب'},
                    { key: 'not_ingredient', label: 'المكونات غير الاساسية', sortable: true, sortDirection: 'desc' },
                    { key: 'status', label: this.$t('Status'), sortable: true, sortDirection: 'desc' },
                    { key: 'actions', label: '','thStyle':'width:10%;','class':'action-column' }
                ],
                pagination:{
                    currentPage: 1,
                    perPage:  10,
                },
                sortBy: '',
                sortDesc: false,
                sortDirection: 'asc',
                filter: null,
                filterOn: ['name', 'status'],
                filteredItems: [],
                editMode: false,
                viewMode: false,
                statuses: [
                    {
                        label: this.$t('Active'),
                        value: 1
                    },
                    {
                        label: this.$t('InActive'),
                        value: 0
                    }
                ]
            }
        },
        methods: {
           changeImage(event, id) {
              if (event.target.files.length) {
                let toSend =  {
                  image: event.target.files[0],
                  id: id
                }
                this.$store
                  .dispatch("recipies/updateImage", toSend)
                  .then((_) => {
                    this.$notify.success({
                      duration: 3000,
                      message: this.$t("ImageUpdatedSuccessfully"),
                      title: this.$t("ImageUpdated"),
                      customClass: "top-center",
                    });
                    this.initData(null);
                  })
                  .catch((_) => {
                    this.$notify.error({
                      duration: 3000,
                      message: this.$t("ImageUpdatedFailed"),
                      title: this.$t("ImageUpdated"),
                      customClass: "top-center",
                    });
                  });
              }
            },
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
                    this.$store.dispatch("recipies/removeData", idVal)
                    .then(_ => {
                        this.updateData(null);
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
                .dispatch("recipies/getData", filters)
                .then(res => {

                    res.data.resources.map(data => {
                      data.tools= '';
                      data.ingredients= '';

                      for(let i=0; i< data.tool.length; i++) {
                        data.tools= data.tools + data.tool[i].name + '\n';
                      }

                      for(let i=0; i< data.ingredient.length; i++) {
                        data.ingredients= data.ingredients + data.ingredient[i].name + ` (${data.ingredient[i].quantity}) ` + '\n';
                      }

                    })
                    this.items = res.data.resources;
                    this.pagination.from = res.data.pagination.from;
                    this.pagination.to = res.data.pagination.to;
                    this.pagination.total = res.data.pagination.total;
                })
                .catch(_ => {
                    this.$notify.error({
                        duration: 3000,
                        message: this.$t("GetDataFailed"),
                        title: this.$t("GetData"),
                        customClass: "top-center",
                    });
                })
            },
            addNewItem:function(){
               this.$router.push({name: 'recipiesAddEdit'})
            },
            detailsRow(id) {
                this.$router.push({name: 'recipiesAddEdit', params: {id: id, page: 'details'}})
            },
            editRow(id) {
                this.$router.push({name: 'recipiesAddEdit', params: {id: id, page: 'edit'}})
            },
            updateData(){
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
                .dispatch("recipies/updateStatus", JSON.stringify(sendData))
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
            this.initData(null);
        }
    }
</script>
