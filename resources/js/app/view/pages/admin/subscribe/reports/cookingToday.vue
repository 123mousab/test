<template>
    <div class="reportPage">
        <h2 class="mt-4">جدول الطبخ اليومي</h2>
        <div class="col-md-4 customDate">
            <div
                class="form-group"
                :class="{ 'has-error': errors.has('addEditValidation.cooking_date') }"
            >
                <label class="control-label">تارخ الطبخ</label>
                <input
                    type="date"
                    name="cooking_date"
                    class="form-control"
                    v-validate="'required'"
                    data-vv-scope="addEditValidation"
                    :data-vv-as="$t('Cooking Date')"
                    v-model="addEditObj.date"
                />
                <div
                    class="help-block"
                    v-if="errors.has('addEditValidation.cooking_date')"
                >
                    {{ errors.first("addEditValidation.cooking_date") }}
                </div>
            </div>
        </div>
        <el-button @click="downloadPDF" class="float-left ml-3 mb-3" type="success"
        >تصدير بي دي اف
        </el-button
        >

<button id="btnExport"
        class = "btn mb-2 mr-2 btn-transition btn-primary float-left ml-3 mb-3"

@click="exportReportToExcel('xlsx', 'kitchen.xls')">تصدير لإكسل</button>
        <download-excel
        class = "btn mb-2 mr-2 btn-transition btn-danger float-left ml-3 mb-3"
         :data="items"
         :fields = "itemsExport"
         worksheet = "تقرير المطبخ اليومي"
          name= "kitchen.xls"
         >
         تصدير إكسل
