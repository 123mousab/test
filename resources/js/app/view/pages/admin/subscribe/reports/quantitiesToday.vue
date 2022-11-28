<template>
    <div class="reportPage">
        <h2 class="mt-4">جدول الكميات اليومي</h2>
        <div class="col-md-4 customDate">
            <div
                class="form-group"
                :class="{ 'has-error': errors.has('addEditValidation.date') }"
            >
                <label class="control-label">التاريخ</label>
                <input
                    type="date"
                    name="date"
                    class="form-control"
                    v-validate="'required'"
                    data-vv-scope="addEditValidation"
                    :data-vv-as="$t('Cooking Date')"
                    v-model="addEditObj.date"
                    @change="getReport"
                />
                <div
                    class="help-block"
                    v-if="errors.has('addEditValidation.date')"
                >
                    {{ errors.first("addEditValidation.date") }}
                </div>
            </div>
        </div>


        <el-button v-if="addEditObj.date" @click="generateReport" class="float-left ml-3 mb-3" type="success"
        >تصدير بي دي اف
        </el-button
        >

              <button v-if="addEditObj.date" id="btnExport"
        class = "btn mb-2 mr-2 btn-transition btn-primary float-left ml-3 mb-3"

@click="exportReportToExcel('xlsx', 'quantities.xls')">تصدير لإكسل</button>

     <div class="container-fluid" v-if="addEditObj.date">
            <table class="mt-3" id="tableExport">
                <thead>
                <tr>
                    <th style="background:#c9e1ed;color:#c73434;font-weigh:bold;;font-size:20px;"
                    colspan="12">
                    <span>التقرير اليومي للكميات (Daily Update Report)
</span>
<br>
<span style="color:#000;font-size:18px;">

    {{ addEditObj.date }}

