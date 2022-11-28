<template>
  <div>

    <!-- راوتر التنقل -->
    <div class="mt-3">
      <label>
        <router-link to="/">{{ $t("Home") }}</router-link>
      </label>
      <span>/</span>
      <label>
        <router-link to="/packages">{{ $t("Packages") }}</router-link>
      </label>
      <span>/</span>

      <label active>
        <span v-if="id && !viewOnly">{{ $t("Edit") }}</span>
        <span v-else-if="viewOnly">{{ $t("Details") }}</span>
        <span v-else>{{ $t("AddNewPackages") }}</span>
      </label>
    </div>

    <!-- العنوان الرئيسي -->
    <h4 class="mt-3">
      <span v-if="id && !viewOnly">{{ $t("Edit") }}</span>
      <span v-else-if="viewOnly">{{ $t("Details") }}</span>
      <span v-else>{{ $t("AddNewPackages") }}</span>
    </h4>


    <div class="mt-3">

      <div ref="form">
        <b-card>
          <div class="row">

             <div class="col-md-4" >
              <div
                class="form-group"
                :class="{ 'has-error': errors.has('addEditValidation.nameAr') }"
              >
                <label class="control-label">{{ $t("NameAr") }}</label>
                <input
                  type="text"
                  name="nameAr"
                  class="form-control"
                  v-validate="lang=='ar'? 'required': ''"
                  data-vv-scope="addEditValidation"
                  :data-vv-as="$t('NameAr')"
                  v-model="addEditObj.name.ar"
                  :disabled="viewOnly"
                />
                <div
                  class="help-block"
                  v-if="errors.has('addEditValidation.nameAr')"
                >
                  {{ errors.first("addEditValidation.nameAr") }}
                </div>
              </div>
            </div>

            <div class="col-md-4">
              <div
                class="form-group"
                :class="{ 'has-error': errors.has('addEditValidation.nameEn') }"
              >
                <label class="control-label">{{ $t("NameEn") }}</label>
                <input
                  type="text"
                  name="nameEn"
                  class="form-control"
                  v-validate="lang=='en'? 'required': ''"
                  data-vv-scope="addEditValidation"
                  :data-vv-as="$t('NameEn')"
                  v-model="addEditObj.name.en"
                  :disabled="viewOnly"
                />
                <div
                  class="help-block"
                  v-if="errors.has('addEditValidation.nameEn')"
                >
                  {{ errors.first("addEditValidation.nameEn") }}
                </div>
              </div>
            </div>

              <div class="col-md-4" >
                  <div
                      class="form-group"
                      :class="{ 'has-error': errors.has('addEditValidation.price') }"
                  >
                      <label class="control-label">السعر</label>
                      <input
                          type="text"
                          name="price"
                          class="form-control"
                          v-validate="'required'"
                          data-vv-scope="addEditValidation"
                          :data-vv-as="$t('price')"
                          v-model="addEditObj.price"
                          :disabled="viewOnly"
                      />
                      <div
                          class="help-block"
                          v-if="errors.has('addEditValidation.price')"
                      >
                          {{ errors.first("addEditValidation.price") }}
                      </div>
                  </div>
              </div>

              <div class="col-md-4" >
                  <div
                      class="form-group"
                      :class="{ 'has-error': errors.has('addEditValidation.cost') }"
                  >
                      <label class="control-label">التكلفة</label>
                      <input
                          type="text"
                          name="cost"
                          class="form-control"
                          v-validate="'required'"
                          data-vv-scope="addEditValidation"
                          :data-vv-as="$t('cost')"
                          v-model="addEditObj.cost"
                          :disabled="viewOnly"
                      />
                      <div
                          class="help-block"
                          v-if="errors.has('addEditValidation.cost')"
                      >
                          {{ errors.first("addEditValidation.cost") }}
                      </div>
                  </div>
              </div>


              <div class="col-md-4" >
                  <div
                      class="form-group"
                      :class="{ 'has-error': errors.has('addEditValidation.number_of_meals') }"
                  >
                      <label class="control-label">عدد الوجبات للباقة</label>
                      <input
                          type="text"
                          name="cost"
                          class="form-control"
                          data-vv-scope="addEditValidation"
                          :data-vv-as="$t('عدد الوجبات للباقة')"
                          v-model="addEditObj.number_of_meals"
                          :disabled="viewOnly"
                      />
                      <div
                          class="help-block"
                          v-if="errors.has('addEditValidation.cost')"
                      >
                          {{ errors.first("addEditValidation.cost") }}
                      </div>
                  </div>
              </div>

              <div class="col-md-4" >
                  <div
                      class="form-group"
                      :class="{ 'has-error': errors.has('addEditValidation.number_of_days') }"
                  >
                      <label class="control-label">عدد ايام الباقة</label>
                      <input
                          type="text"
                          name="number_of_days"
                          class="form-control"
                          v-validate="'required'"
                          data-vv-scope="addEditValidation"
                          :data-vv-as="$t('cost')"
                          v-model="addEditObj.number_of_days"
                          :disabled="viewOnly"
                      />
                      <div
                          class="help-block"
                          v-if="errors.has('addEditValidation.number_of_days')"
                      >
                          {{ errors.first("addEditValidation.number_of_days") }}
                      </div>
                  </div>

                  <div class="col-md-12 mt-3">
                      <el-checkbox-group v-model="addEditObj.days">
                          <el-checkbox label="Saturday"></el-checkbox>
                          <el-checkbox label="Sunday"></el-checkbox>
                          <el-checkbox label="Monday"></el-checkbox>
                          <el-checkbox label="Tuesday"></el-checkbox>
                          <el-checkbox label="Wednesday"></el-checkbox>
                          <el-checkbox label="Thursday"></el-checkbox>
                      </el-checkbox-group>
                  </div>
              </div>



          </div>

