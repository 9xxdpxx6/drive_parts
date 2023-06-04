<template>
    <div>
        <div class="title d-flex">
            <h3 class="me-auto mb-4">Производители</h3>
            <router-link :to="{name: 'brand.create'}">
                <div class="d-flex align-items-center btn btn-primary px-3">
                    <i class="bi bi-plus-lg fs-5"></i>
                    <span class="d-none d-md-block ms-2">Добавить</span>
                </div>
            </router-link>
        </div>
        <div class="controls mt-2 mb-4">
            <div class="row">
                <div class="col-12 mb-2">
                    <input v-model="keyword" @input="getBrands()" type="text" class="form-control" placeholder="Поиск">
                </div>
            </div>
        </div>
        <Preloader v-if="isLoading"></Preloader>
        <div v-else class="brands">
            <div class="heading d-flex">
                <h4 class="ms-2">Фото</h4>
                <h4 class="ms-md-4 ps-2">Наименование</h4>
            </div>
            <div class="row" v-if="brands.length">
                <div class="brand bg-transparent mb-3" v-for="(brand, index) in brands" :key="index">
                    <div class="d-flex shadow-sm rounded-3 overflow-hidden align-items-center"
                         :class="brand.is_active ? 'bg-white' : 'bg-secondary'">
                        <div class="image">
                            <img :src="brand.preview_image ? brand.preview_image : '/assets/no_image.svg'"
                                 class="rounded-3" width="70" :alt="brand.name">
                        </div>
                        <router-link :to="{name: 'brand.edit', params: {alias: brand.alias}}"
                                     class="text-decoration-none text-dark flex-grow-1 my-2 me-3">
                            <div class="brand-title ps-4">
                                <h4>{{ brand.name }}</h4>
                            </div>
                        </router-link>
                        <div v-if="brand.has_vehicles" class="car-badge fs-3"
                             :class="brand.is_active ? 'text-secondary' : 'text-white'">
                            <i class="bi bi-car-front-fill"></i>
                        </div>
                        <ConfirmDelete :item="brand" table="brands" :update-content="getBrands" :related="brand.car_models_qty"></ConfirmDelete>
                    </div>
                </div>
            </div>
            <h3 v-else>Результаты отсутствуют</h3>
        </div>
    </div>
</template>

<script>
import Preloader from "../../components/Preloader.vue"
import ConfirmDelete from "../../components/ConfirmDelete.vue";

export default {
    name: "Index",
    components: {
        Preloader,
        ConfirmDelete,
    },

    data() {
        return {
            isLoading: true,
            brands: [],
            keyword: '',
        }
    },

    mounted() {
        this.getBrands()
    },

    methods: {
        async getBrands() {
            this.isLoading = true

            try {
                const res = await axios.get('/api/brands');
                this.brands = res.data.data
                this.isLoading = false
            } catch (err) {
                console.error('Не удалось загрузить данные')
            }

            if (this.keyword.length) {
                this.brands = this.brands.filter(brand => brand.name.toLowerCase().includes(this.keyword.toLowerCase()))
            }

            this.isLoading = false
        }
    }
}
</script>

<style scoped>

</style>
