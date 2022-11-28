<template>
    <form ref="form" @submit.stop.prevent="saveAddEdit">

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
                        :disabled="viewMode"
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
                        :disabled="viewMode"
                    />
                    <div
                        class="help-block"
                        v-if="errors.has('addEditValidation.nameEn')"
                    >
                        {{ errors.first("addEditValidation.nameEn") }}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div
                    class="form-group"
                    :class="{ 'has-error': errors.has('addEditValidation.country_id') }"
                >
                    <label class="control-label">الدولة</label>
                    <el-select
                        v-model="addEditObj.country_id"
                        v-validate="'required'"
                        name="country_id"
                        v-on:change="listCities(addEditObj.country_id)"
                        :data-vv-as="$t('Countries')"
                        data-vv-scope="addEditValidation"
                        :placeholder="$t('Select')"
                        clearable
                        filterable
                        :disabled="viewOnly"
                    >
                        <el-option
                            v-for="option in countriesArray"
                            :value="option.id"
                            :label="option.name"
                            :key="option.id"
                        >
                        </el-option>
                    </el-select>
                    <div
                        class="help-block"
                        v-if="errors.has('addEditValidation.country_id')"
                    >
                        {{ errors.first("addEditValidation.country_id") }}
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div
                    class="form-group"
                    :class="{ 'has-error': errors.has('addEditValidation.city_id') }"
                >
                    <label class="control-label">المدينة</label>
                    <el-select
                        v-model="addEditObj.city_id"
                        v-validate="'required'"
                        name="city_id"
                        :data-vv-as="$t('Cities')"
                        data-vv-scope="addEditValidation"
                        :placeholder="$t('Select')"
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
                        v-if="errors.has('addEditValidation.city_id')"
                    >
                        {{ errors.first("addEditValidation.city_id") }}
                    </div>
                </div>
            </div>
        </div>


        <button type="button" class="btn c-ml-2 mb-2 btn-icon btn-secondary float-left"
        @click="$emit('closeAddEdit')">
            {{$t("Close")}}
        </button>
        <b-button type="primary" variant="primary" class="float-left ml-2">
            {{$t("Save")}}
        </b-button>

    </form>
</template>

<script>

export default {
    name: 'citiesAddEdit',
    props:['items', 'viewMode', 'editMode', 'idSelected'],
    data() {
        return {
            countriesArray: [],
            citiesArray: [],
            lang: localStorage.getItem('lang') || 'ar',
            addEditObj:{
                id:0,
                name: {
                    ar: '',
                    en: ''
                },
                country_id: '',
                city_id: '',
            },
            button: {
                loading: false,
                'dataStyle': 'zoom-out',
                progress: 0,
            },
            type:"text"
        }
    },
    methods: {
        saveAddEdit(){
            this.$validator.validateAll("addEditValidation").then((result) => {
                if (result) {
                    this.button.loading=true;
                    if(this.addEditObj.id>0){
                        this.$store.dispatch("branches/updateData", this.addEditObj)
                        .then(res => {
                            this.$notify.success({
                                duration: 3000,
                                message: this.$t("UpdatedSuccessfully"),
                                title: this.$t("Updated"),
                                customClass: "top-center",
                            });
                             this.$emit("saveAddEdit");
                        })
                        .catch(_ => {
                            this.$notify.error({
                                duration: 3000,
                                message: this.$t("UpdatedFailed"),
                                title: this.$t("Updated"),
                                customClass: "top-center",
                            });
                        })
                    }
                    else{
                        this.$store.dispatch("branches/saveData", this.addEditObj)
                        .then(res => {
                            this.$notify.success({
                                duration: 3000,
                                message: this.$t("AddedSuccessfully"),
                                title: this.$t("Added"),
                                customClass: "top-center",
                            });
                             this.$emit("saveAddEdit");
                        })
                        .catch(_ => {
                            this.$notify.error({
                                duration: 3000,
                                message: this.$t("AddedFailed"),
                                title: this.$t("Added"),
                                customClass: "top-center",
                            });
                        })
                    }
                }
            });
        },
        listCities(countryID)
        {
            this.$store.dispatch(`branches/listCities`, countryID).then(res => {
                this.citiesArray= res.data;
            });
        }
    },
    created() {
        this.$store.dispatch(`cities/listCountries`).then(res => {
            this.countriesArray= res.data;
        });

        this.$store.dispatch(`branches/listAllCities`).then(res => {
            this.citiesArray= res.data;
        });

        if(!this.editMode && !this.viewMode) return;
        this.$store.dispatch(`branches/findData`, this.idSelected).then(res=>{
            this.addEditObj =  JSON.parse(JSON.stringify(res.data));
        })
    }

}
</script>
