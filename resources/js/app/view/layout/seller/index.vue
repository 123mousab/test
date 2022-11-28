<template>
  <div>
    <!-- Auth -->
    <div v-if="isLogin">
    <!--  BEGIN NAVBAR  -->
    <navbar />
    <!--  END NAVBAR  -->

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">
      <div class="overlay"></div>
      <div class="search-overlay"></div>

      <!--  BEGIN SIDEBAR  -->
      <div class="sidebar-wrapper sidebar-theme">
        <nav id="sidebar">
          <ul class="navbar-nav theme-brand flex-row text-center">
            <li class="nav-item theme-logo">
              <router-link to="/">
                <img
                  src="/assets/images/logo/aseam.jpeg"
                  class="navbar-logo"
                  alt="logo"
                />
              </router-link>
            </li>
          </ul>
          <!-- <div class="shadow-bottom"></div> -->

            <!-- SideBar -->

          <ul class="list-unstyled menu-categories" id="accordionExample">

              <li class="menu" :class="$route.name == 'seller' ? 'active' : ''">
              <router-link to="/" aria-expanded="false" class="dropdown-toggle">
                <div class="">
                  <HomeIcon/>
                  <span>{{$t('Home')}}</span>
                </div>
              </router-link>
            </li>

              <li v-if="roles.includes('constant')" class="menu">
                  <a href="#components" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                      <div class="">
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-box"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                          <span>ثوابت النظام</span>
                      </div>
                      <div>
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                      </div>
                  </a>
                  <ul class="collapse submenu list-unstyled" id="components" data-parent="#accordionExample">
                      <li>
                          <router-link to="/divisions">الاقسام</router-link>
                      </li>
                      <li>
                          <router-link to="/groups">المجموعات</router-link>
                      </li>
                      <li>
                          <router-link to="/nutrition_facts">الحقائق الغذائية</router-link>
                      </li>
                      <li>
                          <router-link to="/cuisines">المطابخ</router-link>
                      </li>
                      <li>
                          <router-link to="/tools">الادوات</router-link>
                      </li>
                      <li>
                          <router-link to="/units">الوحدات</router-link>
                      </li>

                       <li>
                          <router-link to="/countries">الدول</router-link>
                      </li>
                      <li>
                          <router-link to="/cities">المدن</router-link>
                      </li>
                      <li>
                          <router-link to="/branches">الاحياء</router-link>
                      </li>
                      <li>
                          <router-link to="/packages">الباقات</router-link>
                      </li>
                      <li>
                          <router-link to="/periods">فترات العمل</router-link>
                      </li>
                      <li>
                          <router-link to="/bank_names">البنوك</router-link>
                      </li>
                      <li>
                          <router-link to="/company">الشركات</router-link>
                      </li>
                      <li>
                          <router-link to="/group_names">مجموعات الاشخاص</router-link>
                      </li>


                  </ul>
              </li>

              <li v-if="roles.includes('store')" class="menu">
                  <a href="#components1" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                      <div class="">
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-zap"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                          <span>المطعم</span>
                      </div>
                      <div>
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                      </div>
                  </a>
                  <ul class="collapse submenu list-unstyled" id="components1" data-parent="#accordionExample">
                      <li>
                          <router-link to="/ingredients">المكونات الغذائية</router-link>
                      </li>
                      <li>
                          <router-link to="/recipies">الوصفات</router-link>
                      </li>
                      <li>
                          <router-link to="/menus">منيو المطبخ</router-link>
                      </li>
                  </ul>
              </li>

              <li v-if="roles.includes('orders')" class="menu">
                  <a href="#components2" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                      <div class="">
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-zap"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                          <span>المبيعات</span>
                      </div>
                      <div>
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                      </div>
                  </a>
                  <ul class="collapse submenu list-unstyled" id="components2" data-parent="#accordionExample">
                      <li>
                          <router-link to="/subscribe">الاشتراكات</router-link>
                      </li>
                  </ul>
              </li>


            <li  v-if="roles.includes('users')" class="menu">
              <router-link to="/users" aria-expanded="false" class="dropdown-toggle">
                <div class="">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-zap"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                  <span>المستخدمين</span>
                </div>
              </router-link>
            </li>




                <li v-if="roles.includes('reports')" class="menu">
                  <a href="#components3" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                      <div class="">
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-zap"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                          <span>التقارير</span>
                      </div>
                      <div>
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                      </div>
                  </a>
                  <ul class="collapse submenu list-unstyled" id="components3" data-parent="#accordionExample">
                      <li>
                          <!-- <a @click="downloadCookingToday" class="clickable">الطبخ اليومي</a> -->
                          <a @click="openCookingToday" class="clickable">الطبخ اليومي</a>
                      </li>
                  </ul>
                   <ul class="collapse submenu list-unstyled" id="components3" data-parent="#accordionExample">
                      <li>
                          <!-- <a @click="downloadCookingToday" class="clickable">الطبخ اليومي</a> -->
                          <a @click="openDelegateToday" class="clickable">المندوب اليومي</a>
                      </li>
                  </ul>
                   <ul class="collapse submenu list-unstyled" id="components3" data-parent="#accordionExample">
                      <li>
                          <!-- <a @click="downloadCookingToday" class="clickable">الطبخ اليومي</a> -->
                          <a @click="openQuantitiesToday" class="clickable">الكميات اليومي</a>
                      </li>
                  </ul>
              </li>







