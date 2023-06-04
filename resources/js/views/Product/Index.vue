<template>
    <div>
        <div class="title d-flex">
            <h3 class="me-auto mb-4">Товары</h3>
            <router-link :to="{name: 'product.create'}">
                <div class="d-flex align-items-center btn btn-primary px-3">
                    <i class="bi bi-plus-lg fs-5"></i>
                    <span class="d-none d-md-block ms-2">Добавить</span>
                </div>
            </router-link>
        </div>
        <div class="controls mt-2">
            <div class="row flex-row-reverse">
                <div class="col-12 col-md-6 col-xl-4 mb-2">
                    <input v-model="keyword" @input="getProducts(page)" type="text" class="form-control" placeholder="Поиск">
                </div>
                <div class="col-12 col-md-6 col-xl-4 mb-2">
                    <v-select v-model="categories" :options="filterList.categories" label="name" value="id"
                              :searchable="true" placeholder="Категория" multiple
                              id="inputCategory" @input="getProducts(page)">
                        <span slot="no-options">Нет подходящих категорий</span>
                    </v-select>
                </div>
                <div class="col-12 col-md-6 col-xl-4 mb-2">
                    <v-select v-model="brands" :options="filterList.brands" label="name" value="id"
                              :searchable="true" placeholder="Производитель" multiple
                              id="inputBrand" @input="getProducts(page)">
                        <span slot="no-options">Нет подходящих производителей</span>
                    </v-select>
                </div>
                <div class="col-12 col-md-6 col-xl-4 mb-2">
                    <v-select v-model="perPage" :options="perPageOptions" :clearable="false"
                              :searchable="false" placeholder="Отображать по"
                              id="inputBrand" @input="getProducts(page)">
                        <span slot="no-options">Нет подходящих производителей</span>
                        <template slot="selected-option" slot-scope="option">
                            <div>
                                <span>Отображать по:</span>
                                <span>{{ option.label }}</span>
                            </div>
                        </template>
                        <template slot="option" slot-scope="option">
                            <div class="d-flex flex-wrap">
                                <span class="me-1">Отображать по:</span>
                                <span>{{ option.label }}</span>
                            </div>
                        </template>
                    </v-select>
                </div>
                <div class="col-12 col-xl-8 mb-2">
                    <v-select v-model="sort" :options="sortOptions" :clearable="false" label="label" value="value"
                              :searchable="false"
                              id="inputBrand" @input="getProducts(page)">
                        <span slot="no-options">Нет подходящих производителей</span>
                        <span slot="placeholder"><i class="bi bi-sort-down"></i></span>
                    </v-select>
                </div>
            </div>
        </div>
        <Preloader v-if="isLoading"></Preloader>
        <div v-else class="content">
            <div class="products">
                <div class="heading d-none d-md-flex mt-4">
                    <h4 class="ms-2 me-5">Фото</h4>
                    <h4 class="ms-md-4 ps-2">Наименование</h4>
                </div>
                <div class="row" v-if="products && products.length">
                    <div class="product bg-transparent mb-3" v-for="(product, index) in products" :key="index">
                        <div class="d-md-flex shadow-sm rounded-3 overflow-hidden align-items-start"
                             :class="product.is_active ? 'bg-white' : 'bg-secondary'">
                            <div class="image d-none d-md-block">
                                <img :src="product.preview_image ? product.preview_image : '/assets/no_image.svg'"
                                     class="rounded-3" width="120" :alt="product.part_number">
                            </div>
                            <div class="image d-flex justify-content-center m-3 d-md-none">
                                <img :src="product.preview_image ? product.preview_image : '/assets/no_image.svg'"
                                     class="rounded-3" width="300" :alt="product.part_number">
                            </div>
                            <div class="text-decoration-none text-dark flex-grow-1 my-2 mx-4">
                                <router-link :to="{name: 'product.edit', params: {alias: product.alias}}" class="text-decoration-none text-dark">
                                    <div class="product-title text-break pe-5">
                                        <h4>{{ product.name }}</h4>
                                    </div>
                                    <div>
                                        <span>Артикул:</span>
                                        <span>{{ product.part_number }}</span>
                                    </div>
                                    <div>
                                        <span>Категория:</span>
                                        <span v-if="product.category">{{ product.category.name }}</span>
                                    </div>
                                    <div>
                                        <span>Производитель</span>
                                        <span v-if="product.brand">{{ product.brand.name }}</span>
                                    </div>
                                </router-link>
                            </div>
                            <div class="product-details d-none d-md-flex fs-3 mt-2 col-2 col-xl-1 flex-row-reverse">
                                <div class="price ms-2">
                                    <div class="h5">руб</div>
                                    <div v-if="product.old_price" class="h5 text-decoration-line-through text-secondary">руб</div>
                                    <div class="h5">шт</div>
                                </div>
                                <div class="price text-end">
                                    <div class="h5 text-nowrap">{{ product.price | formatNumber }}</div>
                                    <div v-if="product.old_price" class="h5 text-decoration-line-through text-secondary">{{ product.old_price }}</div>
                                    <div class="h5">{{ product.qty }}</div>
                                </div>

                            </div>
                            <div class="product-details d-flex d-md-none fs-3 mt-2 mx-4">
                                <div class="price text-end">
                                    <div class="h5">{{ product.price | formatNumber }}</div>
                                    <div v-if="product.old_price" class="h5 text-decoration-line-through text-secondary">{{ product.old_price }}</div>
                                </div>
                                <div class="currency ms-2 me-auto">
                                    <div class="h5">руб</div>
                                    <div v-if="product.old_price" class="h5 text-decoration-line-through text-secondary">руб</div>
                                </div>
                                <div class="qty d-flex">
                                    <div class="h5 me-2">{{ product.qty }}</div>
                                    <div class="h5">шт</div>
                                </div>
                            </div>
                            <ConfirmDelete :item="product" table="products" :update-content="getProducts" class="text-end"></ConfirmDelete>
                        </div>
                    </div>
                </div>
                <h3 v-else>Результаты отсутствуют</h3>
            </div>
            <div class="pagination-container">
                <Pagination :page="+page" :per-page="+perPage" :pagination="pagination" :get-items="getProducts"></Pagination>
            </div>
        </div>
    </div>
