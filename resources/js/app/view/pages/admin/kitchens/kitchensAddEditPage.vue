<template>
    <div>

        <!-- راوتر التنقل -->
        <div class="mt-3">
            <label>
                <router-link to="/">{{ $t("Home") }}</router-link>
            </label>
            <span>/</span>
            <label>
                <router-link to="/kitchens">{{ $t("Kitchens") }}</router-link>
            </label>
            <span>/</span>

            <label active>
                <span v-if="id && !viewOnly">{{ $t("Edit") }}</span>
                <span v-else-if="viewOnly">{{ $t("Details") }}</span>
                <span v-else>{{ $t("AddNewKitchens") }}</span>
            </label>
        </div>

        <!-- العنوان الرئيسي -->
        <h4 class="mt-3">
            <span v-if="id && !viewOnly">{{ $t("Edit") }}</span>
            <span v-else-if="viewOnly">{{ $t("Details") }}</span>
            <span v-else>{{ $t("AddNewKitchens") }}</span>
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
                                <label class="control-label">{{ $t("Cooking Date") }}</label>
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
                    <div class="row mr-1">
                        <div v-if="!viewOnly" style="margin-right:auto;margin-left:15px;">
                            <el-button @click="addNewCookingDetails" type="success">{{ $t('addNewCookingDetails') }}
                            </el-button>
                        </div>


                        <table id="details" class="mt-3">
                            <tr>
                                <th style="width:5rem">#</th>
                                <th style="width:60rem">{{ $t('Group') }}</th>
                                <th style="width:60rem">{{ $t('Cuisine') }}</th>
                                <th style="width:60rem">{{ $t('Recipie') }}</th>
                                <th v-if="!viewOnly" style="width:60rem">{{ $t('Actions') }}</th>
                            </tr>
                            <tr v-for="(item, index) in addEditObj.groups" :key="index">
                                <td>{{ index + 1 }}</td>
                                <td>
                                    <el-select
                                        :class="{ 'has-error': errors.has(`addEditValidation.group_id${index + 1}`) }"
                                        v-model="item.group_id"
                                        :name="`group_id${index + 1}`"
                                        v-validate="'required'"
                                        :data-vv-as="$t('Group')"
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
                                <td v-if="!viewOnly"><i @click="removeCookingDetails(index)" class="el-icon-delete"></i>
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
                groups: [],
            },
        };
    },
    methods: {
        addNewCookingDetails() {
            let item = {
                group_id: '',
                cuisine_id: '',
                recipie_id: ''
            };

            this.addEditObj.groups.push(item)
        },
        removeCookingDetails(index) {
            this.addEditObj.groups.splice(index, 1)
        },

        saveAddEdit() {
            this.$validator.validateAll("addEditValidation").then((result) => {
                if (result) {
                    console.log(this.addEditObj);
                    if (this.id) {
                        this.$store
                            .dispatch("kitchens/updateData", this.addEditObj)
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
                            .dispatch("kitchens/saveData", this.addEditObj)
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
                    this.$router.push({name: 'kitchens'});
                }
            });
        },
        fillData() {
            let id = this.id
            this.$store
                .dispatch("kitchens/findData", id)
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
