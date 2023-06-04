<template>
    <div>
        <ul class="sidebar-list list-unstyled">
            <li v-for="link in links" :key="link.name" class="sidebar-list-item" @click="hideMenu">
                <router-link :to="link.to" :replace="true"
                             :class="{'router-link-exact-active': isRouteExact(link.to)}"
                             class="d-flex justify-content-between align-items-center text-decoration-none text-secondary py-2 px-3">
                    <span class="fs-4">{{ link.title }}</span>
                    <i :class="link.icon"></i>
                </router-link>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    name: "AppMenu",

    props: {
        navbar: {
            required: false,
        },
        isMenuOpen: {
            type: Boolean,
            required: false,
        }
    },

    data() {
        return {
            links: [
                // {
                //     name: "Статистика",
                //     title: "Статистика",
                //     to: "/",
                //     icon: "bi bi-bar-chart-line fs-2",
                // },
                {
                    name: "Заказы",
                    title: "Заказы",
                    to: { name: "order.index" },
                    icon: "bi bi-cart fs-2",
                },
                {
                    name: "Товары",
                    title: "Товары",
                    to: { name: "product.index" },
                    icon: "bi bi-box-seam fs-2",
                },
                {
                    name: "Категории",
                    title: "Категории",
                    to: { name: "category.index" },
                    icon: "bi bi-boxes fs-2",
                },
                {
                    name: "Производители",
                    title: "Производители",
                    to: { name: "brand.index" },
                    icon: "bi bi-bookmarks fs-2",
                },
                {
                    name: "Клиенты",
                    title: "Клиенты",
                    to: { name: "customer.index" },
                    icon: "bi bi-people fs-2",
                },
                {
                    name: "Поставщики",
                    title: "Поставщики",
                    to: { name: "provider.index" },
                    icon: "bi bi bi-truck fs-2",
                },
                // {
                //     name: "Роли",
                //     title: "Роли",
                //     to: "/",
                //     icon: "bi bi-nut fs-2",
                // },
                {
                    name: "Настройки",
                    title: "Настройки",
                    to: { name: "settings.index" },
                    icon: "bi bi-sliders2 fs-2",
                },
            ],
        };
    },

    methods: {

        hideMenu() {
            if (this.navbar) {
                this.$emit('update-data', false)
                this.navbar.classList.remove('show')
                this.navbar.classList.add('collapse')
            }
        },

        isRouteExact(route) {
            const currentRoutePathWithoutParams = this.$route.path.split('?')[0];
            const linkPathWithoutParams = this.$router.resolve(route).route.path.split('?')[0];
            return currentRoutePathWithoutParams === linkPathWithoutParams;
        },

    },

    computed: {

    }

}
</script>

<style scoped>

.sidebar-list-item {
    transition: 0.2s;
}
.sidebar-list-item:hover {
    background: var(--bs-dark);
}

.router-link-exact-active {
    color: var(--bs-white) !important;
    background: var(--bs-dark);
    transition: .3s;
}

</style>
