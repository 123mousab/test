<template>
  <div>

    <!-- راوتر التنقل -->
    <div class="mt-3">
      <label>
        <router-link to="/">الرئيسية</router-link>
      </label>
      <span>/</span>
      <label>
        <router-link to="/subscribe">Subscribes</router-link>
      </label>
      <span>/</span>

      <label active>
        <span v-if="id && !viewOnly">تعديل</span>
        <span v-else-if="viewOnly">التفاصيل</span>
        <span v-else>Add New Subscribe</span>
      </label>
    </div>

    <!-- العنوان الرئيسي -->
    <h4 class="mt-3">
      <span v-if="id && !viewOnly">تعديل</span>
      <span v-else-if="viewOnly">التفاصيل</span>
      <span v-else>اضافة اشتراك جديد</span>
    </h4>


    <div class="mt-3">

      <div ref="form">
        <b-card>
          <div class="row">
              <h4 class="col-md-12" style="background: #099db1;color: #000;font-weight: bold;padding: 11px;">
                  بيانات العميل الاساسية
              </h4>
             <div class="col-md-6" >
              <div
                class="form-group"
                :class="{ 'has-error': errors.has('addEditValidation.CustomerName') }"
              >
                <label class="control-label">اسم المشترك</label>
                <input
                  type="text"
                  name="name"
                  class="form-control"
                  v-validate="'required'"
                  data-vv-scope="addEditValidation"
                  :data-vv-as="'Customer Name'"
                  v-model="addEditObj.customer.name"
                  :disabled="viewOnly"
                />
                <div
                  class="help-block"
                  v-if="errors.has('addEditValidation.CustomerName')"
                >
                  {{ errors.first("addEditValidation.CustomerName") }}
                </div>
              </div>
            </div>

              <div class="col-md-6" >
                  <div
                      class="form-group"
                      :class="{ 'has-error': errors.has('addEditValidation.CustomerEmail') }"
                  >
                      <label class="control-label">البريد الالكتروني</label>
                      <input
                          type="email"
                          name="email"
                          class="form-control"
                          data-vv-scope="addEditValidation"
                          :data-vv-as="'CustomerEmail'"
                          v-model="addEditObj.customer.email"
                          :disabled="viewOnly"
                      />
                  </div>
              </div>

              <div class="col-md-6" >
                  <div
                      class="form-group"
                      :class="{ 'has-error': errors.has('addEditValidation.CustomerMobile') }"
                  >
                      <label class="control-label">الموبايل</label>
                      <input
                          type="text"
                          name="mobile"
                          class="form-control"
                          v-validate="'required'"
                          data-vv-scope="addEditValidation"
                          :data-vv-as="'CustomerMobile'"
                          v-model="addEditObj.customer.mobile"
                          :disabled="viewOnly"
                      />
                      <div
                          class="help-block"
                          v-if="errors.has('addEditValidation.CustomerMobile')"
                      >
                          {{ errors.first("addEditValidation.CustomerMobile") }}
                      </div>
                  </div>
              </div>

              <div class="col-md-6" >
                  <div
                      class="form-group"
                      :class="{ 'has-error': errors.has('addEditValidation.CustomerBirthDate') }"
                  >
                      <label class="control-label">تاريخ الميلاد</label>
                      <input
                          type="date"
                          name="birthdate"
                          class="form-control"
                          data-vv-scope="addEditValidation"
                          :data-vv-as="'CustomerBirthDate'"
                          v-model="addEditObj.customer.birth_date"
                          :disabled="viewOnly"
                      />
                      <div
                          class="help-block"
                          v-if="errors.has('addEditValidation.CustomerBirthDate')"
                      >
                          {{ errors.first("addEditValidation.CustomerBirthDate") }}
                      </div>
                  </div>
              </div>
          </div>
        </b-card>
          <b-card>
              <div class="row">
                  <h4 class="col-md-12" style="background: #099db1;color: #000;font-weight: bold;padding: 11px;">
                      اضافة بيانات القياس
                  </h4>
                  <div class="col-md-6" >
                      <div
                          class="form-group"
                          :class="{ 'has-error': errors.has('addEditValidation.height') }"
                      >
                          <label class="control-label">الطول</label>
                          <input
                              type="text"
                              name="height"
                              class="form-control"
                              data-vv-scope="addEditValidation"
                              :data-vv-as="'height'"
                              v-model="addEditObj.measurement.height"
                              :disabled="viewOnly"
                          />
                      </div>
                  </div>

                  <div class="col-md-6" >
                      <div
                          class="form-group"
                          :class="{ 'has-error': errors.has('addEditValidation.weight') }"
                      >
                          <label class="control-label">الوزن</label>
                          <input
                              type="text"
                              name="weight"
                              class="form-control"
                              data-vv-scope="addEditValidation"
                              :data-vv-as="'weight'"
                              v-model="addEditObj.measurement.weight"
                              :disabled="viewOnly"
                          />
                      </div>
                  </div>

                  <div class="col-md-6" >
                      <div
                          class="form-group"
                          :class="{ 'has-error': errors.has('addEditValidation.muscle') }"
                      >
                          <label class="control-label">العضلات</label>
                          <input
                              type="text"
                              name="muscle"
                              class="form-control"
                              data-vv-scope="addEditValidation"
                              :data-vv-as="$t('muscle')"
                              v-model="addEditObj.measurement.muscle"
                              :disabled="viewOnly"
                          />
                      </div>
                  </div>

                  <div class="col-md-6" >
                      <div
                          class="form-group"
                          :class="{ 'has-error': errors.has('addEditValidation.fluid') }"
                      >
                          <label class="control-label">السوائل</label>
                          <input
                              type="text"
                              name="fluid"
                              class="form-control"
                              data-vv-scope="addEditValidation"
                              :data-vv-as="'fluid'"
                              v-model="addEditObj.measurement.fluid"
                              :disabled="viewOnly"
                          />
                      </div>
                  </div>

                  <div class="col-md-6" >
                      <div
                          class="form-group"
                          :class="{ 'has-error': errors.has('addEditValidation.target') }"
                      >
                          <label class="control-label">الهدف</label>
                          <el-select
                              v-model="addEditObj.measurement.target"
                              name="target"
                              :data-vv-as="'Target'"
                              data-vv-scope="addEditValidation"
                              :placeholder="'Select'"
                              clearable
                              filterable
                              :disabled="viewOnly"
                          >
                              <el-option
                                  v-for="option in targetArray"
                                  :value="option.id"
                                  :label="option.name"
                                  :key="option.id"
                              >
                              </el-option>
                          </el-select>
                      </div>
                  </div>
              </div>
          </b-card>

          <b-card>
              <div class="row">
                  <h4 class="col-md-12" style="background: #099db1;color: #000;font-weight: bold;padding: 11px;">
                      البيانات المالية
                  </h4>
                  <div class="col-md-6" >
                      <div
                          class="form-group"
                          :class="{ 'has-error': errors.has('addEditValidation.bank_name') }"
                      >
                          <label class="control-label">البنك</label>
                          <el-select
                              v-model="addEditObj.bank.bank_name_id"
                              v-validate="'required'"
                              name="bank_name_id"
                              :data-vv-as="'البنك'"
                              data-vv-scope="addEditValidation"
                              :placeholder="'Select'"
                              clearable
                              filterable
                              :disabled="viewOnly"
                          >
                              <el-option
                                  v-for="option in bankNamesArray"
                                  :value="option.id"
                                  :label="option.name"
                                  :key="option.id"
                              >
                              </el-option>
                          </el-select>
                          <div
                              class="help-block"
                              v-if="errors.has('addEditValidation.bank_name')"
                          >
                              {{ errors.first("addEditValidation.period") }}
                          </div>
                      </div>
                  </div>


                  <div class="col-md-6" >
                      <div
                          class="form-group"
                          :class="{ 'has-error': errors.has('addEditValidation.number_money_transfer') }"
                      >
                          <label class="control-label">الرقم المرجعي</label>
                          <input
                              type="text"
                              name="number_money_transfer"
                              class="form-control"
                              data-vv-scope="addEditValidation"
                              :data-vv-as="'الرقم المرجعي'"
                              v-model="addEditObj.bank.number_money_transfer"
                              :disabled="viewOnly"
                          />
                      </div>
                  </div>

                  <div class="col-md-6" >
                      <div
                          class="form-group"
                          :class="{ 'has-error': errors.has('addEditValidation.transfer_Date') }"
                      >
                          <label class="control-label">تاريخ التحويل</label>
                          <input
                              type="date"
                              name="transfer_date"
                              class="form-control"
                              data-vv-scope="addEditValidation"
                              :data-vv-as="'Transfer Date'"
                              v-model="addEditObj.bank.transfer_date"
                              :disabled="viewOnly"
                          />
                      </div>
                  </div>

                  <div class="col-md-6" >
                      <div
                          class="form-group"
                          :class="{ 'has-error': errors.has('addEditValidation.amount') }"
                      >
                          <label class="control-label">المبلغ المراد تحويله</label>
                          <input
                              type="text"
                              name="amount"
                              class="form-control"
                              data-vv-scope="addEditValidation"
                              :data-vv-as="'amount'"
                              v-model="addEditObj.bank.amount"
                              :disabled="viewOnly"
                          />
                      </div>
                  </div>
              </div>
          </b-card>

          <b-card>
              <div class="row">
                  <h4 class="col-md-12" style="background: #099db1;color: #000;font-weight: bold;padding: 11px;">
                      الرغبات الشخصية
                  </h4>

                  <div class="col-md-6" >
                      <div
                          class="form-group"
                          :class="{ 'has-error': errors.has('addEditValidation.ingredients') }"
                      >
                          <label class="control-label">المكونات الاساسية</label>
                          <el-select
                              v-model="addEditObj.personal_desires.ingredients"
                              name="ingredients"
                              :data-vv-as="'Ingredients'"
                              data-vv-scope="addEditValidation"
                              :placeholder="'Select'"
                              clearable
                              filterable
                              multiple
                              :disabled="viewOnly"
                          >
                              <el-option
                                  v-for="option in ingredientFirstArray"
                                  :value="option.id"
                                  :label="option.name"
                                  :key="option.id"
                              >
                              </el-option>
                          </el-select>
                      </div>
                  </div>

                  <div class="col-md-6" >
                      <div
                          class="form-group"
                          :class="{ 'has-error': errors.has('addEditValidation.ingredients') }"
                      >
                          <label class="control-label">المكونات الغير اساسية</label>
                          <el-select
                              v-model="addEditObj.personal_desires.not_ingredients"
                              name="not_ingredients"
                              :data-vv-as="'not_ingredients'"
                              data-vv-scope="addEditValidation"
                              :placeholder="'Select'"
                              clearable
                              filterable
                              multiple
                              :disabled="viewOnly"
                          >
                              <el-option
                                  v-for="option in ingredientSecondArray"
                                  :value="option.id"
                                  :label="option.name"
                                  :key="option.id"
                              >
                              </el-option>
                          </el-select>
                      </div>
                  </div>

                  <div class="col-md-6" >
                      <div
                          class="form-group"
                          :class="{ 'has-error': errors.has('addEditValidation.recipies') }"
                      >
                          <label class="control-label">التفضيلات من المميزات</label>
                          <el-select
                              v-model="addEditObj.personal_desires.recipies"
                              name="recipies"
                              :data-vv-as="'Recipies'"
                              data-vv-scope="addEditValidation"
                              :placeholder="'Select'"
                              clearable
                              filterable
                              multiple
                              :disabled="viewOnly"
                          >
                              <el-option
                                  v-for="option in groupRecipieArray"
                                  :value="option.id"
                                  :label="option.name"
                                  :key="option.id"
                              >
                              </el-option>
                          </el-select>
                      </div>
                  </div>

                  <div class="col-md-6" >
                      <div
                          class="form-group"
                          :class="{ 'has-error': errors.has('addEditValidation.notes') }"
                      >
                          <label class="control-label">ملاحظات</label>
                          <textarea
                              rows="4"
                              type="text"
                              name="notes"
                              class="form-control"
                              v-validate="''"
                              data-vv-scope="addEditValidation"
                              :data-vv-as="'Notes'"
                              v-model="addEditObj.personal_desires.notes"
                              :disabled="viewOnly"
                          />
                          <div
                              class="help-block"
                              v-if="errors.has('addEditValidation.notes')"
                          >
                              {{ errors.first("addEditValidation.notes") }}
                          </div>
                      </div>
                  </div>

                  <div class="col-md-6" >
                      <div
                          class="form-group"
                          :class="{ 'has-error': errors.has('addEditValidation.carbohydrates') }"
                      >
                          <label class="control-label">الكربوهيدرات</label>
                          <input
                              type="text"
                              name="carbohydrates"
                              class="form-control"
                              v-validate="'required'"
                              data-vv-scope="addEditValidation"
                              :data-vv-as="'carbohydrates'"
                              v-model="addEditObj.personal_desires.carbohydrates"
                              :disabled="viewOnly"
                          />
                          <div
                              class="help-block"
                              v-if="errors.has('addEditValidation.carbohydrates')"
                          >
                              {{ errors.first("addEditValidation.carbohydrates") }}
                          </div>
                      </div>
                  </div>

                  <div class="col-md-6" >
                      <div
                          class="form-group"
                          :class="{ 'has-error': errors.has('addEditValidation.protein') }"
                      >
                          <label class="control-label">البروتين</label>
                          <input
                              type="text"
                              name="protein"
                              class="form-control"
                              v-validate="'required'"
                              data-vv-scope="addEditValidation"
                              :data-vv-as="'protein'"
                              v-model="addEditObj.personal_desires.protein"
                              :disabled="viewOnly"
                          />
                          <div
                              class="help-block"
                              v-if="errors.has('addEditValidation.protein')"
                          >
                              {{ errors.first("addEditValidation.protein") }}
                          </div>
                      </div>
                  </div>

              </div>
          </b-card>
          <b-card>
              <div class="row">
                  <h4 class="col-md-12" style="background: #099db1;color: #000;font-weight: bold;padding: 11px;">
                      اضافة بيانات التوصيل
                  </h4>
                  <div class="col-md-6" >
                      <div
                          class="form-group"
                          :class="{ 'has-error': errors.has('addEditValidation.city') }"
                      >
                          <label class="control-label">المدينة</label>
                          <el-select
                              v-model="addEditObj.delivery.city_id"
                              v-validate="'required'"
                              name="city"
                              @change="changeCity(addEditObj.delivery.city_id)"
                              :data-vv-as="'City'"
                              data-vv-scope="addEditValidation"
                              :placeholder="'Select'"
                              clearable
                              filterable
                              :disabled="viewOnly"
                          >
                              <el-option
                                  v-for="option in citiesArray"
                                  :value="option.id"
                                  :label="option.name"
                                  :key="option.id"
                              >
                              </el-option>
                          </el-select>
                          <div
                              class="help-block"
                              v-if="errors.has('addEditValidation.city')"
                          >
                              {{ errors.first("addEditValidation.city") }}
                          </div>
                      </div>
                  </div>

                  <div class="col-md-6" >
                      <div
                          class="form-group"
                          :class="{ 'has-error': errors.has('addEditValidation.branch') }"
                      >
                          <label class="control-label">الحي</label>
                          <el-select
                              v-model="addEditObj.delivery.branch_id"
                              v-validate="'required'"
                              name="branch"
                              :data-vv-as="'Branch'"
                              data-vv-scope="addEditValidation"
                              :placeholder="'Select'"
                              clearable
                              filterable
                              :disabled="viewOnly"
                          >
                              <el-option
                                  v-for="option in branchesArray"
                                  :value="option.id"
                                  :label="option.name"
                                  :key="option.id"
                              >
                              </el-option>
                          </el-select>
                          <div
                              class="help-block"
                              v-if="errors.has('addEditValidation.branch')"
                          >
                              {{ errors.first("addEditValidation.branch") }}
                          </div>
                      </div>
                  </div>

                  <div class="col-md-6" >
                      <div
                          class="form-group"
                          :class="{ 'has-error': errors.has('addEditValidation.period') }"
                      >
                          <label class="control-label">الفترة</label>
                          <el-select
                              v-model="addEditObj.delivery.period"
                              v-validate="'required'"
                              name="period"
                              :data-vv-as="'Period'"
                              data-vv-scope="addEditValidation"
                              :placeholder="'Select'"
                              clearable
                              filterable
                              :disabled="viewOnly"
                          >
                              <el-option
                                  v-for="option in periodArray"
                                  :value="option.id"
                                  :label="option.name"
                                  :key="option.id"
                              >
                              </el-option>
                          </el-select>
                          <div
                              class="help-block"
                              v-if="errors.has('addEditValidation.period')"
                          >
                              {{ errors.first("addEditValidation.period") }}
                          </div>
                      </div>
                  </div>

                  <div class="col-md-6" >
                      <div
                          class="form-group"
                          :class="{ 'has-error': errors.has('addEditValidation.home_number') }"
                      >
                          <label class="control-label">رقم المنزل</label>
                          <input
                              type="text"
                              name="home_number"
                              class="form-control"
                              data-vv-scope="addEditValidation"
                              :data-vv-as="'home_number'"
                              v-model="addEditObj.delivery.home_number"
                              :disabled="viewOnly"
                          />
                      </div>
                  </div>

                  <div class="col-md-6" >
                      <div
                          class="form-group"
                          :class="{ 'has-error': errors.has('addEditValidation.company') }"
                      >
                          <label class="control-label">الشركة</label>
                          <el-select
                              v-model="addEditObj.delivery.company_id"
                              name="period"
                              :data-vv-as="'company'"
                              data-vv-scope="addEditValidation"
                              :placeholder="'Select'"
                              clearable
                              filterable
                              :disabled="viewOnly"
                          >
                              <el-option
                                  v-for="option in companyArray"
                                  :value="option.id"
                                  :label="option.name"
                                  :key="option.id"
                              >
                              </el-option>
                          </el-select>
                      </div>
                  </div>

                  <div class="col-md-6" >
                      <div
                          class="form-group"
                          :class="{ 'has-error': errors.has('addEditValidation.GroupName') }"
                      >
                          <label class="control-label">مجموعة الاشخاص</label>
                          <el-select
                              v-model="addEditObj.delivery.group_name_id"
                              name="period"
                              :data-vv-as="'GroupName'"
                              data-vv-scope="addEditValidation"
                              :placeholder="'Select'"
                              clearable
                              filterable
                              :disabled="viewOnly"
                          >
                              <el-option
                                  v-for="option in groupNameArray"
                                  :value="option.id"
                                  :label="option.name"
                                  :key="option.id"
                              >
                              </el-option>
                          </el-select>
                      </div>
                  </div>

                  <div class="col-md-6" >
                      <div
                          class="form-group"
                          :class="{ 'has-error': errors.has('addEditValidation.address') }"
                      >
                          <label class="control-label">العنوان</label>
                          <input
                              type="text"
                              name="address"
                              class="form-control"
                              data-vv-scope="addEditValidation"
                              :data-vv-as="'Address'"
                              v-model="addEditObj.delivery.address"
                              :disabled="viewOnly"
                          />
                      </div>
                  </div>

                  <div class="col-md-6" >
                      <div
                          class="form-group"
                          :class="{ 'has-error': errors.has('addEditValidation.notes') }"
                      >
                          <label class="control-label">ملاحظات</label>
                          <textarea
                              rows="4"
                              type="text"
                              name="notes"
                              class="form-control"
                              data-vv-scope="addEditValidation"
                              :data-vv-as="'Notes'"
                              v-model="addEditObj.delivery.notes"
                              :disabled="viewOnly"
                          />
                      </div>
                  </div>
              </div>
          </b-card>
          <b-card>
              <div class="row">
                  <h4 class="col-md-12" style="background: #099db1;color: #000;font-weight: bold;padding: 11px;">
                      بيانات الاشتراك
                  </h4>

                  <div class="col-md-6" >
                      <div
                          class="form-group"
                          :class="{ 'has-error': errors.has('addEditValidation.package') }"
                      >
                          <label class="control-label">الباقة</label>
                          <el-select
                              v-model="addEditObj.subscribe.package_id"
                              v-validate="'required'"
                              name="package"
                              @change="packageDetails(addEditObj.subscribe.package_id)"
                              :data-vv-as="'Package'"
                              data-vv-scope="addEditValidation"
                              :placeholder="'Select'"
                              clearable
                              filterable
                              :disabled="viewOnly"
                          >
                              <el-option
                                  v-for="option in packagesArray"
                                  :value="option.id"
                                  :label="option.name"
                                  :key="option.id"
                              >
                              </el-option>
                          </el-select>
                          <div
                              class="help-block"
                              v-if="errors.has('addEditValidation.package')"
                          >
                              {{ errors.first("addEditValidation.package") }}
                          </div>
                      </div>
                  </div>

                  <template v-if="package_details">
                      <div class="col-md-6" >
                          <label class="control-label">السعر</label>
                          <input
                              type="text"
                              name="price"
                              class="form-control"
                              v-model="package_details.price"
                              disabled
                          />
                      </div>
                      <div class="col-md-6" >
                          <label class="control-label">عدد ايام الباقة</label>
                          <input
                              type="text"
                              name="number_of_days"
                              class="form-control"
                              v-model="package_details.number_of_days"
                              disabled
                          />
                      </div>

                      <div class="col-md-6" >
                          <label class="control-label">الخصم</label>
                          <input
                              type="text"
                              name="number_of_days"
                              class="form-control"
                              v-model.number="package_details.discount"
                          />
                      </div>

                      <div class="col-md-6" >
                              <label class="control-label">تاريخ بداية الاشتراك</label>
                              <input
                                  type="date"
                                  name="start_date"
                                  class="form-control"
                                  v-validate="'required'"
                                  data-vv-scope="addEditValidation"
                                  :data-vv-as="'StartDate'"
                                  v-model="addEditObj.subscribe.start_date"
                                  :disabled="viewOnly"
                              />
                          </div>
                      <div class="col-md-6" >
                          <label class="control-label">الاجمالي</label>
                          <input
                              type="text"
                              name="total"
                              class="form-control"
                              v-model.number="calcTotal"
                              disabled
                          />
                      </div>

                      <div class="row mr-1">
                          <div v-if="!viewOnly" style="margin-right:auto;margin-left:15px;">
                              <el-button @click="addNewDetails" type="success">ميزات الاشتراك</el-button>
                          </div>


                          <table id="ingredients" class="mt-3">
                              <tr>
                                  <th style="width:5rem">#</th>
                                  <th style="width:60rem">اسم المجموعة</th>
                                  <th style="width:15rem">الكمية</th>
                                  <th v-if="!viewOnly" style="width:60rem">{{$t('Actions')}}</th>
                              </tr>
                              <tr v-for="(item, index) in addEditObj.group_subscribe" :key="index">
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