</download-excel>

        <div class="container-fluid">
            <table class="mt-3" id="tableExport">
                <thead>
                <tr>
                    <th>#</th>
                    <th>الاسم</th>
                    <th>عدد الوجبات</th>
                    <th>كمية البروتين</th>
                    <th >كمية الكارب</th>
                    <th >وجبة 1</th>
                    <th >عدد وجبة 1</th>
                    <th >بروتين 1</th>
                    <th >كارب 1</th>
                    <th >وجبة 2</th>
                    <th >عدد وجبة 2</th>
                    <th >بروتين 2</th>
                    <th >كارب 2</th>
                    <th >وجبة 3</th>
                    <th >عدد وجبة 3</th>
                    <th >بروتين 3</th>
                    <th >كارب 3</th>
                   <th >فطور نوع 1</th>
                   <th >شوربة نوع 1</th>
                   <th >سلطة نوع 1</th>
                   <th >ملاحظات</th>
                   <th >فترة التوصيل</th>
                   <th >المدينة</th>
                </tr>
                </thead>
                <tbody>

                <tr v-for="(row, index) in items" :key="index">
                    <td>{{index +1}}</td>
                    <td>{{row.name}}</td>
                    <td>{{row.meals_count}}</td>
                    <td>{{row.protin_count}}</td>
                    <td>{{row.carb_count}}</td>
                    <td>{{row.meal1}}</td>
                    <td>{{row.meal1_count}}</td>
                    <td>{{row.protin1}}</td>
                    <td>{{row.carb1}}</td>
                    <td>{{row.meal2}}</td>
                    <td>{{row.meal2_count}}</td>
                    <td>{{row.protin2}}</td>
                    <td>{{row.carb2}}</td>
                    <td>{{row.meal3}}</td>
                    <td>{{row.meal3_count}}</td>
                    <td>{{row.protin3}}</td>
                    <td>{{row.carb3}}</td>
                    <td>{{row.breakfast1}}</td>
                    <td>{{ row.soap1 }}</td>
                    <td>{{ row.sald1 }}</td>
                    <td>{{ row.notes }}</td>
                    <td>{{ row.delivary }}</td>
                    <td>{{ row.city }}</td>
                </tr>
                <!-- <tr v-for="(row, index) in items">
                    <td >{{ index + 1 }}</td>
                    <td >{{ row.customer.name }}</td>
                    <td >{{ row.customer.mobile }}</td>
                    <td >{{ row.subscribe.start_date }}</td>
                    <td >{{ row.subscribe.end_date }}</td>
                    <td >{{ row.package.name }}</td>
                    <td >{{ row.package.price }}</td>
                    <td >{{ row.package.number_of_days }}</td>
                    <td >{{ row.package.number_of_meals }}</td>
                    <td >{{ row.protein }}</td>
                    <td >{{ row.carb }}</td>
                    <td >{{ row.standard }}</td>
                    <td >{{ row.keto }}</td>
                    <td width="500px" style="color: red">
                        (بدون:
                        <span v-for="(item, index2) in row.exclude_main_ingredients">
                {{ item }}
                <span v-if="index2 !== row.note_recipe.length - 1">،</span>
              </span>
                        ) ( بدون:
                        <span v-for="(item2, index3) in row.personal_desires.exclude_not_main_ingredients">
                {{ item2 }}
                <span v-if="index3 !== row.personal_desires.exclude_not_main_ingredients.length - 1">،</span>
              </span>
                        )
                    </td>

                    <td >{{ row.deliveries.city }}</td>
                    <td >{{ row.deliveries.branch }}</td>
                    <td >{{ row.deliveries.period }}</td>
                    <td >{{ row.deliveries.delegate_name }}</td>
                    <td >{{ row.active }}</td>
                    <td  v-if="row.menu_selected[0][0]">{{ row.menu_selected[0][0].recipie_protein_name }}</td>
                    <td  v-if="row.menu_selected[0][0]">{{ row.menu_selected[0][0].recipie_carb_name }}</td>
                    <td  v-if="row.menu_selected[0][0]">{{ row.menu_selected[0][0].quantity }}</td>
                    <td  v-if="row.menu_selected[0][0]">{{ row.menu_selected[0][0].protein }}</td>
                    <td  v-if="row.menu_selected[0][0]">{{ row.menu_selected[0][0].carb }}</td>
                    <td  v-if="row.menu_selected[0][1]">{{ row.menu_selected[0][1].recipie_protein_name }}</td>
                    <td  v-if="row.menu_selected[0][1]">{{ row.menu_selected[0][1].recipie_carb_name }}</td>
                    <td  v-if="row.menu_selected[0][1]">{{ row.menu_selected[0][1].quantity }}</td>
                    <td  v-if="row.menu_selected[0][1]">{{ row.menu_selected[0][1].protein }}</td>
                    <td  v-if="row.menu_selected[0][1]">{{ row.menu_selected[0][1].carb }}</td>
                    <td   v-if="row.menu_selected[0][2]">{{ row.menu_selected[0][2].recipie_protein_name }}</td>
                    <td   v-if="row.menu_selected[0][2]">{{ row.menu_selected[0][2].recipie_carb_name }}</td>
                    <td   v-if="row.menu_selected[0][2]">{{ row.menu_selected[0][2].quantity }}</td>
                    <td   v-if="row.menu_selected[0][2]">{{ row.menu_selected[0][2].protein }}</td>
                    <td   v-if="row.menu_selected[0][2]">{{ row.menu_selected[0][2].carb }}</td>
