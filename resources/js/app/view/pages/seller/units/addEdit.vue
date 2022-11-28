<template>
    <form ref="form" @submit.stop.prevent="saveAddEdit">

        <div class="row">
            <div class="col-md-6">
                <div class="form-group"
                    :class="{'has-error':errors.has('addEditValidation.nameAr')}"
                >
                    <label class="control-label">{{$t("NameAr")}}</label>
                    <label v-if="lang == 'ar'" class="required">*</label>
                    <input type="text" name="nameAr"
                        class="form-control"
                        v-model="addEditObj.name['ar']"
                        v-validate="lang == 'ar'? 'required': ''"
                        data-vv-scope="addEditValidation"
                        :data-vv-as="$t('NameAr')"
                        :disabled="viewMode"
                    >
                    <div
                        class="help-block"
                        v-if="errors.has('addEditValidation.nameAr')">
                        {{ errors.first('addEditValidation.nameAr') }}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group"
                    :class="{'has-error':errors.has('addEditValidation.nameEn')}"
                >
                    <label class="control-label">{{$t("NameEn")}}</label>
                    <label v-if="lang == 'en'" class="required">*</label>
                    <input type="text" name="nameEn"
                        class="form-control"
                        v-model="addEditObj.name['en']"
                        v-validate="lang == 'en'? 'required': ''"
                        data-vv-scope="addEditValidation"
                        :data-vv-as="$t('NameEn')"
                        :disabled="viewMode"
                    >
                    <div
                        class="help-block"
                        v-if="errors.has('addEditValidation.nameEn')">
                        {{ errors.first('addEditValidation.nameEn') }}
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
    name: 'itemsAddEdit',
    props:['items', 'viewMode', 'editItem'],
    data() {
        return {
            button: {
                loading: false,
                'dataStyle': 'zoom-out',
                progress: 0,
            },
            lang: localStorage.getItem("lang") || 'ar',
            addEditObj: {
                name: {
                    ar: '',
                    en: '',
                }
            },
        }
    },
    methods: {
        saveAddEdit(){
            this.$validator.validateAll("addEditValidation").then((result) => {
                if (result) {
                    this.button.loading=true;
                    if(this.addEditObj.id>0){
                        this.$store.dispatch("units/updateData", this.addEditObj)
                        .then(res => {
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
                        this.$store.dispatch("units/saveData", this.addEditObj)
                        .then(res => {
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
    },
    created() {
        if(!this.editItem) return;
        this.addEditObj = this.editItem;
    }
}
</script>