<!--            // form packages details-->
          <div class="row mr-1">
          <div v-if="!viewOnly" style="margin-right:auto;margin-left:15px;">
              <el-button @click="addNewDetails" type="success">مميزات الباقة</el-button>
          </div>


            <table id="ingredients" class="mt-3">
              <tr>
                <th style="width:5rem">#</th>
                <th style="width:60rem">اسم المجموعة</th>
                <th style="width:15rem">الكمية</th>
                <th v-if="!viewOnly" style="width:60rem">{{$t('Actions')}}</th>
              </tr>
              <tr v-for="(item, index) in addEditObj.details" :key="index">
                <td>{{index+1}}</td>
                <td>

                  <el-select
                    :class="{ 'has-error': errors.has(`addEditValidation.name${index + 1}`) }"
                    v-model="item.id"
                    :name="`name${index + 1}`"
                     v-validate="'required'"
                    :data-vv-as="$t('Name')"
                    data-vv-scope="addEditValidation"
                    :placeholder="$t('Select')"
                    clearable
                    filterable
                    :disabled="viewOnly"
                  >
                    <el-option
                      v-for="option in groupArray"
                      :value="option.id"
                      :label="option.name"
                      :key="option.id"
                    >
                    </el-option>
                </el-select>

                </td>
                <td>
                  <el-input
                    type="text"
                    v-model.number="item.quantity"
                    name="name"
                     v-validate="'required|decimal'"
                    :data-vv-as="$t('Name')"
                    data-vv-scope="addEditValidation"
                     :disabled="viewOnly"
                  />
                  </td>
                <td v-if="!viewOnly"><i @click="removeDetails(index)" class="el-icon-delete"></i></td>
              </tr>
            </table>

          </div>
        </b-card>
        <b-button
        v-if="!viewOnly"
        class="mt-3"
        @click="saveAddEdit"
         variant="primary">
          {{ $t("Save") }}
        </b-button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "packagesAddEdit",
  data() {
    return {
      lang: localStorage.getItem('lang') || 'ar',
      groupArray: [],
      id: this.$route.params.id || 0,
      viewOnly: this.$route.params.page && this.$route.params.page == 'details' ? true : false,
      addEditObj:{
        id:0,
        name: {
          ar: '',
          en: ''
        },
        price: '',
        cost: '',
        number_of_days: '',
        number_of_meals: '',
        days: [],
        details: [],
      },
    };
  },
  methods: {
      addNewDetails() {
      let item = {
        id: '',
        quantity: ''
      };

      this.addEditObj.details.push(item)
    },
    removeDetails(index) {
       this.addEditObj.details.splice(index, 1)
    },

    saveAddEdit() {
      this.$validator.validateAll("addEditValidation").then((result) => {
        if (result) {

            let newDataToSend= JSON.parse(JSON.stringify(this.addEditObj));

            this.daysToSend= [];
            let ourDays= [
                {
                    label: 'Sunday',
                    value:0
                },
                {
                    label: 'Monday',
                    value:1
                },
                {
                    label: 'Tuesday',
                    value:2
                },
                {
                    label: 'Wednesday',
                    value:3
                },
                {
                    label: 'Thursday',
                    value:4
                },
                {
                    label: 'Saturday',
                    value: 6
                }
            ];
            this.addEditObj.days.map(res=>{
                let val= ourDays.find(x=>x.label == res).value;
                this.daysToSend.push(val);
            })


            newDataToSend.days= this.daysToSend;
            newDataToSend.name = this.addEditObj.name;
            newDataToSend.price = this.addEditObj.price;
            newDataToSend.cost = this.addEditObj.cost;
            newDataToSend.number_of_days = this.addEditObj.number_of_days;
            newDataToSend.details = this.addEditObj.details;

            if (this.id) {
              this.$store
                .dispatch("packages/updateData", newDataToSend)
                .then(() => {
                  this.$notify.success({
                      duration: 3000,
                      message: this.$t("UpdatedSuccessfully"),
                      title: this.$t("Update"),
                      customClass: "top-center",
                  });
                })
                .catch((error) => {
                  this.$notify.error({
                      duration: 3000,
                      message: error,
                      title: this.$t("Error"),
                      customClass: "top-center",
                  });
                })
            } else {

              this.$store
                .dispatch("packages/saveData", newDataToSend)
                .then(() => {
                   this.$notify.success({
                      duration: 3000,
                      message: this.$t("SavedSuccessfully"),
                      title: this.$t("Save"),
                      customClass: "top-center",
                  });
                })
                .catch((error) => {
                    this.$notify.error({
                      duration: 3000,
                      message: error,
                      title: this.$t("Error"),
                      customClass: "top-center",
                  });
                })
            }
            this.$router.push({name: 'packages'});
          }
      });
    },
    fillData() {
        let id= this.id
        this.$store
        .dispatch("packages/findData", id)
        .then((res) => {
          this.addEditObj = res.data;
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
    initData() {
      // this.$store.dispatch(`recipies/listGroups`).then(res => {
      //   this.groupArray= res.data;
      // });

        this.$store.dispatch(`new_menus/listGroups`).then(res => {
            this.groupArray = res.data;
        });
    if(this.id) this.fillData();

    },
  },
  created() {
    this.initData();
  },
};
</script>


<style lang="scss">
#ingredients{
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 99%;
    td, th {
        border: 1px solid #ddd;
        padding: 8px;
    }
    th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: right;
        background-color: #099db1;
        color: white;
    }
}
.el-icon-delete {
  cursor: pointer;
}
.el-icon-delete:hover {
  color: rgb(202, 46, 46);
}
</style>
