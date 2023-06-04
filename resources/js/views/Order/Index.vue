<template>
    <div>
        <div class="title d-flex">
            <h3 class="me-auto mb-4">Заказы</h3>
            <router-link :to="{name: 'order.create'}">
                <div class="d-flex align-items-center btn btn-primary px-3">
                    <i class="bi bi-plus-lg fs-5"></i>
                    <span class="d-none d-md-block ms-2">Добавить</span>
                </div>
            </router-link>
        </div>
        <div class="controls mt-2 mb-4">
            <div class="row">
                <div class="col-12 col-xl-8 mb-2">
                    <DatePicker v-model="dates" @input="getOrders(page)" format="DD / MM / YYYY" lang="ru" placeholder="Выбрать даты" range></DatePicker>
                </div>
                <div class="col-12 col-md-6 col-xl-4 mb-2">
                    <input v-model="keyword" @input="getOrders(page)" type="text" class="form-control" placeholder="Поиск">
                </div>
                <div class="col-12 col-md-6 col-xl-4 mb-2">
                    <v-select v-model="selectedStatus" :options="filterList.statuses" label="name" value="id"
                              :searchable="true" placeholder="Статус"
                              id="inputCategory" @input="getOrders(page)">
                        <span slot="no-options">Нет подходящих статусов</span>
                    </v-select>
                </div>
                <div class="col-12 col-md-6 col-xl-4 mb-2">
                    <v-select v-model="labels" :options="filterList.labels" label="name" value="id"
                              :searchable="true" placeholder="Метки" multiple
                              id="inputBrand" @input="getOrders(page)">
                        <span slot="no-options">Нет подходящих меток</span>
                    </v-select>
                </div>
                <div class="col-12 col-md-6 col-xl-4 mb-2">
                    <v-select v-model="perPage" :options="perPageOptions" :clearable="false"
                              :searchable="false" placeholder="Отображать по"
                              id="inputBrand" @input="getOrders(page)">
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
                <div class="col-12 mb-2">
                    <v-select v-model="sort" :options="sortOptions" :clearable="false" label="label" value="value"
                              :searchable="false"
                              id="inputBrand" @input="getOrders(page)">
                        <span slot="no-options">Нет подходящих производителей</span>
                        <span slot="placeholder"><i class="bi bi-sort-down"></i></span>
                    </v-select>
                </div>
            </div>
        </div>
        <Preloader v-if="isLoading"></Preloader>
        <div v-else class="content">
            <div class="orders">
                <div class="heading d-none d-md-flex ms-4 me-5 pe-5">
                    <h4 class="me-4">Номер</h4>
                    <h4 class="flex-grow-1">Детали заказа</h4>
                    <h4 class="me-4 pe-3">Статус</h4>
                    <h4 class="d-none d-xxl-block me-4 pe-2">Товары</h4>
                    <h4 class="me-5 pe-5">Сумма</h4>
                    <h4 class="me-3">Метки</h4>
                </div>
                <div class="row" v-if="orders && orders.length">
                    <div class="order bg-white mb-3 shadow-sm rounded-3 overflow-hidden" v-for="(order, index) in orders" :key="index">
                        <div class="d-flex flex-column flex-md-row align-items-center">
                            <router-link :to="{name: 'order.edit', params: {id: order.id}}"
                                         class="d-flex flex-column flex-md-row text-decoration-none text-dark flex-grow-1 my-2 me-3">
                                <div class="order-number mx-4 text-center">
                                    <h4>{{ order.id }}</h4>
                                    <h4 class="badge bg-success" :class="order.is_paid ? '' : 'opacity-0'">
                                        <span class="fw-normal fs-6">оплачен</span>
                                    </h4>
                                </div>
                                <div class="order-title flex-grow-1">
                                    <h4>
                                        <span v-if="order.user" class="me-2">{{ order.user.name }}</span>
                                        <span v-if="order.car" class="me-2">{{ order.car.name }}</span>
                                    </h4>
                                    <div>
                                        <span class="text-secondary">Дата заказа:</span>
                                        <span class="text-secondary">{{ getOrderDate(order.date) }}</span>
                                    </div>
                                </div>
                            </router-link>
                            <div class="order-status col-12 col-md-1 text-center">
                                <span v-if="order.status">{{ order.status.name }}</span>
                            </div>
                            <div class="accordion accordion-flush d-none d-md-block" :id="`accordion${order.id}`">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" :id="`heading${order.id}`">
                                        <button class="accordion-button collapsed shadow-none bg-white" type="button" data-bs-toggle="collapse" :data-bs-target="`#collapse${order.id}`" aria-expanded="true" :aria-controls="`collapse${order.id}`">
                                            <span class="me-1">{{ order.purchases.length }}</span>
                                        </button>
                                    </h2>
                                </div>
                            </div>
                            <div class="order-total col-12 col-md-1 me-2">
                                <h5 class="text-center">
                                    <span class="me-1">{{ order.total_price | formatNumber }}</span>
                                    <span>руб</span>
                                </h5>
                            </div>
                            <div class="order-labels col-12 col-md-2 col-xxl-1 d-flex flex-column">
                                <h2 v-for="label in order.labels" class="badge px-3 py-1" :class="isColorDark(label.color) ? 'text-light' : 'text-dark'" :style="`background-color: ${label.color}`">
                                    <span class="fw-normal fs-6 text-wrap">{{ label.name }}</span>
                                </h2>
                            </div>
                            <ConfirmDelete :item="order" table="orders" :update-content="getOrders"></ConfirmDelete>
                        </div>
                        <div class="px-2">
                            <div :id="`collapse${order.id}`" class="accordion-collapse collapse bg-white" :aria-labelledby="`heading${order.id}`" :data-bs-parent="`#accordion${order.id}`">
                                <div class="accordion-body">
                                    <div class="my-4">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col" style="width: 120px;">Артикул</th>
                                                <th scope="col" class="w-50">Наименование</th>
                                                <th scope="col" class="text-center">Цена</th>
                                                <th scope="col" class="text-center">Кол-во</th>
                                                <th scope="col" class="text-center">Общая цена</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr v-for="(purchase, index) in order.purchases">
                                                <th scope="row">{{ index + 1 }}</th>
                                                <td>{{ purchase.product.part_number }}</td>
                                                <td>{{ purchase.product.name }}</td>
                                                <td class="text-center">{{ purchase.price | formatNumber }} руб</td>
                                                <td class="text-center">{{ purchase.qty }} шт</td>
                                                <td class="text-center">{{ calcPurchaseTotal(purchase.price, purchase.qty) | formatNumber }} руб</td>
                                            </tr>
                                            <tr><br></tr>
                                            <tr v-if="order.delivery_price">
                                                <th scope="row"></th>
                                                <td>Доставка</td>
                                                <td>{{ order.delivery_name }}</td>
                                                <td class="text-center"></td>
                                                <td class="text-center"></td>
                                                <td class="text-center">{{ order.delivery_price | formatNumber }} руб</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <h3 v-else>Результаты отсутствуют</h3>
            </div>
            <div class="pagination-container">
                <Pagination :page="+page" :per-page="+perPage" :pagination="pagination" :get-items="getOrders"></Pagination>
            </div>
        </div>
    </div>
