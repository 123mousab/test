<template>
  <form ref="form" @submit.stop.prevent="saveAddEdit">
    <div class="row">
      <div class="col-md-3">
        <div
          class="form-group"
          :class="{ 'has-error': errors.has('addEditValidation.name') }"
        >
          <label class="control-label">{{ $t("Name") }}</label>
          <input
            type="text"
            name="name"
            class="form-control"
            v-validate="'required'"
            data-vv-scope="addEditValidation"
            :data-vv-as="$t('Name')"
            v-model="addEditObj.name"
            :disabled="viewMode"
          />
          <div class="help-block" v-if="errors.has('addEditValidation.name')">
            {{ errors.first("addEditValidation.name") }}
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div
          class="form-group"
          :class="{ 'has-error': errors.has('addEditValidation.email') }"
        >
          <label class="control-label">{{ $t("Email") }}</label>
          <input
            type="text"
            name="email"
            class="form-control"
            v-validate="'required|email'"
            data-vv-scope="addEditValidation"
            :data-vv-as="$t('Email')"
            v-model="addEditObj.email"
            :disabled="viewMode"
          />
          <div class="help-block" v-if="errors.has('addEditValidation.email')">
            {{ errors.first("addEditValidation.email") }}
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div
          class="form-group"
          :class="{ 'has-error': errors.has('addEditValidation.password') }"
        >
          <label class="control-label">{{ $t("Password") }}</label>
          <input
            type="password"
            name="password"
            class="form-control"
            v-validate="'required'"
            data-vv-scope="addEditValidation"
            :data-vv-as="$t('Password')"
            v-model="addEditObj.password"
            :disabled="viewMode"
          />
          <div
            class="help-block"
            v-if="errors.has('addEditValidation.password')"
          >
            {{ errors.first("addEditValidation.password") }}
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div
          class="form-group"
          :class="{
            'has-error': errors.has('addEditValidation.confirm_password'),
          }"
        >
          <label class="control-label">{{ $t("ConfirmPassword") }}</label>
          <input
            type="password"
            name="confirm_password"
            class="form-control"
            v-validate="'required'"
            data-vv-scope="addEditValidation"
            :data-vv-as="$t('ConfirmPassword')"
            v-model="addEditObj.confirm_password"
            :disabled="viewMode"
          />
          <div
            class="help-block"
            v-if="errors.has('addEditValidation.confirm_password')"
          >
            {{ errors.first("addEditValidation.confirm_password") }}
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div
          class="form-group"
          :class="{ 'has-error': errors.has('addEditValidation.roles') }"
        >
          <label class="control-label">{{ $t("Roles") }}</label>
          <el-select
            v-model="addEditObj.roles"
            v-validate=""
            name="roles"
            :data-vv-as="$t('Roles')"
            data-vv-scope="addEditValidation"
            :placeholder="$t('Select')"
            clearable
            filterable
            multiple
            :disabled="viewMode"
          >
            <el-option
              v-for="option in roleArray"
              :value="option.id"
              :label="option.name"
              :key="option.id"
            >
            </el-option>
          </el-select>

          <div class="help-block" v-if="errors.has('addEditValidation.roles')">
            {{ errors.first("addEditValidation.roles") }}
          </div>
        </div>
      </div>
    </div>

    <button
      type="button"
      class="btn c-ml-2 mb-2 btn-icon btn-secondary float-left"
      @click="$emit('closeAddEdit')"
    >
      {{ $t("Close") }}
    </button>
    <b-button type="primary" variant="primary" class="float-left ml-2">
      {{ $t("Save") }}
    </b-button>
  </form>
</template>

<script>
export default {
  name: "usersAddEdit",
  props: ["items", "viewMode", "editMode", "idSelected"],
  data() {
    return {
      lang: localStorage.getItem("lang") || "ar",
      roleArray: [],
      addEditObj: {
        id: 0,
        name: "",
        email: "",
        password: "",
        confirm_password: "",
        roles: [],
      },
      button: {
        loading: false,
        dataStyle: "zoom-out",
        progress: 0,
      },
      type: "text",
    };
  },
  methods: {
    saveAddEdit() {
      this.$validator.validateAll("addEditValidation").then((result) => {
        if (result) {
          this.button.loading = true;
          if (this.addEditObj.id > 0) {
            this.$store
              .dispatch("users/updateData", this.addEditObj)
              .then((res) => {
                this.$notify.success({
                  duration: 3000,
                  message: this.$t("UpdatedSuccessfully"),
                  title: this.$t("Updated"),
                  customClass: "top-center",
                });
                this.$emit("saveAddEdit");
              })
              .catch((_) => {
                this.$notify.error({
                  duration: 3000,
                  message: this.$t("UpdatedFailed"),
                  title: this.$t("Updated"),
                  customClass: "top-center",
                });
              });
          } else {
            this.$store
              .dispatch("users/saveData", this.addEditObj)
              .then((res) => {
                this.$notify.success({
                  duration: 3000,
                  message: this.$t("AddedSuccessfully"),
                  title: this.$t("Added"),
                  customClass: "top-center",
                });
                this.$emit("saveAddEdit");
              })
              .catch((_) => {
                this.$notify.error({
                  duration: 3000,
                  message: this.$t("AddedFailed"),
                  title: this.$t("Added"),
                  customClass: "top-center",
                });
              });
          }
        }
      });
    },
  },
  created() {
    this.$store.dispatch(`roles/getData`, this.idSelected).then((res) => {
        this.roleArray= res.data;
    });
    if (!this.editMode && !this.viewMode) return;
    this.$store.dispatch(`users/findData`, this.idSelected).then((res) => {
      this.addEditObj = JSON.parse(JSON.stringify(res.data));
    });
  },
};
</script>
