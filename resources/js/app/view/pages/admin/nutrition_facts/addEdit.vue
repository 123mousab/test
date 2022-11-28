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
    name: 'toolsAddEdit',
    props:['items', 'viewMode', 'editMode', 'idSelected'],
    data() {
        return {
            lang: localStorage.getItem('lang') || 'ar',
            addEditObj:{
                id:0,
                name: {
                    ar: '',
                    en: ''
                },
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
                        this.$store.dispatch("nutrition_facts/updateData", this.addEditObj)
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
                        this.$store.dispatch("nutrition_facts/saveData", this.addEditObj)
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
    },
    created() {
        if(!this.editMode && !this.viewMode) return;
        this.$store.dispatch(`nutrition_facts/findData`, this.idSelected).then(res=>{
            this.addEditObj =  JSON.parse(JSON.stringify(res.data));
        })
    }

}
</script>