<!--                      <div class="col-md-12 mt-3">
                          <table>
                              <tr>
                                  <th>Groups</th>
                                  <th>Quantity</th>
                              </tr>
                              <tr v-for="(item, index) in package_details.details" :key="index">
                                  <td>{{item.group_name}}</td>
                                  <td>
                                      <el-input
                                          type="text"
                                          v-model.number="addEditObj.subscribe.quantities[index]"
                                          name="name"
                                          v-validate="'required|decimal'"
                                          :data-vv-as="$t('Quantity')"
                                          data-vv-scope="addEditValidation"
                                          :disabled="viewOnly"
                                      />
                                  </td>
                              </tr>
                          </table>
                      </div>-->
                      <div class="col-md-12 mt-3">
                          <el-checkbox-group v-model="addEditObj.subscribe.days">
                              <el-checkbox label="6">Saturday</el-checkbox>
                              <el-checkbox label="0">Sunday</el-checkbox>
                              <el-checkbox label="1">Monday</el-checkbox>
                              <el-checkbox label="2">Tuesday</el-checkbox>
                              <el-checkbox label="3">Wedensday</el-checkbox>
                              <el-checkbox label="4">Thursday</el-checkbox>
                          </el-checkbox-group>
                      </div>
                  </template>
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
  name: "recipiesAddEdit",
  data() {
    return {
      lang: localStorage.getItem('lang') || 'ar',
        daysToSend: [],
        targetArray: [
            {
                "id": 0,
                "name": "تضخيم"
            },
            {
                "id": 1,
                "name": "تنقيص وزن"
            },
            {
                "id": 2,
                "name": "تنشيف"
            },
        ],
        groupArray: [],
        groupRecipieArray: [],
        periodArray: [],
        companyArray: [],
        groupNameArray: [],
        bankNamesArray: [],
        nutritionFactsArray: [],
        ingredientsArray: [],
        recipiesArray: [],
        citiesArray: [],
        branchesArray: [],
        driversArray: [],
        packagesArray: [],
        ingredientFirstArray: [],
        ingredientSecondArray: [],
        package_details: '',
      id: this.$route.params.id || 0,
      viewOnly: this.$route.params.page && this.$route.params.page == 'details' ? true : false,
      addEditObj:{
        id:0,
          customer: {
              name: '',
              mobile: '',
              email: '',
              birth_date: ''
        },
          measurement: {
              height: '',
              weight: '',
              muscle: '',
              fluid: '',
              target: ''
          },
          bank: {
              bank_name_id: '',
              transfer_date: '',
              number_money_transfer: '',
              amount: ''
          },
          personal_desires: {
              nutrition_facts: '',
              ingredients: '',
              not_ingredients: '',
              recipies: '',
              protein: '',
              carbohydrates: ''
          },
          delivery: {
            city_id: "",
              branch_id: "",
              driver_id: "",
              home_address: "",
              period: "",
              home_number: "",
              company_id: "",
              group_name_id: "",
              company: "",
              group: "",
              address: "",
              notes: "",
          },
          group_subscribe: [],
          subscribe: {
              package_id: '',
              discount: '',
              days: [],
              quantities: [],
              start_date: ''
          }
      },
    };
  },
  methods: {
      addNewDetails() {
          let item = {
              id: '',
              quantity: ''
          };

          this.addEditObj.group_subscribe.push(item)
      },
      removeDetails(index) {
          this.addEditObj.group_subscribe.splice(index, 1)
      },
      packageDetails(id)
      {
          this.package_details = "";
          this.$store.dispatch(`subscribe/packageDetails`, id).then(res => {
              res.data.discount = 0;
              // res.data.days=[];
              this.package_details= res.data;
              this.addEditObj.group_subscribe = this.package_details.group_subscribe;
              this.addEditObj.subscribe.days = this.package_details.days;
          });
      },
      changeCity(id){
          this.addEditObj.delivery.branch_id = "",
              this.$validator.reset();
          this.$store.dispatch(`branches/listBranches`, id).then(res => {
              this.branchesArray= res.data;
          });
      },
    saveAddEdit() {
      this.$validator.validateAll("addEditValidation").then((result) => {
        if (result) {
            if (this.id) {
              this.$store
                .dispatch("subscribe/updateData", this.addEditObj)
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
                let newDataToSend= JSON.parse(JSON.stringify(this.addEditObj));
                newDataToSend.subscribe.package_id= this.addEditObj.subscribe.package_id;
                newDataToSend.subscribe.start_date= this.addEditObj.subscribe.start_date;
                newDataToSend.group_subscribe= this.addEditObj.group_subscribe;
                newDataToSend.subscribe.total= this.calcTotal;

                this.daysToSend= [];
                /*let ourDays= [
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
                ];*/
               /* this.package_details.days.map(res=>{
                    let val=  ourDays.find(x=>x.label == res)?ourDays.find(x=>x.label == res).value: null;
                    if(val) this.daysToSend.push(val);
                })*/
                newDataToSend.subscribe.days= this.package_details.days;
                // newDataToSend.subscribe.start_date= "2021-07-14";

              this.$store
                .dispatch("subscribe/saveData", newDataToSend)
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
            this.$router.push({name: 'subscribe'});
          }
      });
    },
    fillData() {
        let id= this.id
        this.$store
        .dispatch("subscribe/findData", id)
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
        this.$store.dispatch(`recipies/listGroups`).then(res => {
            this.groupArray= res.data;
        });
      this.$store.dispatch(`recipies/listIngredients`).then(res => {
        this.ingredientsArray= res.data;
      });
        this.$store.dispatch(`kitchens/listRecipies`).then(res => {
            this.recipiesArray = res.data;
        });

        this.$store.dispatch(`kitchens/listRecipiesOfGroups`).then(res => {
            this.groupRecipieArray = res.data;
        });

        this.$store.dispatch(`ingredients/listNutrionFacts`).then(res => {
            this.nutritionFactsArray= res.data;
        });
        this.$store.dispatch(`branches/listCities`, 2).then(res => {
            this.citiesArray= res.data;
        });
        this.$store.dispatch(`drivers/listDrivers`).then(res => {
            this.driversArray= res.data;
        });
        this.$store.dispatch(`subscribe/listPeriods`).then(res => {
            this.periodArray= res.data;
        });
        this.$store.dispatch(`subscribe/listBankNames`).then(res => {
            this.bankNamesArray= res.data;
        });
        this.$store.dispatch(`subscribe/listCompanies`).then(res => {
            this.companyArray= res.data;
        });
        this.$store.dispatch(`subscribe/listGroupNames`).then(res => {
            this.groupNameArray= res.data;
        });
        this.$store.dispatch(`packages/listPackages`).then(res => {
            this.packagesArray= res.data;
        });
        this.$store.dispatch(`subscribe/listFirstGroupIngredients`).then(res => {
            this.ingredientFirstArray = res.data;
        });

        this.$store.dispatch(`subscribe/listSecondGroupIngredients`).then(res => {
            this.ingredientSecondArray = res.data;
        });

    if(this.id) this.fillData();

    },
  },
    computed: {
        calcTotal()
        {
            if (this.package_details && this.package_details.price)
            return this.package_details.price - this.package_details.discount;
        }
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
table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

td, th {
    border: 1px solid #dddddd;
    text-align: right;
    padding: 8px;
}

tr:nth-child(even) {
    background-color: #dddddd;
}
</style>