</span>
                    </th>
                </tr>
                  <tr style="background:#fffcd4;">
                        <th colspan="4">
                            # Subscribers for today
                            <br>
                            # المشتركين لهذا اليوم
                        </th>
                        <th colspan="4">
                             # Active Subscribers
                            <br>
                            # المشتركين الفعالين
                        </th>
                        <th colspan="4">
                              # Temp. Subscriber
                            <br>
                            # الإيقافات المؤقتة
                        </th>
                    </tr>
                     <tr style="background:#c9e1ed;">
                        <th colspan="4">{{ items.subscribe.day }}</th>
                        <th colspan="4">{{ items.subscribe.active  }}</th>
                        <th colspan="4">{{ items.subscribe.stop  }}</th>
                    </tr>
                     <tr style="background:#fffcd4;">
                        <th :colspan="items.subscribe.meal_protein.length == 1? 12: items.subscribe.meal_protein.length == 2? 6  : items.subscribe.meal_protein.length == 3? 4 : items.subscribe.meal_protein.length == 4?3:0"

                        v-if="items.subscribe.meal_protein.length>0">
                            الوجبة الأولى (First Meal)
                        </th>
                        <th
                        :colspan="items.subscribe.meal_protein.length == 1? 12: items.subscribe.meal_protein.length == 2? 6  : items.subscribe.meal_protein.length == 3? 4 : items.subscribe.meal_protein.length == 4?3:0"
                        v-if="items.subscribe.meal_protein.length>1">
                          الوجبة الثانية (Second Meal)
                        </th>
                        <th
                        :colspan="items.subscribe.meal_protein.length == 1? 12: items.subscribe.meal_protein.length == 2? 6  : items.subscribe.meal_protein.length == 3? 4 : items.subscribe.meal_protein.length == 4?3:0"
                        v-if="items.subscribe.meal_protein.length>2">
                            الوجبة الثالثة (Third Meal)
                        </th>
                          <th
                          :colspan="items.subscribe.meal_protein.length == 1? 12: items.subscribe.meal_protein.length == 2? 6  : items.subscribe.meal_protein.length == 3? 4 : items.subscribe.meal_protein.length == 4?3:0"
                           v-if="items.subscribe.meal_protein.length>3">
                            الوجبة الرابعة (Fourth Meal)
                        </th>
                    </tr>
                     <tr style="background:#c9e1ed;">
                        <th
                        :colspan="items.subscribe.meal_protein.length == 1? 12: items.subscribe.meal_protein.length == 2? 6  : items.subscribe.meal_protein.length == 3? 4 : items.subscribe.meal_protein.length == 4?3:0"
                         v-if="items.subscribe.meal_protein.length>0">
                           <span>
                            {{ items.subscribe.meal_protein[0].recipie_protein_name }} -
                            {{ items.subscribe.meal_protein[0].count }}
                           </span>
                        </th>
                        <th
                        :colspan="items.subscribe.meal_protein.length == 1? 12: items.subscribe.meal_protein.length == 2? 6  : items.subscribe.meal_protein.length == 3? 4 : items.subscribe.meal_protein.length == 4?3:0"
                        v-if="items.subscribe.meal_protein.length>1">
                             <span>
                            {{ items.subscribe.meal_protein[1].recipie_protein_name }} -
                            {{ items.subscribe.meal_protein[1].count }}
                           </span>
                        </th>
                        <th
                        :colspan="items.subscribe.meal_protein.length == 1? 12: items.subscribe.meal_protein.length == 2? 6  : items.subscribe.meal_protein.length == 3? 4 : items.subscribe.meal_protein.length == 4?3:0"
                        v-if="items.subscribe.meal_protein.length>2">
                             <span>
                            {{ items.subscribe.meal_protein[2].recipie_protein_name }} -
                            {{ items.subscribe.meal_protein[2].count }}
                           </span>
                        </th>
                         <th
                         :colspan="items.subscribe.meal_protein.length == 1? 12: items.subscribe.meal_protein.length == 2? 6  : items.subscribe.meal_protein.length == 3? 4 : items.subscribe.meal_protein.length == 4?3:0"
                         v-if="items.subscribe.meal_protein.length>3">
                             <span>
                            {{ items.subscribe.meal_protein[3].recipie_protein_name }} -
                            {{ items.subscribe.meal_protein[3].count }}
                           </span>
                        </th>
                    </tr>
                     <tr style="background:#c9e1ed;">
                        <th
                        :colspan="items.subscribe.meal_protein.length == 1? 12: items.subscribe.meal_protein.length == 2? 6  : items.subscribe.meal_protein.length == 3? 4 : items.subscribe.meal_protein.length == 4?3:0"
                        v-if="items.subscribe.meal_carb.length>0">
                             <span >
                            {{ items.subscribe.meal_carb[0].recipie_carb_name }} -
                            {{ items.subscribe.meal_carb[0].count }}
                           </span>
                        </th>
                        <th
                        :colspan="items.subscribe.meal_protein.length == 1? 12: items.subscribe.meal_protein.length == 2? 6  : items.subscribe.meal_protein.length == 3? 4 : items.subscribe.meal_protein.length == 4?3:0"
                        v-if="items.subscribe.meal_carb.length>1">
                              <span >
                            {{ items.subscribe.meal_carb[1].recipie_carb_name }} -
                            {{ items.subscribe.meal_carb[1].count }}
                           </span>
                        </th>
                        <th
                        :colspan="items.subscribe.meal_protein.length == 1? 12: items.subscribe.meal_protein.length == 2? 6  : items.subscribe.meal_protein.length == 3? 4 : items.subscribe.meal_protein.length == 4?3:0"
                        v-if="items.subscribe.meal_carb.length>2">
                                <span >
                            {{ items.subscribe.meal_carb[2].recipie_carb_name }} -
                            {{ items.subscribe.meal_carb[2].count }}
                           </span>
                        </th>
                         <th
                         :colspan="items.subscribe.meal_protein.length == 1? 12: items.subscribe.meal_protein.length == 2? 6  : items.subscribe.meal_protein.length == 3? 4 : items.subscribe.meal_protein.length == 4?3:0"
                         v-if="items.subscribe.meal_carb.length>3">
                               <span >
                            {{ items.subscribe.meal_carb[3].recipie_carb_name }} -
                            {{ items.subscribe.meal_carb[3].count }}
                           </span>
                        </th>
                    </tr>
                     <tr style="background:#9eda9e;">
                        <th colspan="12">مع اسبايسي (With Spicy)</th>
                    </tr>
                      <tr style="background:#fffcd4;">
                        <th colspan="12">وجبات رئيسية</th>
                    </tr>
                     <tr style="background:#fffcd4;">
                        <th colspan="3">
                            الصنف (.Meal Cat)
                        </th>
                        <th colspan="3">
                            # الوجبات (# Meals)
                        </th>
                        <th colspan="3">
                            بروتين (Protein)
                        </th>
                        <th colspan="3">
                            كارب (Carb)
                        </th>
                    </tr>
                     <tr style="background:#c9e1ed;" v-if="items.subscribe.meal_protein_with_spicy.length>0">
                        <th style="background:#fffcd4;" colspan="3">
                            الوجبة الأولى (First Meal)
                        </th>
                        <th colspan="3">
                              <span v-if="items.subscribe.meal_protein_with_spicy.length>0">
                            {{ items.subscribe.meal_protein_with_spicy[0].count }}
                           </span>
                        </th>
                        <th colspan="3">
                            <span v-if="items.subscribe.meal_protein_with_spicy.length>0">
                            {{ items.subscribe.meal_protein_with_spicy[0].sum_protein }}
                           </span>
                            </th>
                        <th colspan="3">
                           <span v-if="items.subscribe.meal_protein_with_spicy.length>0">
                            {{ items.subscribe.meal_protein_with_spicy[0].sum_carb }}
                           </span>
                            </th>
                    </tr>
                     <tr style="background:#c9e1ed;" v-if="items.subscribe.meal_protein_with_spicy.length>1">
                        <th style="background:#fffcd4;" colspan="3">
                             الوجبة الثانية (Second Meal)
                        </th>
                         <th colspan="3">
                              <span v-if="items.subscribe.meal_protein_with_spicy.length>1">
                            {{ items.subscribe.meal_protein_with_spicy[1].count }}
                           </span>
                         </th>
                        <th colspan="3">
                              <span v-if="items.subscribe.meal_protein_with_spicy.length>1">
                            {{ items.subscribe.meal_protein_with_spicy[1].sum_protein }}
                           </span>
                            </th>
                        <th colspan="3">
                            <span v-if="items.subscribe.meal_protein_with_spicy.length>1">
                            {{ items.subscribe.meal_protein_with_spicy[1].sum_carb }}
                           </span>
                            </th>
                    </tr>
                     <tr style="background:#c9e1ed;" v-if="items.subscribe.meal_protein_with_spicy.length>2">
                        <th style="background:#fffcd4;" colspan="3">
                             الوجبة الثالثة (Third Meal)
                        </th>
                        <th colspan="3">
                              <span v-if="items.subscribe.meal_protein_with_spicy.length>2">
                            {{ items.subscribe.meal_protein_with_spicy[2].count }}
                           </span>
                        </th>
                        <th colspan="3">
                              <span v-if="items.subscribe.meal_protein_with_spicy.length>2">
                            {{ items.subscribe.meal_protein_with_spicy[2].sum_protein }}
                           </span>
                            </th>
                        <th colspan="3">
                             <span v-if="items.subscribe.meal_protein_with_spicy.length>2">
                            {{ items.subscribe.meal_protein_with_spicy[2].sum_carb }}
                           </span>
                            </th>
                    </tr>

                     <tr style="background:#c9e1ed;" v-if="items.subscribe.meal_protein_with_spicy.length>3">
                        <th style="background:#fffcd4;" colspan="3">
                             الوجبة الرابعة (Fourth Meal)
                        </th>
                        <th colspan="3">
                              <span v-if="items.subscribe.meal_protein_with_spicy.length>3">
                            {{ items.subscribe.meal_protein_with_spicy[3].count }}
                           </span>
                        </th>
                        <th colspan="3">
                              <span v-if="items.subscribe.meal_protein_with_spicy.length>3">
                            {{ items.subscribe.meal_protein_with_spicy[3].sum_protein }}
                           </span>
                            </th>
                        <th colspan="3">
                             <span v-if="items.subscribe.meal_protein_with_spicy.length>3">
                            {{ items.subscribe.meal_protein_with_spicy[3].sum_carb }}
                           </span>
                            </th>
                    </tr>

                     <tr style="background:#fffcd4;">
                        <th colspan="12">مقبلات الاشتراك</th>
                    </tr>
                      <tr style="background:#c9e1ed;" v-for="(g, gIndex) of items.subscribe.group" :key="gIndex">
                        <th colspan="6">
                            {{ g.recipie_name }}
                        </th>
                        <th colspan="6">{{ g.count }}</th>
                    </tr>

                     <tr style="background:#9eda9e;">
                        <th colspan="12">
                            بدون اسبايسي (Without Spicy)
                        </th>

                      <tr style="background:#fffcd4;">
                        <th colspan="12">وجبات رئيسية</th>
                    </tr>
                     <tr style="background:#fffcd4;">
                        <th colspan="3">
                           <span> الصنف (.Meal Cat)</span>
                        </th>
                        <th colspan="3">
                            # الوجبات (# Meals)
                        </th>
                        <th colspan="3">
                            بروتين (Protein)
                        </th>
                        <th colspan="3">
                            كارب (Carb)
                        </th>
                    </tr>


                    <tr style="background:#c9e1ed;" v-if="items.subscribe.meal_protein_without_spicy.length>0">
                        <th style="background:#fffcd4;" colspan="3">
                            الوجبة الأولى (First Meal)
                        </th>
                        <th colspan="3">
                              <span v-if="items.subscribe.meal_protein_without_spicy.length>0">
                            {{ items.subscribe.meal_protein_without_spicy[0].count }}
                           </span>
                        </th>
                        <th colspan="3">
                            <span v-if="items.subscribe.meal_protein_without_spicy.length>0">
                            {{ items.subscribe.meal_protein_without_spicy[0].sum_protein }}
                           </span>
                            </th>
                        <th colspan="3">
                           <span v-if="items.subscribe.meal_protein_without_spicy.length>0">
                            {{ items.subscribe.meal_protein_without_spicy[0].sum_carb }}
                           </span>
                            </th>
                    </tr>
                     <tr style="background:#c9e1ed;" v-if="items.subscribe.meal_protein_without_spicy.length>1">
                        <th style="background:#fffcd4;" colspan="3">
                             الوجبة الثانية (Second Meal)
                        </th>
                         <th colspan="3">
                              <span v-if="items.subscribe.meal_protein_without_spicy.length>1">
                            {{ items.subscribe.meal_protein_without_spicy[1].count }}
                           </span>
                         </th>
                        <th colspan="3">
                              <span v-if="items.subscribe.meal_protein_without_spicy.length>1">
                            {{ items.subscribe.meal_protein_without_spicy[1].sum_protein }}
                           </span>
                            </th>
                        <th colspan="3">
                            <span v-if="items.subscribe.meal_protein_without_spicy.length>1">
                            {{ items.subscribe.meal_protein_without_spicy[1].sum_carb }}
                           </span>
                            </th>
                    </tr>
                     <tr style="background:#c9e1ed;" v-if="items.subscribe.meal_protein_without_spicy.length>2">
                        <th style="background:#fffcd4;" colspan="3">
                             الوجبة الثالثة (Third Meal)
                        </th>
                        <th colspan="3">
                              <span v-if="items.subscribe.meal_protein_without_spicy.length>2">
                            {{ items.subscribe.meal_protein_without_spicy[2].count }}
                           </span>
                        </th>
                        <th colspan="3">
                              <span v-if="items.subscribe.meal_protein_without_spicy.length>2">
                            {{ items.subscribe.meal_protein_without_spicy[2].sum_protein }}
                           </span>
                            </th>
                        <th colspan="3">
                             <span v-if="items.subscribe.meal_protein_without_spicy.length>2">
                            {{ items.subscribe.meal_protein_without_spicy[2].sum_carb }}
                           </span>
                            </th>
                    </tr>

                     <tr style="background:#c9e1ed;" v-if="items.subscribe.meal_protein_without_spicy.length>3">
                        <th style="background:#fffcd4;" colspan="3">
                             الوجبة الرابعة (Fourth Meal)
                        </th>
                        <th colspan="3">
                              <span v-if="items.subscribe.meal_protein_without_spicy.length>3">
                            {{ items.subscribe.meal_protein_without_spicy[3].count }}
                           </span>
                        </th>
                        <th colspan="3">
                              <span v-if="items.subscribe.meal_protein_without_spicy.length>3">
                            {{ items.subscribe.meal_protein_without_spicy[3].sum_protein }}
                           </span>
                            </th>
                        <th colspan="3">
                             <span v-if="items.subscribe.meal_protein_without_spicy.length>3">
                            {{ items.subscribe.meal_protein_without_spicy[3].sum_carb }}
                           </span>
                            </th>
                    </tr>

                    <tr>
                        <th colspan="12" style="background:#9eda9e;">
                              الاجمالي مع وبدون اسبايسي
                        </th>
                    </tr>

                          <tr style="background:#fffcd4;">
                        <th colspan="12">وجبات رئيسية</th>
                    </tr>
                     <tr style="background:#fffcd4;">
                        <th colspan="3">
                            الصنف (.Meal Cat)
                        </th>
                        <th colspan="3">
                            # الوجبات (# Meals)
                        </th>
                        <th colspan="3">
                            بروتين (Protein)
                        </th>
                        <th colspan="3">
                            كارب (Carb)
                        </th>
                    </tr>
                     <tr style="background:#c9e1ed;"  v-if="items.subscribe.meal_protein.length>0">
                        <th style="background:#fffcd4;" colspan="3">
                            الوجبة الأولى (First Meal)
                        </th>
                        <th colspan="3">

                             <span v-if="items.subscribe.meal_protein.length>0">
                            {{ items.subscribe.meal_protein[0].count }}
                           </span>

                        </th>
                        <th colspan="3">
                              <span v-if="items.subscribe.meal_protein.length>0">
                            {{ items.subscribe.meal_protein[0].sum_protein }}
                           </span>
                        </th>
                        <th colspan="3">
                             <span v-if="items.subscribe.meal_protein.length>0">
                            {{ items.subscribe.meal_protein[0].sum_carb }}
                           </span>
                             </th>
                    </tr>
                     <tr style="background:#c9e1ed;"  v-if="items.subscribe.meal_protein.length>1">
                        <th style="background:#fffcd4;" colspan="3">
                             الوجبة الثانية (Second Meal)
                        </th>
                         <th colspan="3">
                              <span v-if="items.subscribe.meal_protein.length>1">
                            {{ items.subscribe.meal_protein[1].count }}
                           </span>
                         </th>
                        <th colspan="3">
                              <span v-if="items.subscribe.meal_protein.length>1">
                            {{ items.subscribe.meal_protein[1].sum_protein }}
                           </span>
                        </th>
                        <th colspan="3">
                             <span v-if="items.subscribe.meal_protein.length>1">
                            {{ items.subscribe.meal_protein[1].sum_carb }}
                           </span>
                             </th>
                    </tr>
                     <tr style="background:#c9e1ed;" v-if="items.subscribe.meal_protein.length>2">
                        <th style="background:#fffcd4;" colspan="3">
                             الوجبة الثالثة (Third Meal)
                        </th>
                        <th colspan="3">
                            <span v-if="items.subscribe.meal_protein.length>2">
                            {{ items.subscribe.meal_protein[2].count }}
                           </span>
                        </th>
                        <th colspan="3">
                            <span v-if="items.subscribe.meal_protein.length>2">
                            {{ items.subscribe.meal_protein[2].sum_protein }}
                           </span>
                        </th>
                        <th colspan="3">
                            <span v-if="items.subscribe.meal_protein.length>2">
                            {{ items.subscribe.meal_protein[2].sum_carb }}
                           </span>
                        </th>
                    </tr>

                      <tr style="background:#c9e1ed;" v-if="items.subscribe.meal_protein.length>3">
                        <th style="background:#fffcd4;" colspan="3">
                             الوجبة الرابعة (Fourth Meal)
                        </th>
                        <th colspan="3">
                            <span v-if="items.subscribe.meal_protein.length>3">
                            {{ items.subscribe.meal_protein[3].count }}
                           </span>
                        </th>
                        <th colspan="3">
                            <span v-if="items.subscribe.meal_protein.length>3">
                            {{ items.subscribe.meal_protein[3].sum_protein }}
                           </span>
                        </th>
                        <th colspan="3">
                            <span v-if="items.subscribe.meal_protein.length>3">
                            {{ items.subscribe.meal_protein[3].sum_carb }}
                           </span>
                        </th>
                    </tr>


                      <tr style="background:#fffcd4;">
                        <th colspan="12">
                            إحصائية الباقات
                        </th>
                    </tr>
                      <tr v-for="(lp, lpIndex) of items.subscribe.list_package" :key="lpIndex">
                        <th style="background:#fffcd4;" colspan="6">
                            {{ lp.name }}
                        </th>
                        <th style="background:#c9e1ed;" colspan="6">
                             {{ lp.count }}
                        </th>
                    </tr>

                </thead>
                <tbody>

                </tbody>
            </table>
        </div>


    </div>
</template>

<script>
export default {
    data() {
        return {
            items: [],
            addEditObj: {
                date: '',
            }
        };
    },
    methods: {
        getReport() {
            let params = {
                date: this.addEditObj.date
            }
            this.$store.dispatch(`reports/getQuantititesReport`, params).then((res) => {
                this.items = res.data;
            });
        },
        exportReportToExcel(type, fn, dl) {
            var elt= document.getElementById('tableExport');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "تقرير الكميات اليومي" });
              return dl ?
         XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }):
         XLSX.writeFile(wb, fn || ('MySheetName.' + (type || 'xlsx')));
        },
        generateReport() {
            window.print();
        },
        initData() {
            this.getReport();
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
        border: 1px solid #000;
        padding: 4px;
    }

    th {
        padding-top: 4px;
        padding-bottom: 6px;
        text-align: center;
        color: #000;
        font-weight: bold;
        font-size: 18px;
    }

    h2 {
        text-align: center;
        color: #000;
        margin-top: 15px;
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
