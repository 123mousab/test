<template>
  <div>
    <div class="mt-3">
      <label>
        <router-link to="/">{{ $t("Home") }}</router-link>
      </label>
      <span>/</span>
      <label active>{{ $t("Subscribes") }}</label>
    </div>
    <h4 class="mt-3">{{ $t("Subscribes") }}</h4>
    <div class="main-card mt-3 card">
      <div class="card-body">
        <div>
          <div class="row">
            <div class="col-md-6">
              <span id="addWrapper">
                <button
                  class="btn mb-2 btn-transition btn-outline-primary"
                  @click="addNewItem()"
                >
                  {{ $t("AddNew") }}
                </button>

                <download-excel
                  class="btn mb-2 mr-2 btn-transition btn-outline-success"
                  :data="items"
                  :fields="lang == 'ar' ? itemsExportAr : itemsExport"
                  :worksheet="$t('Subscribes')"
                  name="subscribes.xls"
                >
                  {{ $t("ExportExcel") }}
                </download-excel>

                <button
                  class="btn mb-2 btn-transition btn-outline-info"
                  @click="openFilters()"
                >
                  {{ $t("Filters") }}
                </button>

                <button
                  class="btn mb-2 btn-transition btn-outline-danger"
                  @click="clearFilters()"
                >
                  {{ $t("ClearFilters") }}
                </button>
              </span>
            </div>
          </div>
          <b-table
            show-empty
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
            <template v-slot:cell(status)="row">
              <span
                v-if="row.item.status"
                style="color: green; font-weight: bold"
              >
                {{ $t("Active") }}
                <div
                  @click="updateStatus(row.item)"
                  :style="
                    row.item.status ? 'background:green' : 'background:red'
                  "
                  style="
                    display: inline-block;
                    cursor: pointer;
                    width: 10px;
                    height: 10px;
                    border-radius: 50%;
                  "
                ></div>
              </span>
              <span v-else style="color: red; font-weight: bold">
                {{ $t("InActive") }}
                <div
                  @click="updateStatus(row.item)"
                  :style="
                    row.item.status ? 'background:green' : 'background:red'
                  "
                  style="
                    display: inline-block;
                    cursor: pointer;
                    width: 10px;
                    height: 10px;
                    border-radius: 50%;
                  "
                ></div>
              </span>
            </template>
            <template v-slot:cell(actions)="row">
              <b-dropdown
                style="display: inline-block"
                no-flip
                :text="$t('Actions')"
                variant="primary"
              >
                <span :id="'editWrapper' + row.index">
                  <button
                    type="button"
                    class="dropdown-item text-primary"
                    @click="editRow(row.item.id)"
                  >
                    {{ $t("Edit") }}
                  </button>
                </span>
                <span :id="'deleteWrapper' + row.index">
                  <button
                    type="button"
                    class="dropdown-item text-danger"
                    @click="deleteRow(row.item.id)"
                  >
                    {{ $t("Delete") }}
                  </button>
                </span>
                <span :id="'detailsWrapper' + row.index">
                  <button
                    type="button"
                    class="dropdown-item text-success"
                    @click="detailsRow(row.item.id)"
                  >
                    {{ $t("Details") }}
                  </button>
                </span>
              </b-dropdown>
              <el-button
                @click="stopSubscribtion(row.item.id)"
                v-if="row.item.is_ended == 1"
                type="danger"
                >{{ $t("StopSubscribtion") }}</el-button
              >
              <el-button @click="startSubscribtion(row.item.id)" v-else type="success">{{
                $t("StartSubscribtion")
              }}</el-button>

               <el-button
                @click="giveAction(row.item.id)"
                style="background:#008059;color:#fff;"
                >{{ $t("Give") }}</el-button
              >

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
          <div class="form-group">
            <label class="control-label">{{ $t("Name") }}</label>
            <input
              type="text"
              name="name"
              class="form-control"
              v-model="filters.name"
            />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
          <div class="form-group">
            <label class="control-label">{{ $t("Status") }}</label>
            <el-select v-model="filters.status" clearable filterable>
              <el-option
                v-for="option in statuses"
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
          <b-button @click="filterData" variant="success">{{
            $t("Filter")
          }}</b-button>
          <b-button @click="closeFilters" variant="secondary">{{
            $t("Close")
          }}</b-button>
        </div>
      </div>
    </el-drawer>

    <b-modal
      v-model="stopSubscribtionModal"
      id="info-modal"
      :title="$t('StopSubscribtion')"
      ok-only
      hide-footer
      size="lg"
    >
      <form ref="form" @submit.stop.prevent="stop">
        <div class="row">
          <div class="col-md-4">
            <div
              class="form-group"
              :class="{ 'has-error': errors.has('addEditValidation.from_date') }"
            >
              <label class="control-label">{{ $t("FromDate") }}</label>
              <input
                type="date"
                name="from_date"
                class="form-control"
                v-validate="lang=='ar'?'required': ''"
                data-vv-scope="addEditValidation"
                :data-vv-as="$t('FromDate')"
                v-model="addEditObj.start_date"
              />
              <div
                class="help-block"
                v-if="errors.has('addEditValidation.from_date')"
              >
                {{ errors.first("addEditValidation.from_date") }}
              </div>
            </div>
          </div>

           <div class="col-md-4">
            <div
              class="form-group"
              :class="{ 'has-error': errors.has('addEditValidation.to_date') }"
            >
              <label class="control-label">{{ $t("ToDate") }}</label>
              <input
                type="date"
                name="to_date"
                class="form-control"
                v-validate="''"
                data-vv-scope="addEditValidation"
                :data-vv-as="$t('ToDate')"
                v-model="addEditObj.end_date"
              />
              <div
                class="help-block"
                v-if="errors.has('addEditValidation.to_date')"
              >
                {{ errors.first("addEditValidation.to_date") }}
              </div>
            </div>
          </div>

        </div>

        <button
          type="button"
          class="btn c-ml-2 mb-2 btn-icon btn-secondary float-left"
          @click="stopSubscribtionModal = false"
        >
          {{ $t("Close") }}
        </button>
        <b-button type="primary" variant="primary" class="float-left ml-2">
          {{ $t("Stop") }}
        </b-button>

      </form>
    </b-modal>


     <b-modal
      v-model="giveModal"
      id="info-modal"
      :title="$t('Give')"
      ok-only
      hide-footer
      size="lg"
    >
      <form ref="form" @submit.stop.prevent="give">
        <div class="row">

           <div class="col-md-4">
            <div
              class="form-group"
              :class="{ 'has-error': errors.has('addEditValidation.days_no') }"
            >
              <label class="control-label">{{ $t("DaysNo") }}</label>
              <input
                type="number"
                name="days_no"
                class="form-control"
                v-validate="lang=='ar'?'required|numeric|min:0': ''"
                data-vv-scope="addEditValidation"
                :data-vv-as="$t('DaysNo')"
                v-model="addEditObj2.days_no"
                min="0"
              />
              <div
                class="help-block"
                v-if="errors.has('addEditValidation.days_no')"
              >
                {{ errors.first("addEditValidation.days_no") }}
              </div>
            </div>
          </div>

        </div>

        <button
          type="button"
          class="btn c-ml-2 mb-2 btn-icon btn-secondary float-left"
          @click="giveModal = false"
        >
          {{ $t("Close") }}
        </button>
        <b-button type="primary" variant="primary" class="float-left ml-2">
          {{ $t("Give") }}
        </b-button>

      </form>
    </b-modal>

  </div>
