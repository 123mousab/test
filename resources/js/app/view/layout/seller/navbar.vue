<template>
  <div class="header-container fixed-top">
        <header class="header navbar navbar-expand-sm">
            <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg></a>
            <ul class="navbar-item flex-row search-ul">
                <li class="nav-item dropdown user-profile-dropdown  order-lg-0 order-1">
                    <a class="nav-link dropdown-toggle user cursor" 
                    id="userProfileDropdown" data-toggle="dropdown" aria-haspopup="true" 
                    aria-expanded="false">
                       <span style="font-size:16px;font-weight:bold;">
                           {{$t('Hi')}}{{user.name}}
                        </span>
                    </a>
                </li>
            </ul>
            <ul class="navbar-item flex-row">
                <li class="nav-item dropdown user-profile-dropdown  order-lg-0 order-1">
                    <b-button @click="logout" variant="danger">{{$t('Logout')}}</b-button>
                </li>
            </ul>
        </header>
    </div>
</template>

<script>
export default {
    data() {
      return {
            user: localStorage.getItem('user')? JSON.parse(localStorage.getItem('user')): null,
      }
    },
    methods: {
        logout() {
        this.$notify.success({
            duration: 3000,
            message: this.$t("LogoutSuccessfully"),
            title: this.$t("Logout"),
            customClass: "top-center",
        });
        localStorage.removeItem('user');
        localStorage.removeItem('token');
        this.$store
                .dispatch("auth/logout")
                .then(_ => {
                    window.location.href= "/";
                })
                .catch((error) => {
                    window.location.href= "/";
                })
        },
    }
}
</script>

<style>

</style>