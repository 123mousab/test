<template>
    <div>
    <div class="mt-3">
        <label>
            <router-link to="/">{{$t('Home')}}</router-link>
        </label>
        <span>/</span>
    </div>
        <h4 class="mt-3">{{$t('لا يوجد لديك صلاحية')}}</h4>
    </div>
</template>
<script>
    export default {
        name: '',
        data() {
            return {
            }
        }
    }
</script>
