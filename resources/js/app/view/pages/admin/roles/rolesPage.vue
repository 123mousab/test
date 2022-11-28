<template>
    <div>
    <div class="mt-3">
        <label>
            <router-link to="/">{{$t('Home')}}</router-link>
        </label>
        <span>/</span>
        <label active>{{$t('Roles')}}</label>
    </div>
        <h4 class="mt-3">{{$t('Roles')}}</h4>
        <div class="main-card mt-3 card">
            <div class="card-body">
                <div>
                    <div class="row">
                        <div class="col-md-6">
                            <!-- <span id="addWrapper">
                                <download-excel
                                    class = "btn mb-2 mr-2 btn-transition btn-outline-success"
                                    :data   = "items"
                                    :fields = "itemsExport"
                                    :worksheet = "$t('Roles')"
                                    name= "roles.xls"
                                    >
                                    {{$t('ExportExcel')}}
                                </download-excel>

                            </span> -->
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
                         <!-- <template v-slot:cell(image)="row">
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
                         </template> -->
                        <!-- <template v-slot:cell(status)="row">
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
                        </template> -->
                    </b-table>
                    <!-- <div class="row">
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
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        name: 'roles',
        data() {
            return {
                // filters: {
                //     page: null
                // },
                lang: localStorage.getItem("lang") || 'ar',
                // statuses: [
                //     {
                //         label: this.$t('فعال'),
                //         value: 1
                //     },
                //     {
                //         label: this.$t('غير فعال'),
                //         value: 0
                //     }
                // ],
                // itemsExport: {
                //     // 'Image': 'image',
                //     'Name en': 'name.en',
                //     'Name ar': 'name.ar',
                //     'status': {
                //         field: 'status',
                //         callback: (value) => {
                //             if(value) return 'فعال';
                //             else return 'معطل';
                //         }
                //     },
                // },
                // itemsExportAr: {
                //     //  'الصورة': 'image',
                //     'الاسم عربي': 'name.ar',
                //     'الاسم إنجليزي': 'name.en',
                //     'الحالة': {
                //         field: 'status',
                //         callback: (value) => {
                //             if(value) return 'فعال';
                //             else return 'معطل';
                //         }
                //     },
                // },
                items: [],
                fields: [
                    // { key: 'image', label: this.$t('Image'), sortable: true, sortDirection: 'desc' },
                    { key: 'name', label: 'الاسم', sortable: true, sortDirection: 'desc' },
                    // { key: 'name.en', label: 'الاسم انجليزي', sortable: true, sortDirection: 'desc' },
                    // { key: 'status', label: this.$t('Status'), sortable: true, sortDirection: 'desc' },
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
            }
        },
        methods: {
            // changeImage(event, id) {
            //     if (event.target.files.length) {
            //         let toSend =  {
            //             image: event.target.files[0],
            //             id: id
            //         }
            //         this.$store
            //             .dispatch("roles/updateImage", toSend)
            //             .then((_) => {
            //                 this.$notify.success({
            //                     duration: 3000,
            //                     message: this.$t("ImageUpdatedSuccessfully"),
            //                     title: this.$t("ImageUpdated"),
            //                     customClass: "top-center",
            //                 });
            //                 this.initData(null);
            //             })
            //             .catch((_) => {
            //                 this.$notify.error({
            //                     duration: 3000,
            //                     message: this.$t("ImageUpdatedFailed"),
            //                     title: this.$t("ImageUpdated"),
            //                     customClass: "top-center",
            //                 });
            //             });
            //     }
            // },
            onFiltered(filteredItems) {
                this.pagination.currentPage = 1;
                this.filteredItems = filteredItems;
            },
            initData() {
                this.$store
                .dispatch("roles/getData")
                .then(res => {
                    this.items = res.data;
                    // this.pagination.from = res.data.pagination.from;
                    // this.pagination.to = res.data.pagination.to;
                    // this.pagination.total = res.data.pagination.total;
                })
                .catch(error => {
                    this.$notify.error({
                        duration: 3000,
                        message: this.$t("GetDataFailed"),
                        title: this.$t("GetData"),
                        customClass: "top-center",
                    });
                })
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
                .dispatch("roles/updateStatus", JSON.stringify(sendData))
                .then(() => {
                    this.$notify.success({
                        duration: 3000,
                        message: this.$t("UpdatedSuccessfully"),
                        title: this.$t("Update"),
                        customClass: "top-center",
                    });
                    this.initData();
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
            // this.filters.page = page;
            this.initData();
        },
    },
        created() {
            this.initData();
        }
    }
</script>