<!--                    <td >{{ row.details_recipies[2].recipe_name }}</td>-->
<!--                    <td >{{ row.details_recipies[2].quantity }}</td>-->
<!--                    <td >{{ row.details_recipies[3].recipe_name }}</td>-->
<!--                    <td >{{ row.details_recipies[3].quantity }}</td>-->
<!--                    <td >{{ row.details_recipies[4].recipe_name }}</td>-->
<!--                    <td >{{ row.details_recipies[4].quantity }}</td>-->
<!--                    <td width="600px">{{ row.details_recipies[5].recipe_name }}</td>-->
<!--                    <td >{{ row.details_recipies[5].quantity }}</td>-->
<!--                    <td style="color: red">-->
<!--                        (بدون:-->
<!--                        <span v-for="(item, index2) in row.note_recipe">-->
<!--                {{ item }}-->
<!--                <span v-if="index2 !== row.note_recipe.length - 1">،</span>-->
<!--              </span>-->
<!--                        ) (-->
<!--                        <span v-for="(item2, index3) in row.note_ingredients">-->
<!--                {{ item2 }}-->
<!--                <span v-if="index3 !== row.note_ingredients.length - 1">،</span>-->
<!--              </span>-->
<!--                        )-->
<!--                    </td>-->
<!--                    <td >{{ row.package_name }}</td>-->
<!--                    <td >{{ row.delivery }}</td>-->
<!--                    <td >{{ row.period }}</td>-->
<!--                    <td >{{ row.side }}</td>-->
<!--                    <td >{{ row.area }}</td>-->
                <!-- </tr> -->
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
        items: [
        {
        'name': "صدام",
        'meals_count': "عدد وجبات الباقة",
        'protin_count': "كمية البروتين",
        'carb_count': "كمية الكارب",
        'meal1': 'وجبة 1',
       'meal1_count':'عددها',
        'protin1': 'بروتين 1',
        'carb1': 'كارب 1',
         'meal2': 'وجبة 2',
       'meal2_count':'عددها',
        'protin2': 'بروتين 2',
        'carb2': 'كارب 2',
       'meal3_count':'عددها',
        'protin3': 'بروتين 3',
        'carb3': 'كارب 3',
        'meal3': 'وجبة 3',
        'breakfast1': 'فطور نوع 1',
        'soap1': 'شوربة نوع 1',
        'sald1': 'سلطة نوع 1',
        'notes': 'زبادي خضار',
        'delivary': 'صباحي',
        'city': 'وسط أ',
      },
        {
        'name': "صدام",
        'meals_count': "عدد وجبات الباقة",
        'protin_count': "كمية البروتين",
        'carb_count': "كمية الكارب",
        'meal1': 'وجبة 1',
       'meal1_count':'عددها',
        'protin1': 'بروتين 1',
        'carb1': 'كارب 1',
         'meal2': 'وجبة 2',
       'meal2_count':'عددها',
        'protin2': 'بروتين 2',
        'carb2': 'كارب 2',
       'meal3_count':'عددها',
        'protin3': 'بروتين 3',
        'carb3': 'كارب 3',
        'meal3': 'وجبة 3',
        'breakfast1': 'فطور نوع 1',
        'soap1': 'شوربة نوع 1',
        'sald1': 'سلطة نوع 1',
        'notes': 'زبادي خضار',
        'delivary': 'صباحي',
        'city': 'وسط أ',
      },
        {
        'name': "صدام",
        'meals_count': "عدد وجبات الباقة",
        'protin_count': "كمية البروتين",
        'carb_count': "كمية الكارب",
        'meal1': 'وجبة 1',
       'meal1_count':'عددها',
        'protin1': 'بروتين 1',
        'carb1': 'كارب 1',
         'meal2': 'وجبة 2',
       'meal2_count':'عددها',
        'protin2': 'بروتين 2',
        'carb2': 'كارب 2',
       'meal3_count':'عددها',
        'protin3': 'بروتين 3',
        'carb3': 'كارب 3',
        'meal3': 'وجبة 3',
        'breakfast1': 'فطور نوع 1',
        'soap1': 'شوربة نوع 1',
        'sald1': 'سلطة نوع 1',
        'notes': 'زبادي خضار',
        'delivary': 'صباحي',
        'city': 'وسط أ',
      },
        {
        'name': "صدام",
        'meals_count': "عدد وجبات الباقة",
        'protin_count': "كمية البروتين",
        'carb_count': "كمية الكارب",
        'meal1': 'وجبة 1',
       'meal1_count':'عددها',
        'protin1': 'بروتين 1',
        'carb1': 'كارب 1',
         'meal2': 'وجبة 2',
       'meal2_count':'عددها',
        'protin2': 'بروتين 2',
        'carb2': 'كارب 2',
       'meal3_count':'عددها',
        'protin3': 'بروتين 3',
        'carb3': 'كارب 3',
        'meal3': 'وجبة 3',
        'breakfast1': 'فطور نوع 1',
        'soap1': 'شوربة نوع 1',
        'sald1': 'سلطة نوع 1',
        'notes': 'زبادي خضار',
        'delivary': 'صباحي',
        'city': 'وسط أ',
      },
        {
        'name': "صدام",
        'meals_count': "عدد وجبات الباقة",
        'protin_count': "كمية البروتين",
        'carb_count': "كمية الكارب",
        'meal1': 'وجبة 1',
       'meal1_count':'عددها',
        'protin1': 'بروتين 1',
        'carb1': 'كارب 1',
         'meal2': 'وجبة 2',
       'meal2_count':'عددها',
        'protin2': 'بروتين 2',
        'carb2': 'كارب 2',
       'meal3_count':'عددها',
        'protin3': 'بروتين 3',
        'carb3': 'كارب 3',
        'meal3': 'وجبة 3',
        'breakfast1': 'فطور نوع 1',
        'soap1': 'شوربة نوع 1',
        'sald1': 'سلطة نوع 1',
        'notes': 'زبادي خضار',
        'delivary': 'صباحي',
        'city': 'وسط أ',
      },
        {
        'name': "صدام",
        'meals_count': "عدد وجبات الباقة",
        'protin_count': "كمية البروتين",
        'carb_count': "كمية الكارب",
        'meal1': 'وجبة 1',
       'meal1_count':'عددها',
        'protin1': 'بروتين 1',
        'carb1': 'كارب 1',
         'meal2': 'وجبة 2',
       'meal2_count':'عددها',
        'protin2': 'بروتين 2',
        'carb2': 'كارب 2',
       'meal3_count':'عددها',
        'protin3': 'بروتين 3',
        'carb3': 'كارب 3',
        'meal3': 'وجبة 3',
        'breakfast1': 'فطور نوع 1',
        'soap1': 'شوربة نوع 1',
        'sald1': 'سلطة نوع 1',
        'notes': 'زبادي خضار',
        'delivary': 'صباحي',
        'city': 'وسط أ',
      },
        {
        'name': "صدام",
        'meals_count': "عدد وجبات الباقة",
        'protin_count': "كمية البروتين",
        'carb_count': "كمية الكارب",
        'meal1': 'وجبة 1',
       'meal1_count':'عددها',
        'protin1': 'بروتين 1',
        'carb1': 'كارب 1',
         'meal2': 'وجبة 2',
       'meal2_count':'عددها',
        'protin2': 'بروتين 2',
        'carb2': 'كارب 2',
       'meal3_count':'عددها',
        'protin3': 'بروتين 3',
        'carb3': 'كارب 3',
        'meal3': 'وجبة 3',
        'breakfast1': 'فطور نوع 1',
        'soap1': 'شوربة نوع 1',
        'sald1': 'سلطة نوع 1',
        'notes': 'زبادي خضار',
        'delivary': 'صباحي',
        'city': 'وسط أ',
      },
        {
        'name': "صدام",
        'meals_count': "عدد وجبات الباقة",
        'protin_count': "كمية البروتين",
        'carb_count': "كمية الكارب",
        'meal1': 'وجبة 1',
       'meal1_count':'عددها',
        'protin1': 'بروتين 1',
        'carb1': 'كارب 1',
         'meal2': 'وجبة 2',
       'meal2_count':'عددها',
        'protin2': 'بروتين 2',
        'carb2': 'كارب 2',
       'meal3_count':'عددها',
        'protin3': 'بروتين 3',
        'carb3': 'كارب 3',
        'meal3': 'وجبة 3',
        'breakfast1': 'فطور نوع 1',
        'soap1': 'شوربة نوع 1',
        'sald1': 'سلطة نوع 1',
        'notes': 'زبادي خضار',
        'delivary': 'صباحي',
        'city': 'وسط أ',
      },
        {
        'name': "صدام",
        'meals_count': "عدد وجبات الباقة",
        'protin_count': "كمية البروتين",
        'carb_count': "كمية الكارب",
        'meal1': 'وجبة 1',
       'meal1_count':'عددها',
        'protin1': 'بروتين 1',
        'carb1': 'كارب 1',
         'meal2': 'وجبة 2',
       'meal2_count':'عددها',
        'protin2': 'بروتين 2',
        'carb2': 'كارب 2',
       'meal3_count':'عددها',
        'protin3': 'بروتين 3',
        'carb3': 'كارب 3',
        'meal3': 'وجبة 3',
        'breakfast1': 'فطور نوع 1',
        'soap1': 'شوربة نوع 1',
        'sald1': 'سلطة نوع 1',
        'notes': 'زبادي خضار',
        'delivary': 'صباحي',
        'city': 'وسط أ',
      },
        {
        'name': "صدام",
        'meals_count': "عدد وجبات الباقة",
        'protin_count': "كمية البروتين",
        'carb_count': "كمية الكارب",
        'meal1': 'وجبة 1',
       'meal1_count':'عددها',
        'protin1': 'بروتين 1',
        'carb1': 'كارب 1',
         'meal2': 'وجبة 2',
       'meal2_count':'عددها',
        'protin2': 'بروتين 2',
        'carb2': 'كارب 2',
       'meal3_count':'عددها',
        'protin3': 'بروتين 3',
        'carb3': 'كارب 3',
        'meal3': 'وجبة 3',
        'breakfast1': 'فطور نوع 1',
        'soap1': 'شوربة نوع 1',
        'sald1': 'سلطة نوع 1',
        'notes': 'زبادي خضار',
        'delivary': 'صباحي',
        'city': 'وسط أ',
      },
        {
        'name': "صدام",
        'meals_count': "عدد وجبات الباقة",
        'protin_count': "كمية البروتين",
        'carb_count': "كمية الكارب",
        'meal1': 'وجبة 1',
       'meal1_count':'عددها',
        'protin1': 'بروتين 1',
        'carb1': 'كارب 1',
         'meal2': 'وجبة 2',
       'meal2_count':'عددها',
        'protin2': 'بروتين 2',
        'carb2': 'كارب 2',
       'meal3_count':'عددها',
        'protin3': 'بروتين 3',
        'carb3': 'كارب 3',
        'meal3': 'وجبة 3',
        'breakfast1': 'فطور نوع 1',
        'soap1': 'شوربة نوع 1',
        'sald1': 'سلطة نوع 1',
        'notes': 'زبادي خضار',
        'delivary': 'صباحي',
        'city': 'وسط أ',
      },
        {
        'name': "صدام",
        'meals_count': "عدد وجبات الباقة",
        'protin_count': "كمية البروتين",
        'carb_count': "كمية الكارب",
        'meal1': 'وجبة 1',
       'meal1_count':'عددها',
        'protin1': 'بروتين 1',
        'carb1': 'كارب 1',
         'meal2': 'وجبة 2',
       'meal2_count':'عددها',
        'protin2': 'بروتين 2',
        'carb2': 'كارب 2',
       'meal3_count':'عددها',
        'protin3': 'بروتين 3',
        'carb3': 'كارب 3',
        'meal3': 'وجبة 3',
        'breakfast1': 'فطور نوع 1',
        'soap1': 'شوربة نوع 1',
        'sald1': 'سلطة نوع 1',
        'notes': 'زبادي خضار',
        'delivary': 'صباحي',
        'city': 'وسط أ',
      },
        {
        'name': "صدام",
        'meals_count': "عدد وجبات الباقة",
        'protin_count': "كمية البروتين",
        'carb_count': "كمية الكارب",
        'meal1': 'وجبة 1',
       'meal1_count':'عددها',
        'protin1': 'بروتين 1',
        'carb1': 'كارب 1',
         'meal2': 'وجبة 2',
       'meal2_count':'عددها',
        'protin2': 'بروتين 2',
        'carb2': 'كارب 2',
       'meal3_count':'عددها',
        'protin3': 'بروتين 3',
        'carb3': 'كارب 3',
        'meal3': 'وجبة 3',
        'breakfast1': 'فطور نوع 1',
        'soap1': 'شوربة نوع 1',
        'sald1': 'سلطة نوع 1',
        'notes': 'زبادي خضار',
        'delivary': 'صباحي',
        'city': 'وسط أ',
      },

    ],
    itemsExport: {
        // '#': {
        //     callback: (data, index) => {
        //         return `${index}`;
        //     }
        // },
        'الاسم': 'name',
         'عدد الوجبات': 'meals_count',
         'كمية البروتين': 'protin_count',
         'كمية الكارب': 'carb_count',
        'وجبة 1': 'meal1',
        'عدد وجبة 1': 'meal1_count',
        'بروتين 1': 'protin1',
        'كارب 1': 'carb1',
         'وجبة 2': 'meal2',
        'عدد وجبة 2': 'meal2_count',
        'بروتين 2': 'protin2',
        'كارب 2': 'carb2',
         'وجبة 3': 'meal3',
        'عدد وجبة 3': 'meal3_count',
        'بروتين 3': 'protin3',
        'كارب 3': 'carb3',
        'فطور نوع 1': 'breakfast1',
        'شوربة نوع 1': 'soap1',
        'سلطة نوع 1': 'sald1',
        'ملاحظات': 'notes',
        'فترة التوصيل': 'delivary',
        'المدينة': 'city',
    },
            addEditObj: {
                date: '',
            },

        };
    },
    methods: {
        exportReportToExcel(type, fn, dl) {
            var elt= document.getElementById('tableExport');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "تقرير المطبخ اليومي" });
              return dl ?
         XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }):
         XLSX.writeFile(wb, fn || ('MySheetName.' + (type || 'xlsx')));
        },
        initData() {
            let params = {
                date: '2022-06-18'
            }
            this.$store.dispatch(`reports/getCookingTodayReport`, params).then((res) => {
                // this.items = res.data;
            }).catch(err => {

            })
        },
        downloadPDF() {
            // this.$store
            //     .dispatch(`reports/downloadCookingTodayPDFReport`)
            //     .then(() => {
            //     });
        window.print();
        },
    },
    created() {
        this.initData();
    },
};
</script>

