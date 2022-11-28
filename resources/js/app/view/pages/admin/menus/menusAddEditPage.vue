<template>
    <div>

        <!-- راوتر التنقل -->
        <div class="mt-3">
            <label>
                <router-link to="/">{{ $t("Home") }}</router-link>
            </label>
            <span>/</span>
            <label>
                <router-link to="/menus">منيو المطبخ</router-link>
            </label>
            <span>/</span>

            <label active>
                <span v-if="id && !viewOnly">{{ $t("Edit") }}</span>
                <span v-else-if="viewOnly">{{ $t("Details") }}</span>
                <span v-else>اضافة منيو جديدة</span>
            </label>
        </div>

        <!-- العنوان الرئيسي -->
        <h4 class="mt-3">
            <span v-if="id && !viewOnly">{{ $t("Edit") }}</span>
            <span v-else-if="viewOnly">{{ $t("Details") }}</span>
            <span v-else>اضافة مينو جديدة</span>
        </h4>


        <div class="mt-3">

            <div ref="form">
                <b-card>
                    <div class="row">
                        <div class="col-md-4">
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
                                    v-model="addEditObj.cooking_date"
                                    :disabled="viewMode"
                                />
                                <div
                                    class="help-block"
                                    v-if="errors.has('addEditValidation.cooking_date')"
                                >
                                    {{ errors.first("addEditValidation.cooking_date") }}
                                </div>
                            </div>
                        </div>
                    </div>
<!--                    الفورم الاول-->
                    <div class="row mr-1">
                        <div v-if="!viewOnly" style="margin-right:auto;margin-left:15px;">
                            <el-button @click="addNewFirstGroup" type="success">اضافة مكونات المنيو الاساسية
                            </el-button>
                        </div>


                        <table id="details" class="mt-3">
                            <tr>
                                <th style="width:5rem">#</th>
                                <th style="width:60rem">المكون الاساسي</th>
                                <th style="width:60rem">المطبخ</th>
                                <th style="width:60rem">الوصفة</th>
                                <th v-if="!viewOnly" style="width:60rem">{{ $t('Actions') }}</th>
                            </tr>
                            <tr v-for="(item, index) in addEditObj.first_group" :key="index">
                                <td>{{ index + 1 }}</td>
                                <td>
                                    <el-select
                                        :class="{ 'has-error': errors.has(`addEditValidation.ingredient_id${index + 1}`) }"
                                        v-model="item.ingredient_id"
                                        :name="`ingredient_id${index + 1}`"
                                        data-vv-scope="addEditValidation"
                                        :placeholder="$t('Select')"
                                        clearable
                                        filterable
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
                                </td>
                                <input
                                    type="hidden"
                                    name="number_of_days"
                                    class="form-control"
                                    value="1"
                                    data-vv-scope="addEditValidation"
                                    v-model="item.type"
                                    :disabled="viewOnly"
                                />
                                <td>
                                    <el-select
                                        :class="{ 'has-error': errors.has(`addEditValidation.cuisine_id${index + 1}`) }"
                                        v-model="item.cuisine_id"
                                        :name="`cuisine_id${index + 1}`"
                                        v-validate="'required'"
                                        :data-vv-as="$t('Cuisine')"
                                        data-vv-scope="addEditValidation"
                                        :placeholder="$t('Select')"
                                        clearable
                                        filterable
                                        :disabled="viewOnly"
                                    >
                                        <el-option
                                            v-for="option in cuisineArray"
                                            :value="option.id"
                                            :label="option.name"
                                            :key="option.id"
                                        >
                                        </el-option>
                                    </el-select>
                                </td>
                                <td>
                                    <el-select
                                        :class="{ 'has-error': errors.has(`addEditValidation.recipie_id${index + 1}`) }"
                                        v-model="item.recipie_id"
                                        :name="`recipie_id${index + 1}`"
                                        v-validate="'required'"
                                        :data-vv-as="$t('Recipie')"
                                        data-vv-scope="addEditValidation"
                                        :placeholder="$t('Select')"
                                        clearable
                                        filterable
                                        :disabled="viewOnly"
                                    >
                                        <el-option
                                            v-for="option in recipieArray"
                                            :value="option.id"
                                            :label="option.name"
                                            :key="option.id"
                                        >
                                        </el-option>
                                    </el-select>
                                </td>
                                <td v-if="!viewOnly"><i @click="removeFirstGroup(index)" class="el-icon-delete"></i>
                                </td>
                            </tr>
                        </table>

                    </div>
