<template>
    <div>
        <aside v-if="!isMobile" id="sidebar" class="position-fixed h-100 top-0 bg-info">
            <div class="sidebar-title">
                <div class="sidebar-brand d-flex justify-content-between align-items-center text-secondary">
                    <span class="fs-4 text-white ps-3">
                        <span style="color: #3751FF;">DRIVE</span>
                        <span style="color: #6C757D;">PARTS</span>
                    </span>
                    <img src="/assets/logo.svg" class="me-2" height="45" alt="Logo">
                </div>
            </div>
            <AppMenu></AppMenu>
        </aside>
    </div>
</template>

<script>
import AppMenu from "./AppMenu.vue";

export default {
    name: "AppSidebar",
    components: {AppMenu},

    props: {
        isMobile: {
            type: Boolean,
            required: true,
        }
    },
}
</script>

<style scoped>

#sidebar {
    z-index: 2000;
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

</style>
