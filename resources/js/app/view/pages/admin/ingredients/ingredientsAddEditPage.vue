<template>
  <div>

    <!-- راوتر التنقل -->
    <div class="mt-3">
      <label>
        <router-link to="/">{{ $t("Home") }}</router-link>
      </label>
      <span>/</span>
      <label>
        <router-link to="/recipies">{{ $t("Ingredients") }}</router-link>
      </label>
      <span>/</span>

      <label active>
        <span v-if="id && !viewOnly">{{ $t("Edit") }}</span>
        <span v-else-if="viewOnly">{{ $t("Details") }}</span>
        <span v-else>{{ $t("AddNewIngredients") }}</span>
      </label>
    </div>

    <!-- العنوان الرئيسي -->
    <h4 class="mt-3">
      <span v-if="id && !viewOnly">{{ $t("Edit") }}</span>
      <span v-else-if="viewOnly">{{ $t("Details") }}</span>
      <span v-else>{{ $t("AddNewIngredients") }}</span>
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


            <div class="col-md-6">
              <div
                class="form-group"
                :class="{ 'has-error': errors.has('addEditValidation.descriptionAr') }"
              >
                <label class="control-label">{{ $t("DescriptionAr") }}</label>
                <textarea
                  rows="4"
                  type="text"
                  name="descriptionAr"
                  class="form-control"
                  v-validate="''"
                  data-vv-scope="addEditValidation"
                  :data-vv-as="$t('DescriptionAr')"
                  v-model="addEditObj.description.ar"
                  :disabled="viewOnly"
                />
                <div
                  class="help-block"
                  v-if="errors.has('addEditValidation.descriptionAr')"
                >
                  {{ errors.first("addEditValidation.descriptionAr") }}
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div
                class="form-group"
                :class="{ 'has-error': errors.has('addEditValidation.descriptionEn') }"
              >
                <label class="control-label">{{ $t("DescriptionEn") }}</label>
                <textarea
                  rows="4"
                  type="text"
                  name="descriptionEn"
                  class="form-control"
                  v-validate="''"
                  data-vv-scope="addEditValidation"
                  :data-vv-as="$t('DescriptionEn')"
                  v-model="addEditObj.description.en"
                  :disabled="viewOnly"
                />
                <div
                  class="help-block"
                  v-if="errors.has('addEditValidation.descriptionEn')"
                >
                  {{ errors.first("addEditValidation.descriptionEn") }}
                </div>
              </div>
            </div>


            <div class="col-md-4">
              <div
                class="form-group"
                :class="{ 'has-error': errors.has('addEditValidation.unit_id') }"
              >
                <label class="control-label">الوحدة</label>
                 <el-select
                  v-model="addEditObj.unit_id"
                  v-validate=""
                  name="unit_id"
                  :data-vv-as="$t('Unit')"
                  data-vv-scope="addEditValidation"
                  :placeholder="$t('Select')"
                  clearable
                  filterable
                  :disabled="viewOnly"
                >
                  <el-option
                    v-for="option in unitArray"
                    :value="option.id"
                    :label="option.name"
                    :key="option.id"
                  >
                  </el-option>
                </el-select>
                <div
                  class="help-block"
                  v-if="errors.has('addEditValidation.unit_id')"
                >
                  {{ errors.first("addEditValidation.unit_id") }}
                </div>
              </div>
            </div>

              <div class="col-md-4">
                  <div
                      class="form-group"
                      :class="{ 'has-error': errors.has('addEditValidation.division_id') }"
                  >
                      <label class="control-label">القسم</label>
                      <el-select
                          v-model="addEditObj.division_id"
                          v-validate="'required'"
                          name="division_id"
                          :data-vv-as="$t('Division')"
                          data-vv-scope="addEditValidation"
                          :placeholder="$t('Select')"
                          clearable
                          filterable
                          :disabled="viewOnly"
                      >
                          <el-option
                              v-for="option in divisionArray"
                              :value="option.id"
                              :label="option.name"
                              :key="option.id"
                          >
                          </el-option>
                      </el-select>
                      <div
                          class="help-block"
                          v-if="errors.has('addEditValidation.division_id')"
                      >
                          {{ errors.first("addEditValidation.division_id") }}
                      </div>
                  </div>
              </div>

              <div class="col-md-4">
                  <div
                      class="form-group"
                      :class="{ 'has-error': errors.has('addEditValidation.cost') }"
                  >
                      <label class="control-label">التكلفة</label>
                      <el-input
                          type="text"
                          v-model="addEditObj.cost"
                          name="cost"
                          v-validate=""
                          :data-vv-as="$t('Cost')"
                          data-vv-scope="addEditValidation"
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
          </div>


<!--            -->
            <div class="col-md-4">
                <div
                    class="form-group"
                    :class="{ 'has-error': errors.has('addEditValidation.main') }"
                >