</template>
<script>
export default {
  data() {
    return {
      itemId: null,
      giveModal: false,
      stopSubscribtionModal: false,
      addEditObj: {
        start_date: "",
        end_date: "",
      },
      addEditObj2: {
        days_no: ""
      },
      filters: {
        name: "",
        status: null,
        page: null,
      },
      lang: localStorage.getItem("lang") || "ar",
      itemsExport: {
        // 'Image': 'image',
        "Customer Name": "customer_name",
        status: {
          field: "status",
          callback: (value) => {
            if (value) return "فعال";
            else return "معطل";
          },
        },
      },
      itemsExportAr: {
        //  'الصورة': 'image',
        "الاسم عربي": "customer_name",
        الحالة: {
          field: "status",
          callback: (value) => {
            if (value) return "فعال";
            else return "معطل";
          },
        },
      },
      filterDrawer: false,
      items: [],
      fields: [
        {
          key: "customer_name",
          label: this.$t("Customer Name"),
          sortable: true,
          sortDirection: "desc",
        },
        {
          key: "package_name",
          label: this.$t("Package Name"),
          sortable: true,
          sortDirection: "desc",
        },
        {
          key: "start_date",
          label: this.$t("Start Date"),
          sortable: true,
          sortDirection: "desc",
        },
        {
          key: "end_date",
          label: this.$t("End Date"),
          sortable: true,
          sortDirection: "desc",
        },
        {
          key: "actions",
          label: "",
          thStyle: "width:30%;",
          class: "action-column",
        },
      ],
      pagination: {
        currentPage: 1,
        perPage: 10,
      },
      sortBy: "",
      sortDesc: false,
      sortDirection: "asc",
      filter: null,
      filterOn: ["name", "status"],
      filteredItems: [],
      editMode: false,
      viewMode: false,
      statuses: [
        {
          label: this.$t("Active"),
          value: 1,
        },
        {
          label: this.$t("InActive"),
          value: 0,
        },
      ],
    };
  },
  methods: {
    clearModalData() {
        this.addEditObj= {
        start_date: "",
        end_date: "",
      }
    },
    give() {
        this.$validator.validateAll("addEditValidation").then((result) => {
        if (result) {

        let data = {
            id: this.itemId,
            formData: this.addEditObj2
        }
        this.$store
        .dispatch("subscribe/giveSubscribtion",data)
        .then((_) => {
           this.$notify.success({
            duration: 3000,
            message: "تم تعويض الاشتراك بنجاح",
            title: this.$t("GiveSubscribtion"),
            customClass: "top-center",
          });
          this.giveModal=false;
        })
          .catch((_) => {
            this.$notify.error({
              duration: 3000,
              message: 'حصل خطأ في تعويض الاشتراك',
              title: this.$t("GiveSubscribtion"),
              customClass: "top-center",
            });
          });

        }
      });
    },
    stop() {
      this.$validator.validateAll("addEditValidation").then((result) => {
        if (result) {

            let data = {
            id: this.itemId,
            formData: this.addEditObj
        }
        this.$store
        .dispatch("subscribe/stopSubscribtion",data)
        .then((_) => {
           this.$notify.success({
            duration: 3000,
            message: "تم إيقاف الاشتراك بنجاح",
            title: this.$t("StopSubscribtion"),
            customClass: "top-center",
          });
          this.stopSubscribtionModal=false;
          this.initData();
        })
          .catch((_) => {
            this.$notify.error({
              duration: 3000,
              message: 'حصل خطأ في إيقاف الاشتراك',
              title: this.$t("StopSubscribtion"),
              customClass: "top-center",
            });
          });


        }
      });
    },
    startSubscribtion(id) {
        this.itemId= id;
         this.$store
        .dispatch("subscribe/startSubscribtion",this.itemId)
        .then((_) => {
           this.$notify.success({
            duration: 3000,
            message: "تم استئناف الاشتراك بنجاح",
            title: this.$t("StartSubscribtion"),
            customClass: "top-center",
          });
          this.initData();
        })
          .catch((_) => {
            this.$notify.error({
              duration: 3000,
              message: 'حصل خطأ في استئناف الاشتراك',
              title: this.$t("StartSubscribtion"),
              customClass: "top-center",
            });
          });
    },
    stopSubscribtion(id) {
      this.itemId= id;
      this.clearModalData();
      this.stopSubscribtionModal = true;
    },
    giveAction(id) {
        this.itemId= id;
        this.addEditObj2.days_no= '';
        this.giveModal = true;
    },
    clearFilters() {
      this.filters = {
        name: null,
        status: null,
        page: null,
      };
      this.initData(this.filters);
    },
    openFilters() {
      this.filterDrawer = true;
    },
    closeFilters() {
      this.filterDrawer = false;
    },
    filterData() {
      this.pagination.currentPage = 1;
      this.filters.page = 1;
      this.initData(this.filters);
      this.closeFilters();
    },
    deleteRow(idVal) {
      this.$confirm(
        this.$t("DeleteConfirmMessage"),
        this.$t("DeleteConfirmTitle"),
        {
          confirmButtonText: this.$t("DeleteConfirmOk"),
          cancelButtonText: this.$t("DeleteConfirmCancel"),
          type: "warning",
        }
      ).then(() => {
        this.$store
          .dispatch("subscribe/removeData", idVal)
          .then((_) => {
            this.updateData(null);
            this.$notify.success({
              duration: 3000,
              message: this.$t("DeleteSuccessfully"),
              title: this.$t("Delete"),
              customClass: "top-center",
            });
          })
          .catch((_) => {
            this.$notify.error({
              duration: 3000,
              message: this.$t("DeleteFailed"),
              title: this.$t("Delete"),
              customClass: "top-center",
            });
          });
      });
    },
    onFiltered(filteredItems) {
      this.pagination.currentPage = 1;
      this.filteredItems = filteredItems;
    },
    initData(filters) {
      this.$store
        .dispatch("subscribe/getData", filters)
        .then((res) => {
          this.items = res.data.resources;
          this.pagination.from = res.data.pagination.from;
          this.pagination.to = res.data.pagination.to;
          this.pagination.total = res.data.pagination.total;
        })
        .catch((_) => {
          this.$notify.error({
            duration: 3000,
            message: this.$t("GetDataFailed"),
            title: this.$t("GetData"),
            customClass: "top-center",
          });
        });
    },
    addNewItem: function () {
      this.$router.push({ name: "subscribeAddEdit" });
    },
    detailsRow(id) {
      this.$router.push({
        name: "subscribeAddEdit",
        params: { id: id, page: "details" },
      });
    },
    editRow(id) {
      this.$router.push({
        name: "subscribeAddEdit",
        params: { id: id, page: "edit" },
      });
    },
    updateData() {
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
        .dispatch("subscribe/updateStatus", JSON.stringify(sendData))
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
  },
};
</script>