</template>

<script>
import Preloader from "../../components/Preloader.vue"
import ConfirmDelete from "../../components/ConfirmDelete.vue"
import Pagination from "../../components/Pagination.vue"
import Designer from "../../store/Designer"
import vSelect from "vue-select"
import DatePicker from 'vue2-datepicker'
import 'vue2-datepicker/locale/ru'

export default {
    name: "Index",
    components: {
        Preloader,
        Pagination,
        ConfirmDelete,
        DatePicker,
        'v-select': vSelect,
    },

    data() {
        return {
            isLoading: true,
            orders: [],
            filterList: [],
            keyword: '',
            selectedStatus: null,
            labels: [],
            sort: { label: 'Сортировать по умолчанию', value: '' },
            sortOptions: [
                { label: 'Сортировать по умолчанию', value: '' },
                { label: 'Сортировать по дате (возрастание)', value: 'date_asc' },
                { label: 'Сортировать по дате (убывание)', value: 'date_desc' },
            ],
            page: 1,
            perPage: 25,
            perPageOptions: [
                25,
                50,
                100,
                200,
            ],
            pagination: {},
            dates: null,
        }
    },

    mounted() {
        this.getParams()
        this.getFilterList()
    },

    methods: {

        isColorDark(color) {
            return Designer.isColorDark(color)
        },

        calcPurchaseTotal(price, qty) {
            return Number(price * qty).toFixed(2)
        },

        getOrderDate(date) {
            const orderDate  = new Date(date)
            const formattedDate = orderDate.toLocaleDateString('ru', { day: '2-digit', month: '2-digit', year: 'numeric' })
            const formattedTime = orderDate.toLocaleTimeString('ru', { hour: '2-digit', minute: '2-digit' })
            return `${formattedDate} | ${formattedTime}`
        },

        formatQueryDate(date) {
            const dateObj = new Date(date);

            const padZero = (num) => (num < 10 ? "0" + num : num);

            const year = dateObj.getFullYear();
            const month = padZero(dateObj.getMonth() + 1);
            const day = padZero(dateObj.getDate());

            return `${year}-${month}-${day}`;
        },

        async getParams() {
            const keyword = this.$route.query.keyword
            const dates = this.$route.query.dates
            const status = this.$route.query.status
            const labelIds = this.$route.query.labels
            const sort = this.$route.query.sort
            const page = this.$route.query.page
            const perPage = this.$route.query.per_page

            if (keyword) {
                this.keyword = keyword
            }
            if (dates) {
                const strDates = dates.split('_')
                const from = new Date(strDates[0])
                const to = new Date(strDates[1])
                this.dates = [
                    new Date(from),
                    new Date(to),
                ]
            }
            if (status) {
                try {
                    const res = await axios.get(`/api/order_statuses/${status}`)
                    this.selectedStatus = res.data.data
                } catch (error) {
                    console.error(error)
                }
            }
            if (labelIds) {
                for (const id of labelIds) {
                    try {
                        const res = await axios.get(`/api/labels/${id}`)
                        this.labels.push(res.data.data)
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
            await this.getOrders()
        },

        async getOrders(page = this.page) {
            this.isLoading = true

            let dates
            if (this.dates && this.dates.every(date => date !== null)) {
                const from = this.formatQueryDate(this.dates[0])
                const to = this.formatQueryDate(this.dates[1])
                dates = `${from}_${to}`
            }
            let labelIds
            if (this.labels) {
                labelIds = Array.isArray(this.labels) ? this.labels.map(label => label.id.toString()) : [this.labels.id.toString()]
            }

            const currentQuery = {
                keyword: this.keyword,
                dates: dates,
                status: this.selectedStatus?.id.toString(),
                labels: labelIds,
                sort: this.sort.value,
                page: page.toString(),
                per_page: this.perPage.toString(),
            };

            const currentPath = '/orders'
            const currentParams = {
                keyword: this.$route.query.keyword,
                dates: this.$route.query.dates,
                status: this.$route.query.status,
                labels: Array.isArray(this.$route.query.labels) ? this.$route.query.labels : [this.$route.query.labels].filter(Boolean),
                sort: this.$route.query.sort,
                page: this.$route.query.page,
                per_page: this.$route.query.per_page,
            }

            const isCurrentLocation =
                this.$route.path === currentPath &&
                JSON.stringify(currentParams) === JSON.stringify(currentQuery)

            try {
                const res = await axios.get('/api/orders', {
                    params: currentQuery,
                });

                if (!isCurrentLocation) {
                    await this.$router.push({
                        path: currentPath,
                        query: currentQuery,
                    })
                }

                this.orders = res.data.data
                this.pagination = res.data.meta
                this.isLoading = false
            } catch (err) {
                this.pagination = {}
                this.isLoading = false
                console.error('Не удалось загрузить данные')
            }
        },

        async getFilterList() {
            const res = await axios.get('/api/orders/filters')
            this.filterList = res.data
        },

    }
}
</script>

<style scoped>

.content {
    min-height: 65vh;
    display: flex;
    flex-direction: column;
}

.orders {
    flex: 1 1 auto;
}

.pagination-container {
    grid-row: 2;
}

</style>
