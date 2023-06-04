<template>
    <div>
        <AppHeader></AppHeader>

        <aside v-if="token" id="sidebar" class="position-fixed h-100 top-0">
            <div class="sidebar-title">
                <div class="sidebar-brand d-flex justify-content-between align-items-center text-secondary">
                    <span class="fs-4 text-white ps-3">
                        <span style="color: #3751FF;">DRIVE</span>
                        <span style="color: #6C757D;">PARTS</span>
                    </span>
                    <img src="/assets/logo.svg" class="me-2" height="45" alt="Logo">
                </div>
            </div>

            <ul class="sidebar-list list-unstyled">
                <li class="sidebar-list-item">
                    <router-link to="/"
                                 class="d-flex justify-content-between align-items-center text-decoration-none text-secondary mb-2 mx-3">
                        <span class="fs-4">Статистика</span>
                        <i class="bi bi-bar-chart-line fs-2"></i>
                    </router-link>
                </li>
                <li class="sidebar-list-item">
                    <router-link to="/"
                                 class="d-flex justify-content-between align-items-center text-decoration-none text-secondary my-2 mx-3">
                        <span class="fs-4">Заказы</span>
                        <i class="bi bi-cart fs-2"></i>
                    </router-link>
                </li>
                <li class="sidebar-list-item">
                    <router-link :to="{name: 'category.create'}"
                                 class="d-flex justify-content-between align-items-center text-decoration-none text-secondary my-2 mx-3">
                        <span class="fs-4">Товары</span>
                        <i class="bi bi-boxes fs-2"></i>
                    </router-link>
                </li>
                <li class="sidebar-list-item">
                    <router-link :to="{name: 'category.index'}"
                                 class="d-flex justify-content-between align-items-center text-decoration-none text-secondary my-2 mx-3">
                        <span class="fs-4">Категории</span>
                        <i class="bi bi-grid fs-2"></i>
                    </router-link>
                </li>
                <li class="sidebar-list-item">
                    <router-link to="/"
                                 class="d-flex justify-content-between align-items-center text-decoration-none text-secondary my-2 mx-3">
                        <span class="fs-4">Производители</span>
                        <i class="bi bi-bookmarks fs-2"></i>
                    </router-link>
                </li>
                <li class="sidebar-list-item">
                    <router-link to="/qwe"
                                 class="d-flex justify-content-between align-items-center text-decoration-none text-secondary my-2 mx-3">
                        <span class="fs-4">Клиенты</span>
                        <i class="bi bi-people fs-2"></i>
                    </router-link>
                </li>
                <li class="sidebar-list-item">
                    <router-link to="/"
                                 class="d-flex justify-content-between align-items-center text-decoration-none text-secondary my-2 mx-3">
                        <span class="fs-4">Поставщики</span>
                        <i class="bi bi-box-seam fs-2"></i>
                    </router-link>
                </li>
                <li class="sidebar-list-item">
                    <router-link to="/"
                                 class="d-flex justify-content-between align-items-center text-decoration-none text-secondary my-2 mx-3">
                        <span class="fs-4">Роли</span>
                        <i class="bi bi-nut fs-2"></i>
                    </router-link>
                </li>
                <li class="sidebar-list-item">
                    <router-link to="/"
                                 class="d-flex justify-content-between align-items-center text-decoration-none text-secondary my-2 mx-3">
                        <span class="fs-4">Настройки</span>
                        <i class="bi bi-sliders2 fs-2"></i>
                    </router-link>
                </li>
            </ul>
        </aside>

        <main class="d-flex">
            <div class="plug"></div>
            <router-view class="p-4"></router-view>
        </main>

        <footer v-if="token">

        </footer>
    </div>
</template>

<script>
import AppHeader from "../components/AppHeader.vue"

export default {
    name: "Index",
    components: {AppHeader},

    data() {
        return {
            token: null,
            isProfileOpen: false,
        }
    },

    mounted() {
        this.getToken()
    },

    updated() {
        this.getToken()
    },

    methods: {
        getToken() {
            this.token = localStorage.getItem('x_xsrf_token')
        },

        logout() {
            axios.post('/logout')
                .then(res => {
                    localStorage.removeItem('x_xsrf_token')
                    this.$router.push({name: 'user.login'})
                })
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

#sidebar {
    z-index: 10;
}

.sidebar-title, .sidebar-brand {
    height: 70px;
}

#sidebar {
    width: 300px;
    translate: -240px;
    transition: .2s;
}

#sidebar:hover {
    translate: 0;
}

.router-link-exact-active {
    color: #fff !important;
    transition: .3s;
}

.plug {
    width: 60px;
    height: 100%;
}

</style>