<!--                    <label class="control-label">اساسي</label>-->
                    <b-form-checkbox
                        id="checkbox-1"
                        v-model="addEditObj.main"
                        name="main"
                        value="1"
                        unchecked-value="0"
                    >
                        اساسي
                    </b-form-checkbox>
                    <div
                        class="help-block"
                        v-if="errors.has('addEditValidation.main')"
                    >
                        {{ errors.first("addEditValidation.main") }}
                    </div>
                </div>
            </div>

          <div class="row mr-1">
          <div v-if="!viewOnly" style="margin-right:auto;margin-left:15px;">
              <el-button @click="addNewNutriotionFact" type="success">اضافة الحقائق الغذائية</el-button>
          </div>


            <table id="nutriotion_facts" class="mt-3">
              <tr>
                <th style="width:5rem">#</th>
                <th style="width:60rem">الحقيقة الغذائية</th>
                <th style="width:15rem">{الوحدة</th>
                <th style="width:15rem">القيمة</th>
                <th v-if="!viewOnly" style="width:60rem">{{$t('Actions')}}</th>
              </tr>
              <tr v-for="(item, index) in addEditObj.nutriotion_fact" :key="index">
                <td>{{index+1}}</td>
                <td>

                  <el-select
                    :class="{ 'has-error': errors.has(`addEditValidation.nutriotion_fact_id${index + 1}`) }"
                    v-model="item.nutriotion_fact_id"
                    :name="`nutriotion_fact_id${index + 1}`"
                     v-validate="'required'"
                    :data-vv-as="$t('NutriotionFact')"
                    data-vv-scope="addEditValidation"
                    :placeholder="$t('Select')"
                    clearable
                    filterable
                    :disabled="viewOnly"
                  >
                    <el-option
                      v-for="option in nutriotionFactArray"
                      :value="option.id"
                      :label="option.name"
                      :key="option.id"
                    >
                    </el-option>
                </el-select>

                </td>
                  <td>

                      <el-select
                          :class="{ 'has-error': errors.has(`addEditValidation.unit_ids${index + 1}`) }"
                          v-model="item.unit_ids"
                          :name="`unit_ids${index + 1}`"
                          v-validate="'required'"
                          :data-vv-as="$t('Unit')"
                          data-vv-scope="addEditValidation"
                          :placeholder="$t('Select')"
                          clearable
                          filterable
                          :disabled="viewOnly"
                      >
                          <el-option
                              v-for="option in unitArray"
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
                    v-model.number="item.value"
                    name="value"
                     v-validate="'required|decimal'"
                    :data-vv-as="$t('Value')"
                    data-vv-scope="addEditValidation"
                     :disabled="viewOnly"
                  />
                  </td>
                <td v-if="!viewOnly"><i @click="removeNutriotionFact(index)" class="el-icon-delete"></i></td>
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
  name: "ingredientsAddEdit",
  data() {
    return {
      lang: localStorage.getItem('lang') || 'ar',
      divisionArray: [],
      unitArray: [],
      unitsArray: [],
      nutriotionFactArray: [],

      id: this.$route.params.id || 0,
      viewOnly: this.$route.params.page && this.$route.params.page == 'details' ? true : false,
      addEditObj:{
        id:0,
        name: {
          ar: '',
          en: ''
        },
        description: {
          ar: '',
          en: ''
        },
        division_id: '',
        cost: '',
        unit_id: '',
        main: 0,
        nutriotion_fact: [],
      },
    };
  },
  methods: {
    addNewNutriotionFact() {
      let item = {
          nutriotion_fact_id: '',
          unit_ids: '',
          value: ''
      };

      this.addEditObj.nutriotion_fact.push(item)
    },
    removeNutriotionFact(index) {
       this.addEditObj.nutriotion_fact.splice(index, 1)
    },

    saveAddEdit() {
      this.$validator.validateAll("addEditValidation").then((result) => {
        if (result) {
          console.log(this.addEditObj);
            if (this.id) {
              this.$store
                .dispatch("ingredients/updateData", this.addEditObj)
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
                .dispatch("ingredients/saveData", this.addEditObj)
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
            this.$router.push({name: 'ingredients'});
          }
      });
    },
    fillData() {
        let id= this.id
        this.$store
        .dispatch("ingredients/findData", id)
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
      this.$store.dispatch(`ingredients/listUnits`).then(res => {
        this.unitArray= res.data;
      });

        this.$store.dispatch(`ingredients/listUnits`).then(res => {
            this.unitsArray= res.data;
        });

      this.$store.dispatch(`ingredients/listNutrionFacts`).then(res => {
        this.nutriotionFactArray= res.data;
      });

      this.$store.dispatch(`ingredients/listDivisions`).then(res => {
        this.divisionArray= res.data;
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