<!--              نهاية الفورم الاول      -->
                    <div class="row mr-1">
                        <div v-if="!viewOnly" style="margin-right:auto;margin-left:15px;">
                            <el-button @click="addNewSecondGroup" type="success">اضافة مكونات المنيو غير الاساسية
                            </el-button>
                        </div>


                        <table id="details" class="mt-3">
                            <tr>
                                <th style="width:5rem">#</th>
                                <th style="width:60rem">المكون الغير الاساسي</th>
                                <th style="width:60rem">المطبخ</th>
                                <th style="width:60rem">الوصفة</th>
                                <th v-if="!viewOnly" style="width:60rem">{{ $t('Actions') }}</th>
                            </tr>
                            <tr v-for="(item, index) in addEditObj.second_group" :key="index">
                                <td>{{ index + 1 }}</td>
                                <td>
                                    <el-select
                                        :class="{ 'has-error': errors.has(`addEditValidation.group_id${index + 1}`) }"
                                        v-model="item.ingredient_id"
                                        :name="`ingredient_id${index + 1}`"
                                        data-vv-scope="addEditValidation"
                                        :placeholder="$t('Select')"
                                        clearable
                                        filterable
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
                                </td>
                                <td>
                                    <el-select
                                        :class="{ 'has-error': errors.has(`addEditValidation.cuisine_id${index + 1}`) }"
                                        v-model="item.cuisine_id"
                                        :name="`cuisine_id${index + 1}`"
                                        v-validate="'required'"
                                        :data-vv-as="$t('Cuisine')"
                                        data-vv-scope="addEditValidation"
                                        :placeholder="$t('Select')"
                                        clearable
                                        filterable
                                        :disabled="viewOnly"
                                    >
                                        <el-option
                                            v-for="option in cuisineArray"
                                            :value="option.id"
                                            :label="option.name"
                                            :key="option.id"
                                        >
                                        </el-option>
                                    </el-select>
                                </td>
                                <td>
                                    <el-select
                                        :class="{ 'has-error': errors.has(`addEditValidation.recipie_id${index + 1}`) }"
                                        v-model="item.recipie_id"
                                        :name="`recipie_id${index + 1}`"
                                        v-validate="'required'"
                                        :data-vv-as="$t('Recipie')"
                                        data-vv-scope="addEditValidation"
                                        :placeholder="$t('Select')"
                                        clearable
                                        filterable
                                        :disabled="viewOnly"
                                    >
                                        <el-option
                                            v-for="option in recipieArray"
                                            :value="option.id"
                                            :label="option.name"
                                            :key="option.id"
                                        >
                                        </el-option>
                                    </el-select>
                                </td>
                                <td v-if="!viewOnly"><i @click="removeSecondGroup(index)" class="el-icon-delete"></i>
                                </td>
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
    name: "recipiesAddEdit",
    data() {
        return {
            lang: localStorage.getItem('lang') || 'ar',
            groupArray: [],
            ingredientFirstArray: [],
            ingredientSecondArray: [],
            recipieArray: [],
            cuisineArray: [],

            id: this.$route.params.id || 0,
            viewOnly: this.$route.params.page && this.$route.params.page == 'details' ? true : false,
            addEditObj: {
                id: 0,
                cooking_date: '',
                cuisine_id: '',
                recipie_id: '',
                group_id: '',
                ingredient_id: '',
                groups: [],
                first_group: [],
                second_group: [],
            },
        };
    },
    methods: {
        addNewFirstGroup() {
            let item = {
                ingredient_id: '',
                cuisine_id: '',
                recipie_id: '',
                type: 1,
            };

            this.addEditObj.first_group.push(item)
        },
        removeFirstGroup(index) {
            this.addEditObj.first_group.splice(index, 1)
        },

        addNewSecondGroup() {
            let item = {
                ingredient_id: '',
                cuisine_id: '',
                recipie_id: '',
                type:0
            };

            this.addEditObj.second_group.push(item)
        },
        removeSecondGroup(index) {
            this.addEditObj.second_group.splice(index, 1)
        },

        saveAddEdit() {
            this.$validator.validateAll("addEditValidation").then((result) => {
                if (result) {
                    console.log(this.addEditObj);
                    if (this.id) {
                        this.$store
                            .dispatch("menus/updateData", this.addEditObj)
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
                            .dispatch("menus/saveData", this.addEditObj)
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
                    this.$router.push({name: 'menus'});
                }
            });
        },
        fillData() {
            let id = this.id
            this.$store
                .dispatch("menus/findData", id)
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
            this.$store.dispatch(`menus/listFirstGroupIngredients`).then(res => {
                this.ingredientFirstArray = res.data;
            });

            this.$store.dispatch(`menus/listSecondGroupIngredients`).then(res => {
                this.ingredientSecondArray = res.data;
            });

            this.$store.dispatch(`kitchens/listRecipies`).then(res => {
                this.recipieArray = res.data;
            });

            this.$store.dispatch(`recipies/listCuisines`).then(res => {
                this.cuisineArray = res.data;
            });

            if (this.id) this.fillData();

        },
    },
    created() {
        this.initData();
    },
};
</script>


<style lang="scss">
#ingredients {
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
