<template>
    <div>
        <div class="title d-flex">
            <h3 class="me-auto mb-4">Клиенты</h3>
            <router-link :to="{name: 'customer.create'}">
                <div class="d-flex align-items-center btn btn-primary px-3">
                    <i class="bi bi-plus-lg fs-5"></i>
                    <span class="d-none d-md-block ms-2">Добавить</span>
                </div>
            </router-link>
        </div>
        <div class="controls mt-2 mb-4">
            <div class="row flex-row-reverse">
                <div class="col-12 col-md-6 mb-2">
                    <input v-model="keyword" @input="getCustomers(page)" type="text" class="form-control" placeholder="Поиск">
                </div>
                <div class="col-12 col-md-6 mb-2">
                    <v-select v-model="selectedGroup" :options="filterList.groups" label="name" value="id"
                              :searchable="true" placeholder="Группа"
                              id="inputCategory" @input="getCustomers(page)">
                        <span slot="no-options">Нет подходящих категорий</span>
                    </v-select>
                </div>
                <div class="col-12 col-md-6 mb-2">
                    <v-select v-model="perPage" :options="perPageOptions" :clearable="false"
                              :searchable="false" placeholder="Отображать по"
                              id="inputBrand" @input="getCustomers(page)">
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
                <div class="col-12 col-md-6 mb-2">
                    <v-select v-model="sort" :options="sortOptions" :clearable="false" label="label" value="value"
                              :searchable="false"
                              id="inputBrand" @input="getCustomers(page)">
                        <span slot="no-options">Нет подходящих производителей</span>
                        <span slot="placeholder"><i class="bi bi-sort-down"></i></span>
                    </v-select>
                </div>
            </div>
        </div>
        <Preloader v-if="isLoading"></Preloader>
        <div v-else class="content">
            <div class="customers">
                <div class="heading d-flex">
                    <h4 class="ms-md-4 col-md-4 col-lg-5 me-3">ФИО / Наименование</h4>
                    <h4 class="d-none d-md-block col-md-4 col-lg-3 col-xxl-2 ps-1 ms-lg-4">Телефон</h4>
                    <h4 class="d-none d-md-block col-md-3 col-md-3 col-xxl-4 ps-1">Email</h4>
                </div>
                <div class="row" v-if="customers && customers.length">
                    <div class="customer bg-transparent mb-3" v-for="(customer, index) in customers" :key="index">
                        <div class="d-flex shadow-sm rounded-3 overflow-hidden align-items-center" :class="customer.is_active ? 'bg-white' : 'bg-secondary'">
                            <router-link :to="{name: 'customer.edit', params: {id: customer.id}}"
                                         class="text-decoration-none text-dark col-10 col-md-4 col-lg-5 flex-grow-1 text-break my-2 me-2">
                                <div class="customer-title ps-4">
                                    <h4>{{ customer.name }}</h4>
                                </div>
                            </router-link>
                            <div class="customer-phone d-none d-md-block col-md-4 col-lg-3 col-xxl-2 me-2">
                                <h4 v-if="customer">{{ formatPhone(customer.phone_number) }}</h4>
                            </div>
                            <div class="customer-email d-none d-md-block col-md-3 col-xxl-4 text-break">
                                <h4>{{ customer.email }}</h4>
                            </div>
                            <ConfirmDelete :item="customer" table="customers" :update-content="getCustomers"></ConfirmDelete>
                        </div>
                    </div>
                </div>
                <h3 v-else>Результаты отсутствуют</h3>
            </div>
            <div class="pagination-container">
                <Pagination :page="+page" :per-page="+perPage" :pagination="pagination" :get-items="getCustomers"></Pagination>
            </div>
        </div>
    </div>
</template>

<script>
import Preloader from "../../components/Preloader.vue"
import ConfirmDelete from "../../components/ConfirmDelete.vue"
import Designer from "../../store/Designer"
import Pagination from "../../components/Pagination.vue"
import vSelect from "vue-select"

export default {
    name: "Index",
    components: {
        Preloader,
        ConfirmDelete,
        Pagination,
        'v-select': vSelect,
    },

    data() {
        return {
            isLoading: true,
            customers: [],
            filterList: [],
            keyword: '',
            selectedGroup: null,
            sort: { label: 'Сортировать по умолчанию', value: '' },
            sortOptions: [
                { label: 'Сортировать по умолчанию', value: '' },
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

        formatPhone(phone) {
            return Designer.formatPhone(phone)
        },

        async getParams() {
            const keyword = this.$route.query.keyword
            const group = this.$route.query.group
            const sort = this.$route.query.sort
            const page = this.$route.query.page
            const perPage = this.$route.query.per_page

            if (keyword) {
                this.keyword = keyword
            }
            if (group) {
                try {
                    const res = await axios.get(`/api/groups/${group}`)
                    this.selectedGroup = res.data.data
                } catch (error) {
                    console.error(error)
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
            await this.getCustomers()
        },

        async getCustomers(page = this.page) {
            this.isLoading = true

            const currentQuery = {
                keyword: this.keyword,
                group: this.selectedGroup?.id.toString(),
                sort: this.sort.value,
                page: page.toString(),
                per_page: this.perPage.toString(),
            }

            const currentPath = '/customers'
            const currentParams = {
                keyword: this.$route.query.keyword,
                group: this.$route.query.group,
                sort: this.$route.query.sort,
                page: this.$route.query.page,
                per_page: this.$route.query.per_page,
            }

            const isCurrentLocation =
                this.$route.path === currentPath &&
                JSON.stringify(currentParams) === JSON.stringify(currentQuery)

            try {
                const res = await axios.get('/api/customers', {
                    params: currentQuery,
                });

                if (!isCurrentLocation) {
                    await this.$router.push({
                        path: currentPath,
                        query: currentQuery,
                    })
                }

                this.customers = res.data.data
                this.pagination = res.data.meta
                this.isLoading = false
            } catch (err) {
                this.pagination = {}
                this.isLoading = false
                console.error('Не удалось загрузить данные')
            }
        },

        async getFilterList() {
            const res = await axios.get('/api/customers/filters')
            this.filterList = res.data
        }

    }
}
</script>

<style scoped>

.content {
    min-height: 72vh;
    display: flex;
    flex-direction: column;
}

.customers {
    flex: 1 1 auto;
}

.pagination-container {
    grid-row: 2;
}

</style>