</template>

<script>
import Preloader from "../../components/Preloader.vue"
import ConfirmDelete from "../../components/ConfirmDelete.vue"
import vSelect from "vue-select"
import Pagination from "../../components/Pagination.vue";

export default {
    name: "Index",
    components: {
        Pagination,
        Preloader,
        ConfirmDelete,
        'v-select': vSelect,
    },

    data() {
        return {
            isLoading: true,
            products: null,
            filterList: [],
            keyword: '',
            categories: [],
            brands: [],
            sort: { label: 'Сортировать по умолчанию', value: '' },
            sortOptions: [
                { label: 'Сортировать по умолчанию', value: '' },
                { label: 'Сортировать по цене (возрастание)', value: 'price_asc' },
                { label: 'Сортировать по цене (убывание)', value: 'price_desc' },
                { label: 'Сортировать по наименованию (А-Я)', value: 'name_asc' },
                { label: 'Сортировать по наименованию (Я-А)', value: 'name_desc' },
            ],
            page: 1,
            perPage: 25,
            perPageOptions: [
                25,
                50,
                100,
                200,
            ],
            pagination: [],
        }
    },

    mounted() {
        this.getParams()
        this.getFilterList()
    },

    methods: {

        async getParams() {
            const keyword = this.$route.query.keyword
            const categoryIds = this.$route.query.categories
            const brandIds = this.$route.query.brands
            const sort = this.$route.query.sort
            const page = this.$route.query.page
            const perPage = this.$route.query.per_page

            if (keyword) {
                this.keyword = keyword
            }
            if (categoryIds) {
                for (const id of categoryIds) {
                    try {
                        const res = await axios.get(`/api/categories/${id}`)
                        this.categories.push(res.data.data)
                    } catch (error) {
                        console.error(error)
                    }
                }
            }
            if (brandIds) {
                for (const id of brandIds) {
                    try {
                        const res = await axios.get(`/api/brands/${id}`)
                        this.brands.push(res.data.data)
                    } catch (error) {
                        console.error(error)
                    }
                }
            }
            if (sort) {
                this.sort = this.sortOptions.find(option => option.value === sort)
            }
            if (page) {
                this.page = page
            }
            if (perPage) {
                this.perPage = perPage
            }
            await this.getProducts()
        },

        async getProducts(page = this.page) {
            this.isLoading = true

            let categoryIds
            let brandIds
            if (this.categories) {
                categoryIds = Array.isArray(this.categories) ? this.categories.map(category => category.id.toString()) : [this.categories.id.toString()]
            }
            if (this.brands) {
                brandIds = Array.isArray(this.brands) ? this.brands.map(brand => brand.id.toString()) : [this.brands.id.toString()]
            }

            const currentQuery = {
                keyword: this.keyword,
                categories: categoryIds,
                brands: brandIds,
                sort: this.sort.value,
                page: page.toString(),
                per_page: this.perPage.toString(),
            }

            const currentPath = '/products'
            const currentParams = {
                keyword: this.$route.query.keyword,
                categories: Array.isArray(this.$route.query.categories) ? this.$route.query.categories : [this.$route.query.categories].filter(Boolean),
                brands: Array.isArray(this.$route.query.brands) ? this.$route.query.brands : [this.$route.query.brands].filter(Boolean),
                sort: this.$route.query.sort,
                page: this.$route.query.page,
                per_page: this.$route.query.per_page,
            }

            const isCurrentLocation =
                this.$route.path === currentPath &&
                JSON.stringify(currentParams) === JSON.stringify(currentQuery)

            try {
                const res = await axios.get('/api/products', {
                    params: currentQuery,
                });

                if (!isCurrentLocation) {
                    await this.$router.push({
                        path: currentPath,
                        query: currentQuery,
                    })
                }

                this.products = res.data.data
                this.pagination = res.data.meta
                this.isLoading = false
            } catch (err) {
                this.pagination = {}
                this.isLoading = false
                console.error('Не удалось загрузить данные')
            }
        },

        async getFilterList() {
            const res = await axios.get('/api/products/filters')
            this.filterList = res.data
        }

    },

}
</script>

<style scoped>

.content {
    min-height: 72vh;
    display: flex;
    flex-direction: column;
}

.products {
    flex: 1 1 auto;
}

.pagination-container {
    grid-row: 2;
}

</style>
