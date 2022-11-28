<template>
    <div>

        <!-- راوتر التنقل -->
        <div class="mt-3">
            <label>
                <router-link to="/">{{ $t("Home") }}</router-link>
            </label>
            <span>/</span>
            <label>
                <router-link to="/recipies">{{ $t("Recipies") }}</router-link>
            </label>
            <span>/</span>

            <label active>
                <span v-if="id && !viewOnly">{{ $t("Edit") }}</span>
                <span v-else-if="viewOnly">{{ $t("Details") }}</span>
                <span v-else>{{ $t("AddNewRecipies") }}</span>
            </label>
        </div>

        <!-- العنوان الرئيسي -->
        <h4 class="mt-3">
            <span v-if="id && !viewOnly">{{ $t("Edit") }}</span>
            <span v-else-if="viewOnly">{{ $t("Details") }}</span>
            <span v-else>{{ $t("AddNewRecipies") }}</span>
        </h4>


        <div class="mt-3">

            <div ref="form">
                <b-card>
                    <div class="row">

                        <div class="col-md-4">
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

<!--                        <div class="col-md-4">
                            <div
                                class="form-group"
                            >
                                <label class="control-label">النوع</label>
                                <select name="parent" class="form-control" v-model="selected" @change="clear()" required>
                                    <option value="" selected></option>
                                    <option value="item1" >تابع لميزة</option>
                                    <option value="item2">تابع لمكون اساسي</option>
                                </select>
                            </div>
                        </div>-->

                        <div class="col-md-4"">

                            <label class="control-label">الميزات</label>
                            <el-select
                                v-model="addEditObj.group_id"
                                name="group_id"
                                :data-vv-as="$t('Groups')"
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
                        </div>

                        <div class="col-md-4">
                            <label class="control-label">المكون الاساسي</label>
                            <el-select
                                v-model="addEditObj.ingredient_primary_id"
                                name="ingredient_primary_id"
                                data-vv-scope="addEditValidation"
                                :placeholder="$t('Select')"
                                clearable
                                filterable
                                :disabled="viewOnly"
                            >
                                <el-option
                                    v-for="option in ingredientPrimaryArray"
                                    :value="option.id"
                                    :label="option.name"
                                    :key="option.id"
                                >
                                </el-option>
                            </el-select>
                            <div>
                                <b-form-checkbox
                                    id="checkbox-1"
                                    v-model="addEditObj.protein"
                                    name="checkbox-1"
                                    value="1"
                                    unchecked-value="0"
                                >
                                    بروتين
                                </b-form-checkbox>
                            </div>
                            <div>
                                <b-form-checkbox
                                    id="checkbox-2"
                                    v-model="addEditObj.carb"
                                    name="checkbox-1"
                                    value="1"
                                    unchecked-value="0"
                                >
                                    كارب
                                </b-form-checkbox>
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
                                :class="{ 'has-error': errors.has('addEditValidation.tool_id') }"
                            >
                                <label class="control-label">{{ $t("Tools") }}</label>
                                <el-select
                                    v-model="addEditObj.tool_id"
                                    v-validate=""
                                    name="tool_id"
                                    :data-vv-as="$t('Tools')"
                                    data-vv-scope="addEditValidation"
                                    :placeholder="$t('Select')"
                                    clearable
                                    filterable
                                    multiple
                                    :disabled="viewOnly"
                                >
                                    <el-option
                                        v-for="option in toolArray"
                                        :value="option.id"
                                        :label="option.name"
                                        :key="option.id"
                                    >
                                    </el-option>
                                </el-select>
                                <div
                                    class="help-block"
                                    v-if="errors.has('addEditValidation.tool_id')"
                                >
                                    {{ errors.first("addEditValidation.tool_id") }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div
                                class="form-group"
                                :class="{ 'has-error': errors.has('addEditValidation.cuisine_id') }"
                            >
                                <label class="control-label">{{ $t("Cuisines") }}</label>
                                <el-select
                                    v-model="addEditObj.cuisine_id"
                                    v-validate="'required'"
                                    name="cuisine_id"
                                    :data-vv-as="$t('Cuisines')"
                                    data-vv-scope="addEditValidation"
                                    :placeholder="$t('Select')"
                                    clearable
                                    filterable
                                    multiple
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
                                <div
                                    class="help-block"
                                    v-if="errors.has('addEditValidation.cuisine_id')"
                                >
                                    {{ errors.first("addEditValidation.cuisine_id") }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mr-1">
                        <div v-if="!viewOnly" style="margin-right:auto;margin-left:15px;">
                            <el-button @click="addNewIngredient" type="success">اضافة مكونات غير اساسية</el-button>
                        </div>


                        <table id="ingredients" class="mt-3">
                            <tr>
                                <th style="width:5rem">#</th>
                                <th style="width:60rem">{{ $t('Name') }}</th>
                                <th style="width:15rem">{{ $t('Quantity') }}</th>
                                <th v-if="!viewOnly" style="width:60rem">{{ $t('Actions') }}</th>
                            </tr>
                            <tr v-for="(item, index) in addEditObj.ingredient" :key="index">
                                <td>{{ index + 1 }}</td>
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
                                            v-for="option in ingredientArray"
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
                                <td v-if="!viewOnly"><i @click="removeIngredient(index)" class="el-icon-delete"></i>
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
            selected: '',
            lang: localStorage.getItem('lang') || 'ar',
            groupArray: [],
            toolArray: [],
            cuisineArray: [],
            ingredientArray: [],
            ingredientPrimaryArray: [],

            id: this.$route.params.id || 0,
            viewOnly: this.$route.params.page && this.$route.params.page == 'details' ? true : false,
            addEditObj: {
                id: 0,
                name: {
                    ar: '',
                    en: ''
                },
                description: {
                    ar: '',
                    en: ''
                },
                cuisine_id: '',
                tool_id: '',
                protein: 0,
                carb: 0,
                group_id: '',
                ingredient_primary_id: '',
                ingredient: [],
            },
        };
    },
    methods: {
        addNewIngredient() {
            let item = {
                id: '',
                quantity: ''
            };

            this.addEditObj.ingredient.push(item)
        },

        /*clear()
        {
            this.addEditObj.group_id = '';
            this.addEditObj.ingredient_primary_id = '';
            this.addEditObj.protein = 0;
            this.addEditObj.carb = 0;
        },*/

        removeIngredient(index) {
            this.addEditObj.ingredient.splice(index, 1)
        },

        saveAddEdit() {
            this.$validator.validateAll("addEditValidation").then((result) => {
                if (result) {
                    console.log(this.addEditObj);
                    if (this.id) {
                        this.$store
                            .dispatch("recipies/updateData", this.addEditObj)
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
                            .dispatch("recipies/saveData", this.addEditObj)
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
                    this.$router.push({name: 'recipies'});
                }
            });
        },
        fillData() {
            let id = this.id
            this.$store
                .dispatch("recipies/findData", id)
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
                this.groupArray = res.data;
            });
            this.$store.dispatch(`recipies/listTools`).then(res => {
                this.toolArray = res.data;
            });
            this.$store.dispatch(`recipies/listCuisines`).then(res => {
                this.cuisineArray = res.data;
            });
            this.$store.dispatch(`recipies/listIngredients`).then(res => {
                this.ingredientArray = res.data;
            });
            this.$store.dispatch(`recipies/listPrimaryIngredients`).then(res => {
                this.ingredientPrimaryArray = res.data;
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
