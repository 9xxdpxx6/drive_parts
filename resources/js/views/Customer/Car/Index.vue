<template>
    <div>
        <a @click.prevent="$router.back()" class="col-12 col-md-4 col-lg-3 col-xl-2 link-primary text-decoration-none fw-bold d-flex align-items-center" role="button">
            <i class="bi bi-arrow-left-short fs-4"></i>
            <span>Вернуться к покупателю</span>
        </a>
        <div class="title d-flex">
            <h3 class="me-auto mb-4">
                <span>Автомобили</span>
                <span v-if="customer" class="fst-italic">{{ customer.name }}</span>
            </h3>
            <router-link :to="{name: 'car.create'}">
                <div class="d-flex align-items-center btn btn-primary px-3">
                    <i class="bi bi-plus-lg fs-5"></i>
                    <span class="d-none d-md-block ms-2">Добавить</span>
                </div>
            </router-link>
        </div>
        <Preloader v-if="isLoading"></Preloader>
        <div v-else class="cars">
            <div class="heading d-flex">
                <h4 class="col-md-5 col-lg-4 ps-2">Наименование</h4>
                <h4 class="d-none d-md-block col-md-3 col-lg-3">VIN</h4>
                <h4 class="d-none d-lg-block col-md-3 col-lg-3">Модификация</h4>
            </div>
            <div class="row" v-if="cars.length">
                <div class="car bg-transparent mb-3" v-for="(car, index) in cars" :key="index">
                    <div class="d-flex shadow-sm rounded-3 overflow-hidden align-items-center" :class="car.is_active ? 'bg-white' : 'bg-secondary'">
                        <router-link :to="{name: 'car.edit', params: {id: car.id}}"
                                     class="col-10 col-md-5 col-lg-4 text-decoration-none text-dark text-break my-2">
                            <div class="car-title ps-4">
                                <h4>{{ car.name }}</h4>
                            </div>
                        </router-link>
                        <div class="car-vin d-none d-md-block col-md-4 col-xl-3 me-2">
                            <h4>{{ car.vin_code }}</h4>
                        </div>
                        <div class="car-info d-none d-lg-block col-md-3 col-lg-3 text-break">
                            <h4>{{ car.modification }}</h4>
                        </div>
                        <ConfirmDelete class="ms-auto" :item="car" table="cars" :update-content="getCars"></ConfirmDelete>
                    </div>
                </div>
            </div>
            <h3 v-else>Результаты отсутствуют</h3>
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
            cars: [],
            customer: null,
        }
    },

    mounted() {
        this.getCars()
        this.getCustomer()
    },

    methods: {

        async getCars() {
            try {
                const res = await axios.get(`/api/cars`);
                this.cars = res.data.data.filter(car => car.customer_id === this.customerId)
                this.isLoading = false
            } catch (err) {
                console.error('Не удалось загрузить данные')
            }
        },

        async getCustomer() {
            try {
                const res = await axios.get(`/api/customers/${this.$route.params.customer}`);
                this.customer = res.data.data
            } catch (err) {
                console.error('Не удалось загрузить данные')
            }
        },

    },

    computed: {
        customerId() {
            return parseInt(this.$route.params.customer)
        }
    }
}
</script>

<style scoped>
.img-fluid{
    height: 45px;
    width: 45px;
}
</style>