<!--              <li class="menu" :class="$route.name == 'recipies' ? 'active' : ''">
                  <router-link
                      to="/recipies"
                      aria-expanded="false"
                      class="dropdown-toggle"
                  >
                      <div class="">
                          <ItemsIcon />
                          <span>{{$t('Recipies')}}</span>
                      </div>
                  </router-link>
              </li>-->

          </ul>

        </nav>
      </div>
      <!--  END SIDEBAR  -->

      <!--  BEGIN CONTENT AREA  -->
      <div id="content" class="main-content">
        <div class="layout-px-spacing">
           <h4 v-if="$route.path == '/'" class="mt-4 mx-1">أهلا بك في نظام V-Shape</h4>
          <router-view />
        </div>
        <!--  End CONTENT AREA  -->

        <!-- footer -->
<!--        <div class="footer-wrapper">
          <div class="footer-section f-section-1">
            <p class="">Copyright © 2021 , All rights reserved.</p>
          </div>
          <div class="footer-section f-section-2">
            <p class="">
              Coded with S2M
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="feather feather-heart"
              >
                <path
                  d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"
                ></path>
              </svg>
            </p>
          </div>
        </div>-->

      </div>
      <!--  END CONTENT AREA  -->
    </div>
    <!-- END MAIN CONTAINER -->
  </div>

  <div v-else>
       <section class="col-md-6 my-5 offset-md-3">

      <div class="card shadow p-5"
      style="position: relative;
    right: 50%;"
      >
        <div>
          <div style="text-align:center;" class="mb-4">
            <img  width="400" height="150" src="/assets/images/logo/aseam.jpeg" alt="">
          </div>
          <h3 class="text-center text-uppercase mb-4">تسجيل دخول</h3>
          <hr>

          <div class="form-group">
            <label>الإيميل أو الموبايل</label>
            <input v-model="loginObj.email" type="text"  class="form-control">
          </div>

          <label for="Password">كلمة المرور</label>
          <div class="input-group mb-3">
            <input v-model="loginObj.password" type="password" name="password" id="password" class="form-control" >
          </div>

          <button
           class="btn btn-block btn-primary rounded-pill mt-3"
           @click="login"
          >
            دخول
          </button>
        </div>
      </div>
    </section>
  </div>

  </div>
</template>

<script>
import navbar from "./navbar";
import HomeIcon from "../../icons/home";
import CategoriesIcon from "../../icons/categories";
import CustomersIcon from "../../icons/customers";
import ItemsIcon from "../../icons/items";
import CouponsIcon from "../../icons/coupons";
import RatesIcon from "../../icons/rates";
import PagesIcon from "../../icons/pages";
import OrdersIcon from "../../icons/orders";
import SettingsIcon from "../../icons/settings";
import ContactUsIcon from "../../icons/contactUs";
export default {
   components: {
    HomeIcon,
    CategoriesIcon,
    CustomersIcon,
    ItemsIcon,
    CouponsIcon,
    RatesIcon,
    PagesIcon,
    OrdersIcon,
    SettingsIcon,
    ContactUsIcon,
    navbar,
  },
  data() {
    return {
      isLogin: localStorage.getItem('token') || false,
      loginObj: {
        email: '',
        password: ''
      },
      roles: [],
    }
  },
  methods: {
    login() {
       this.$store
              .dispatch("auth/login", this.loginObj)
              .then(res => {
                this.$notify.success({
                  duration: 3000,
                  message: this.$t("LoginSuccessfully"),
                  title: this.$t("Login"),
                  customClass: "top-center",
                });
                localStorage.setItem('user',JSON.stringify(res.data.user));
                localStorage.setItem('token',res.data.token);
                window.location.href= "/";
              })
              .catch((error) => {
                this.$notify.error({
                  duration: 3000,
                  message: this.$t("LoginFailed"),
                  title: this.$t("Login"),
                  customClass: "top-center",
                });
              })
              .finally(() => {
              });
    },
    downloadCookingToday() {
      this.$store.dispatch(`reports/downloadCookingTodayPDFReport`).then(() => {
      });
    },
    openCookingToday() {
      window.open('/reports/cookingToday');
    },
    openDelegateToday() {
      window.open('/reports/delegateToday');
    },
    openQuantitiesToday() {
      window.open('/reports/quantitiesToday');
    }
  },
  created() {
    let user = localStorage.getItem('user')? JSON.parse(localStorage.getItem('user')) : null;
    if(user)
    this.roles= user.roles ;
  }
};
</script>

<style>
#sidebar .theme-brand li.theme-logo img {
  width: 200px;
  height: 80px;
  margin-top: 20px;
  border-radius: 5px;
}
</style>
