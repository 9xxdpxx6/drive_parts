<template>
    <div>
        <header class="shadow-sm bg-info align-items-start">
            <div class="menu">
                <nav class="navbar navbar-info bg-info">
                    <div class="container-fluid px-0">
                        <button @click="isMenuOpen = !isMenuOpen" class="navbar-toggler py-0 shadow-none border-0"
                                type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                            <i class="text-light" :class="menuIcon"></i>
                        </button>
                        <div class="header-right d-flex align-items-center ms-auto">
<!--                            <i class="bi bi-bell text-light fs-4 mx-3"></i>-->
                            <a @click.prevent="logout" href="#" class=" text-light fs-3 me-4"><i
                                class="bi bi-box-arrow-right"></i></a>
                        </div>
                        <div class="collapse navbar-collapse pt-3" id="navbarNav" ref="navbarNav">
                            <AppMenu  v-if="isMobile" :navbar="navbar" :is-menu-open="isMenuOpen" @update-data="isMenuOpen = $event"></AppMenu>
                        </div>
                    </div>
                </nav>
            </div>
        </header>
    </div>
</template>

<script>
import AppMenu from "./AppMenu.vue";

export default {
    name: "AppHeader",
    components: {AppMenu},

    props: {
        isMobile: {
            type: Boolean,
            required: true,
        }
    },

    data() {
        return {
            navbar: {},
            isMenuOpen: false,
        };
    },

    mounted() {
        this.navbar = this.$refs.navbarNav
    },

    methods: {
        logout() {
            axios.post('/logout')
                .then(res => {
                    localStorage.removeItem('x_xsrf_token')
                    localStorage.removeItem('user')
                    this.$router.push({name: 'user.login'})
                })
        },
    },

    computed: {
        menuIcon() {
            return this.isMenuOpen ? 'bi bi-x-lg fs-2' : 'bi bi-list fs-1';
        },
    },
}
</script>

<style scoped>

.navbar {
    transition: height 0.3s ease-in-out;
    overflow: hidden;
    height: auto;
}

.navbar.collapse {
    height: 0;
}

</style>
