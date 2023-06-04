<template>
    <div>
        <div class="title d-flex">
            <h3 class="me-auto mb-4">Статусы заказов</h3>
            <a @click.prevent="changeEditOrderStatusId(null)" href="#">
                <div class="d-flex align-items-center btn btn-primary px-3">
                    <i class="bi bi-plus-lg fs-5"></i>
                    <span class="d-none d-md-block ms-2">Добавить</span>
                </div>
            </a>
        </div>
        <div class="controls mt-2 mb-4">
            <div class="row">
                <div class="col-12 mb-2">
                    <input v-model="keyword" @input="getOrderStatuses()" type="text" class="form-control" placeholder="Поиск">
                </div>
            </div>
        </div>
        <Preloader v-if="isLoading"></Preloader>
        <div v-else class="order-statuses">
            <div class="heading d-flex">
                <h4 class="ms-md-2">Наименование</h4>
            </div>
            <div class="row" v-if="orderStatuses.length">
                <template v-for="(orderStatus, index) in orderStatuses">
                    <div class="order-status bg-transparent mb-3" :class="isEdit(orderStatus.id) ? 'd-none' : ''">
                        <div class="d-flex shadow-sm rounded-3 overflow-hidden align-items-center"
                             :class="orderStatus.is_active ? 'bg-white' : 'bg-secondary'">
                            <div class="text-decoration-none text-dark flex-grow-1 my-2 me-3">
                                <div class="order-status-title ps-4">
                                    <h4 class="text-break">{{ orderStatus.name }}</h4>
                                </div>
                            </div>
                            <div class="d-none d-md-block text-decoration-none text-dark  my-2 me-3">
                                <span class="text-break">{{ orderStatus.writeOffTitle }}</span>
                            </div>
                            <div class="align-self-center">
                                <button @click.prevent="changeEditOrderStatusId(orderStatus)"
                                        class="btn btn-outline-primary d-flex align-items-center"
                                        data-bs-toggle="tooltip" title="Редактировать">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                            </div>
                            <ConfirmDelete :item="orderStatus" table="order_statuses"
                                           :update-content="getOrderStatuses"></ConfirmDelete>
                        </div>
                    </div>
                    <div class="order-status bg-transparent mb-3" :class="isEdit(orderStatus.id) ? '' : 'd-none'">
                        <div class="d-flex bg-white shadow-sm rounded-3 overflow-hidden align-items-center">
                            <div class="text-decoration-none text-dark flex-grow-1 me-3">
                                <div class="order-status-title ps-3 py-2">
                                    <input v-model="name" type="text" class="form-control">
                                </div>
                            </div>
                            <div class="d-none d-md-block text-decoration-none text-dark flex-grow-1 my-2 me-3">
                                <select v-model="writeOff" class="form-select">
                                    <option value="1">Не списывать товары</option>
                                    <option value="2">Списывать товары</option>
                                    <option value="3">Зачислять товары</option>
                                </select>
                            </div>
                            <div class="d-none form-check form-switch mb-3 d-md-flex">
                                <input v-model="isActive" class="form-check-input mt-3" type="checkbox" role="switch"
                                       id="switchIsActive">
                            </div>
                            <div class="align-self-center">
                                <button @click.prevent="update(orderStatus.id)"
                                        class="btn btn-primary d-flex align-items-center" data-bs-toggle="tooltip"
                                        title="Сохранить" :disabled="!isDataLoaded">
                                    <i class="bi bi-check-square"></i>
                                </button>
                            </div>
                            <ConfirmDelete :item="orderStatus" table="order_statuses"
                                           :update-content="getOrderStatuses"></ConfirmDelete>
                        </div>
                        <p v-show="errors.name" class="alert alert-danger py-2 my-2">{{ errors.name }}</p>
                    </div>
                </template>
            </div>
            <h3 v-else-if="!showAdd">Результаты отсутствуют</h3>
            <transition name="slide-fade">
                <div v-if="showAdd" class="order-status bg-transparent mb-3">
                    <div class="d-flex bg-white shadow-sm rounded-3 overflow-hidden align-items-center">
                        <div class="text-decoration-none text-dark flex-grow-1 me-3">
                            <div class="order-status-title ps-3 py-2">
                                <input v-model="name" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="d-none d-md-block text-decoration-none text-dark flex-grow-1 my-2 me-3">
                            <select v-model="writeOff" class="form-select">
                                <option value="1">Не списывать товары</option>
                                <option value="2">Списывать товары</option>
                                <option value="3">Зачислять товары</option>
                            </select>
                        </div>
                        <div class="d-none form-check form-switch mb-3 d-md-flex">
                            <input v-model="isActive" class="form-check-input mt-3" type="checkbox" role="switch"
                                   id="switchIsActive">
                        </div>
                        <div class="align-self-center">
                            <button @click.prevent="store" class="btn btn-primary d-flex align-items-center"
                                    title="Добавить" :disabled="!isDataLoaded">
                                <i class="bi bi-check-square"></i>
                            </button>
                        </div>
                        <button class="btn btn-link align-self-center me-2 shadow-none" @click="showAdd = false"
                                type="button">
                            <i class="bi bi-x fs-1 text-danger"></i>
                        </button>
                    </div>
                    <p v-show="errors.name" class="alert alert-danger py-2 my-2">{{ errors.name }}</p>
                </div>
            </transition>
        </div>
    </div>
