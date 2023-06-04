<template>
    <div>
        <a @click.prevent="$router.back()" class="col-12 col-md-4 col-lg-3 col-xl-2 link-primary text-decoration-none fw-bold d-flex align-items-center" role="button">
            <i class="bi bi-arrow-left-short fs-4"></i>
            <span>Вернуться к списку</span>
        </a>
        <h3 class="mt-2 mb-4">Редактирование автомобиля</h3>
        <div class="row">
            <div class="col-12">
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <label for="inputName" class="form-label ms-1">Идетификационный номер (VIN)</label>
                        <div class="form-check form-switch mb-3 d-flex">
                            <label class="form-check-label me-5 text-info" for="switchIsActive">Активность</label>
                            <input v-model="isActive" class="form-check-input" type="checkbox" role="switch" id="switchIsActive">
                        </div>
                    </div>
                    <input v-model="vinCode" type="text" class="form-control" placeholder="Идетификационный номер (VIN)" id="inputName" maxlength="30">
                    <p v-show="errors.vinCode" class="alert alert-danger py-2 my-2">{{ errors.vinCode }}</p>
                </div>

                <div class="mb-3">
                    <label for="inputYear" class="form-label ms-1">Год</label>
                    <input v-model="year" type="number" name="year" @change="validate" maxlength="4" min="1900" max="2099" class="form-control" placeholder="Год" id="inputYear">
                    <p v-show="errors.year" class="alert alert-danger py-2 my-2">{{ errors.year }}</p>
                </div>
                <div class="mb-3 v-select-container">
                    <label for="inputBrand" class="form-label ms-1">Марка</label>
                    <v-select v-model="selectedBrand" @input="getCarModels" :options="brands" label="name" value="id"
                              :searchable="true" placeholder="Производитель"
                              id="inputBrand">
                        <span slot="no-options">Нет подходящих производителей</span>
                    </v-select>
                    <p v-show="errors.brand" class="alert alert-danger py-2 my-2">{{ errors.brand }}</p>
                </div>
                <div class="mb-3 v-select-container">
                    <label for="inputCarModel" class="form-label ms-1">Модель</label>
                    <v-select v-model="selectedCarModel" :options="carModels" label="name" value="id"
                              :searchable="true" placeholder="Модель"
                              id="inputCarModel">
                        <span slot="no-options">Нет подходящих моделей</span>
                    </v-select>
                    <p v-show="errors.carModel" class="alert alert-danger py-2 my-2">{{ errors.carModel }}</p>
                </div>
                <div class="mb-3">
                    <label for="textareaInfo" class="form-label ms-1">Дополнительная информация</label>
                    <textarea v-model="modification" class="form-control" placeholder="Дополнительная информация" id="textareaInfo" rows="3"></textarea>
                </div>
            </div>
        </div>
        <div class="d-flex flex-row-reverse">
            <input @click.prevent="checkForm" type="submit" class="btn btn-primary mt-4" value="Сохранить" :disabled="!isDataLoaded">
        </div>
    </div>
</template>

<script>
import vSelect from 'vue-select'

export default {
    name: "Create",

    components: {
        'v-select': vSelect,
    },

    data() {
        return {
            car: null,
            vinCode: '',
            selectedBrand: null,
            selectedCarModel: null,
            modification: '',
            year: null,
            isActive: true,
            brands: [],
            carModels: [],
            cars: [],
            isDataLoaded: true,
            errors: {
                vinCode: '',
                brand: '',
                carModel: '',
                year: '',
            }
        }
    },

    mounted() {
        this.getCar(this.$route.params.id)
        this.getBrands()
        this.getCars()
    },

    methods: {

        async getCar(id) {
            try {
                const res = await axios.get(`/api/cars/${id}`)
                this.car = res.data.data
                this.vinCode = this.car.vin_code
                this.selectedBrand = this.car.brand
                this.selectedCarModel = this.car.car_model
                this.modification = this.car.modification
                this.year = this.car.year
                this.isActive = this.car.is_active
            } catch (err) {
                console.error('Не удалось загрузить данные')
            }
        },

        validate() {
            if (this.year < 1900) {
                this.year = 1901
            } else if (this.year > new Date().getFullYear()) {
                this.year = new Date().getFullYear();
            } else {
                this.year = parseFloat(this.year).toFixed(0)
            }
        },

        async getBrands() {
            try {
                const res = await axios.get('/api/brands')
                this.brands = res.data.data.filter(brand => brand.has_vehicles)
            } catch (err) {
                console.error('Не удалось загрузить данные')
            }
        },

        async getCarModels() {
            this.selectedCarModel = null
            if (!this.selectedBrand) return
            try {
                const res = await axios.get('/api/car_models')
                this.carModels = res.data.data.filter(carModel => carModel.brand_id === this.selectedBrand.id)
            } catch (err) {
                console.error('Не удалось загрузить данные')
            }
        },

        async getCars() {
            try {
                const res = await axios.get('/api/cars')
                this.cars = res.data.data.filter(car => car.id !== this.car.id)
            } catch (err) {
                console.error('Не удалось загрузить данные')
            }
        },

        checkForm() {
            this.errors.vinCode = ''
            this.errors.brand = ''
            this.errors.carModel = ''
            this.errors.year = ''
            if (this.selectedBrand === null) {
                this.errors.brand = 'Не указан производитель'
            }
            if (this.selectedCarModel === null) {
                this.errors.carModel = 'Не указана модель автомобиля';
            }
            if (this.year === null) {
                this.errors.year = 'Не указан год'
            }
            if (this.vinCode) {
                this.cars.forEach(item => {
                    if (this.vinCode === item.vin_code) {
                        this.errors.vinCode = `Email "${this.email}" уже зарегистрирован`
                    }
                });
            }

            if (this.errors.vinCode || this.errors.brand || this.errors.carModel || this.errors.year) {
                return;
            }
            this.update(this.$route.params.id)
        },

        async update(id) {
            try {
                const data = {
                    vin_code: this.vinCode.toUpperCase(),
                    car_model_id: this.selectedCarModel.id,
                    modification: this.modification,
                    year: this.year,
                    car_id: this.car.id,
                    user_id: this.$route.params.customer,
                    is_active: this.isActive,
                }

                this.isDataLoaded = false
                await axios.patch(`/api/cars/${id}`, data)
                this.isDataLoaded = true
                await this.$router.push({name: 'car.index'})
            } catch (err) {
                console.error('Не удалось загрузить данные')
            }
        }

    }
}
</script>

<style scoped>

</style>