<style lang="scss">
.reportPage {
    table {
        border-collapse: collapse;
        width: 100%;
    }

    td,
    th {
        border: 1px solid #dddddd;
        text-align: center;
        padding: 4px;
        font-weight: bold;
        font-size: 14px;
    }

    th {
        padding-top: 4px;
        padding-bottom: 6px;
        color: #fff;

    }

    h2 {
        text-align: center;
        color: #000;
        margin-top: 15px;
    }

    th:nth-child(1),
    th:nth-child(2),
    th:nth-child(3),
    th:nth-child(4),
    th:nth-child(5) {
        background-color: #808080;
    }

    th:nth-child(6),
    th:nth-child(7),
    th:nth-child(8),
    th:nth-child(9),
    th:nth-child(10),
    th:nth-child(11),
    th:nth-child(12),
    th:nth-child(13),
    th:nth-child(14),
    th:nth-child(15),
    th:nth-child(16),
    th:nth-child(17) {
        background-color: #bf8f00;
    }

    th:nth-child(18),
    th:nth-child(19),
    th:nth-child(20) {
        background-color: #bf8f00;
    }

    th:nth-child(21),
    th:nth-child(22),
    th:nth-child(23),
    th:nth-child(24),
    th:nth-child(25),
    th:nth-child(26),
    th:nth-child(27),
    th:nth-child(28),
    th:nth-child(29),
    th:nth-child(30),
    th:nth-child(31),
    th:nth-child(32),
    th:nth-child(33),
    th:nth-child(34),
    th:nth-child(35) {
        background-color: #808080;
    }
}

@media print {
    h2,
    .el-button,
    .customDate {
        display: none;
    }

}
</style>
