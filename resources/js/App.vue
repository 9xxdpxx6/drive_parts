<template>
    <div>
        <AppHeader v-if="token" :is-mobile="isMobile"></AppHeader>

        <AppSidebar v-if="token" :is-mobile="isMobile"></AppSidebar>

        <main class="d-flex">
            <div class="plug" v-if="!isMobile"></div>
            <router-view class="p-3 py-lg-4 px-lg-5 w-100" ></router-view>
        </main>
    </div>
</template>

<script>
import AppHeader from "./components/AppHeader.vue"
import AppSidebar from "./components/AppSidebar.vue"
import router from "./router";

export default {
    name: "Index",
    components: {
        AppHeader,
        AppSidebar
    },

    data() {
        return {
            token: '',
        }
    },

    mounted() {
        this.getToken()
        document.addEventListener('visibilitychange', this.handlePageActivity)
    },

    beforeUnmount() {
        document.removeEventListener('visibilitychange', this.handlePageActivity)
    },

    updated() {
        this.getToken()
    },

    methods: {

        getToken() {
            this.token = localStorage.getItem('x_xsrf_token')
        },

        handlePageActivity() {
            if (document.visibilityState === 'visible') {
                if (!this.token && this.$route.name !== 'user.login') {
                    this.$router.replace({name: 'user.login'})
                }
            }
        },

    },

    computed: {
        isMobile() {
            return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)
        },
    }
}
</script>

<style scoped>

.plug {
    width: 60px;
    height: 100%;
}
main {
    width: 100% !important;
}

</style>
