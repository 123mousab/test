<template>
    <div class="reportPage">
        <h2 class="mt-4">جدول المندوب اليومي</h2>
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

@click="exportReportToExcel('xlsx', 'delegate.xls')">تصدير لإكسل</button>

   <download-excel
    v-if="addEditObj.date"
        class = "btn mb-2 mr-2 btn-transition btn-danger float-left ml-3 mb-3"
         :data="items"
         :fields = "itemsExport"
         worksheet = "تقرير المندوب اليومي"
          name= "delegate.xls"
         >
         تصدير إكسل
</download-excel>

     <div class="container-fluid" v-if="addEditObj.date">
            <table class="mt-3" id="tableExport">
                <thead>
                <tr>
                    <th width="100px">#</th>
                    <th width="100px">الاسم</th>
                    <th width="100px">الشركة</th>
                    <th width="100px">الحي</th>
                    <th width="100px">ملاحظات التسليم</th>
                    <th width="100px">المندوب</th>
                    <th width="100px">فترة التوصيل</th>
                </tr>
                </thead>
                <tbody>

                <tr v-for="(row, index) in items" :key="index">
                    <td width="100px">{{ index + 1 }}</td>
                    <td width="100px">{{ row.customer.name }}</td>
                    <td width="100px">{{ row.deliveries.home_address }}</td>
                    <td width="100px">{{ row.deliveries.branch }}</td>
                    <td width="100px">{{ row.deliveries.notes }}</td>
                    <td width="100px">{{ row.deliveries.delegate_name }}</td>
                    <td width="100px">{{ row.deliveries.period }}</td>
                </tr>
                </tbody>
            </table>
        </div>


    </div>
</template>

<script>
export default {
    data() {
        return {
             itemsExport: {
        // '#': {
        //     callback: (data, index) => {
        //         return `${index}`;
        //     }
        // },
        'الاسم': 'customer.name',
         'الشركة': 'deliveries.home_address',
         'الحي': 'deliveries.branch',
         'ملاحظات التسليم': 'deliveries.notes',
        'المندوب': 'deliveries.delegate_name',
        'فترة التوصيل': 'deliveries.period',
    },
            items: [

            ],
            addEditObj: {
                date: '',
            }
        };
    },
    methods: {
        exportReportToExcel(type, fn, dl) {
            var elt= document.getElementById('tableExport');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "تقرير المندوب اليومي" });
              return dl ?
         XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }):
         XLSX.writeFile(wb, fn || ('MySheetName.' + (type || 'xlsx')));
        },
        generateReport() {
            window.print();
        },
        getReport() {
            let params = {
                date: this.addEditObj.date
            }
            this.$store.dispatch(`reports/getDelegateReport`, params).then((res) => {
                this.items = res.data;
            });
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
        text-align: right;
        padding: 4px;
        font-weight: bold;
    }

    th {
        padding-top: 4px;
        padding-bottom: 6px;
    }

    h2 {
        text-align: center;
        color: #000;
        margin-top: 15px;
    }

    th {
        background-color: #888;
        color: #000;
        font-weight: bold;
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