</template>

<script>
import Preloader from "../../../components/Preloader.vue"
import ConfirmDelete from "../../../components/ConfirmDelete.vue";

export default {
    name: "Index",
    components: {
        Preloader,
        ConfirmDelete,
    },

    data() {
        return {
            isLoading: true,
            orderStatuses: [],
            keyword: '',
            editOrderStatusId: null,
            showAdd: false,
            name: '',
            writeOff: 1,
            isActive: true,
            isDataLoaded: true,
            errors: {
                name: '',
            },
        }
    },

    mounted() {
        this.getOrderStatuses()
    },

    methods: {
        async getOrderStatuses() {
            this.isLoading = true

            try {
                const res = await axios.get('/api/order_statuses');
                this.orderStatuses = res.data.data
                this.isLoading = false
            } catch (err) {
                console.error('Не удалось загрузить данные')
            }

            if (this.keyword.length) {
                this.orderStatuses = this.orderStatuses.filter(status => status.name.toLowerCase().includes(this.keyword.toLowerCase()))
            }

            this.isLoading = false
        },

        changeEditOrderStatusId(orderStatus) {
            this.errors.name = ''
            this.editOrderStatusId = orderStatus ? orderStatus.id : null
            this.name = orderStatus ? orderStatus.name : ''
            this.writeOff = orderStatus ? orderStatus.write_off : 1
            this.isActive = orderStatus ? orderStatus.is_active : true
            this.showAdd = !orderStatus
        },

        checkForm() {
            this.errors.name = ''
            if (this.name.trim() === '') {
                this.errors.name = 'Не указано наименование'
            }

            return !this.errors.name;
        },

        async update(id) {
            if (!this.checkForm()) return
            this.editOrderStatusId = null
            try {
                const data = {
                    name: this.name,
                    write_off: this.writeOff,
                    is_active: this.isActive,
                }
                this.isDataLoaded = false
                await axios.patch(`/api/order_statuses/${id}`, data)
                await this.getOrderStatuses()
                this.name = ''
                this.writeOff = 1
                this.isDataLoaded = true
            } catch (err) {
                console.error('Не удалось загрузить данные')
            }
        },

        async store() {
            if (!this.checkForm()) return
            try {
                const data = {
                    name: this.name,
                    write_off: this.writeOff,
                    is_active: this.isActive,
                }
                this.isDataLoaded = false
                await axios.post('/api/order_statuses', data)
                await this.getOrderStatuses()
                this.name = ''
                this.showAdd = false
                this.isDataLoaded = true
            } catch (err) {
                console.error('Не удалось загрузить данные')
            }
        },

        isEdit(id) {
            return this.editOrderStatusId === id
        },

    }
}

</script>

<style scoped>

.slide-fade-enter-active,
.slide-fade-leave-active {
    transition: .3s;
}

.slide-fade-enter, .slide-fade-leave-to {
    transform: translateY(100%);
    opacity: 0;
}

</style>
