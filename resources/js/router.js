import Vue from 'vue'
import VueRouter from "vue-router";

Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',

    routes: [
        {
            path: '/get', component: () => import('./views/Get.vue'),
            name: 'get.index'
        },
        {
            path: '/user/login', component: () => import('./views/Login.vue'),
            name: 'user.login'
        },
        {
            path: '/user/registration', component: () => import('./views/Registration.vue'),
            name: 'user.registration'
        },
        {
            path: '/user/personal', component: () => import('./views/Personal.vue'),
            name: 'user.personal'
        },

        {
            path: '/brands', component: () => import('./views/Brand/Index.vue'),
            name: 'brand.index',
        },
        {
            path: '/brands/add', component: () => import('./views/Brand/Create.vue'),
            name: 'brand.create'
        },
        {
            path: '/brands/:alias', component: () => import('./views/Brand/Show.vue'),
            name: 'brand.show'
        },
        {
            path: '/brands/:alias/edit', component: () => import('./views/Brand/Edit.vue'),
            name: 'brand.edit'
        },

        {
            path: '/categories', component: () => import('./views/Category/Index.vue'),
            name: 'category.index'
        },
        {
            path: '/categories/add', component: () => import('./views/Category/Create.vue'),
            name: 'category.create'
        },
        {
            path: '/categories/:alias', component: () => import('./views/Category/Show.vue'),
            name: 'category.show'
        },
        {
            path: '/categories/:alias/edit', component: () => import('./views/Category/Edit.vue'),
            name: 'category.edit'
        },

        {
            path: '/customers', component: () => import('./views/Customer/Index.vue'),
            name: 'customer.index',
        },
        {
            path: '/customers/add', component: () => import('./views/Customer/Create.vue'),
            name: 'customer.create'
        },
        {
            path: '/customers/:id', component: () => import('./views/Customer/Show.vue'),
            name: 'customer.show'
        },
        {
            path: '/customers/:id/edit', component: () => import('./views/Customer/Edit.vue'),
            name: 'customer.edit'
        },

        {
            path: '/:customer/cars', component: () => import('./views/Customer/Car/Index.vue'),
            name: 'car.index'
        },
        {
            path: '/:customer/cars/add', component: () => import('./views/Customer/Car/Create.vue'),
            name: 'car.create'
        },
        {
            path: '/:customer/cars/:id', component: () => import('./views/Customer/Car/Show.vue'),
            name: 'car.show'
        },
        {
            path: '/:customer/cars/:id/edit', component: () => import('./views/Customer/Car/Edit.vue'),
            name: 'car.edit'
        },

        {
            path: '/groups', component: () => import('./views/Settings/Group/Index.vue'),
            name: 'group.index',
        },
        {
            path: '/groups/add', component: () => import('./views/Settings/Group/Create.vue'),
            name: 'group.create'
        },
        {
            path: '/groups/:alias', component: () => import('./views/Settings/Group/Show.vue'),
            name: 'group.show'
        },
        {
            path: '/groups/:alias/edit', component: () => import('./views/Settings/Group/Edit.vue'),
            name: 'group.edit'
        },

        {
            path: '/orders', component: () => import('./views/Order/Index.vue'),
            name: 'order.index',
        },
        {
            path: '/orders/add', component: () => import('./views/Order/Create.vue'),
            name: 'order.create'
        },
        {
            path: '/orders/:id', component: () => import('./views/Order/Show.vue'),
            name: 'order.show'
        },
        {
            path: '/orders/:id/edit', component: () => import('./views/Order/Edit.vue'),
            name: 'order.edit'
        },
        {
            path: '/orders/:id/print', component: () => import('./views/Order/Print.vue'),
            name: 'order.print'
        },

        {
            path: '/products', component: () => import('./views/Product/Index.vue'),
            name: 'product.index'
        },
        {
            path: '/products/add', component: () => import('./views/Product/Create.vue'),
            name: 'product.create'
        },
        {
            path: '/products/:alias', component: () => import('./views/Product/Show.vue'),
            name: 'product.show'
        },
        {
            path: '/products/:alias/edit', component: () => import('./views/Product/Edit.vue'),
            name: 'product.edit'
        },

        {
            path: '/providers', component: () => import('./views/Provider/Index.vue'),
            name: 'provider.index',
        },
        {
            path: '/providers/add', component: () => import('./views/Provider/Create.vue'),
            name: 'provider.create'
        },
        {
            path: '/providers/:code', component: () => import('./views/Provider/Show.vue'),
            name: 'provider.show'
        },
        {
            path: '/providers/:code/edit', component: () => import('./views/Provider/Edit.vue'),
            name: 'provider.edit'
        },

        {
            path: '/settings', component: () => import('./views/Settings/Index.vue'),
            name: 'settings.index'
        },

        {
            path: '*', component: () => import('./views/Error.vue'),
            name: 'error'
        }
    ]
})

router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('x_xsrf_token')

    if (!token) {
        if (to.name === 'user.login' || to.name === 'user.registration') {
            return next();
        } else {
            return next({
                name: 'user.login'
            })
        }
    }


    if (to.name === 'user.login' || to.name === 'user.registration' && token) {
        return next({
            name: 'user.personal'
        })
    }

    next()
})

export default router
