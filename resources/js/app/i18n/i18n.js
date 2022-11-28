import Vue from "vue";
import VueI18n from "vue-i18n";
import i18nData from "./i18nData";
import modules from "./modules";

Vue.use(VueI18n);

export default new VueI18n({
    locale: "ar",
    messages: i18nData,
    silentTranslationWarn: true,
});

i18nData.ar = {
    ...modules.base.ar,
};
i18nData.en = {
    ...modules.base.en,
};
