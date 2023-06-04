<template>
    <div>
        <div class="title d-flex mt-5">
            <h3 class="me-auto mb-4">Модели {{ brand.name }}</h3>
            <a @click.prevent="changeEditCarModelId(null)" href="#" class="text-decoration-none">
                <div class="d-flex align-items-center btn btn-outline-primary px-3">
                    <i class="bi bi-plus-lg fs-5"></i>
                    <span class="d-none d-md-block ms-2">Добавить</span>
                </div>
            </a>
        </div>
        <div class="controls mt-2 mb-4">
            <div class="row">
                <div class="col-12 mb-2">
                    <input v-model="keyword" @input="getCarModels()" type="text" class="form-control" placeholder="Поиск">
                </div>
            </div>
        </div>
        <Preloader v-if="isLoading"></Preloader>
        <div v-else class="car-models">
            <div class="heading d-flex">
                <h4 class="ms-md-2">Наименование</h4>
            </div>
            <div class="row" v-if="brand.car_models">
                <template v-for="(carModel, index) in carModels">
                    <div class="car-model bg-transparent mb-3" :class="isEdit(carModel.id) ? 'd-none' : ''">
                        <div class="d-flex shadow-sm rounded-3 overflow-hidden align-items-center"
                             :class="carModel.is_active ? 'bg-white' : 'bg-secondary'">
                            <div class="text-decoration-none text-dark flex-grow-1 my-2 me-3">
                                <div class="car-model-title ps-4">
                                    <h4 class="text-break">{{ carModel.name }}</h4>
                                </div>
                            </div>
                            <div class="align-self-center">
                                <button @click.prevent="changeEditCarModelId(carModel)"
                                        class="btn btn-outline-primary d-flex align-items-center"
                                        data-bs-toggle="tooltip" title="Редактировать">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                            </div>
                            <ConfirmDelete :item="carModel" table="car_models" :update-content="getCarModels"
                                           :related="carModel.cars_qty"></ConfirmDelete>
                        </div>
                    </div>
                    <div class="car-model bg-transparent mb-3" :class="isEdit(carModel.id) ? '' : 'd-none'">
                        <div class="d-flex bg-white shadow-sm rounded-3 overflow-hidden align-items-center">
                            <div class="text-decoration-none text-dark flex-grow-1 me-3 ">
                                <div class="car-model-title ps-3 py-2 mt-3">
                                    <input v-model="name" @input="updateAlias" type="text" class="form-control">
                                    <p v-model="alias"></p>
                                </div>
                            </div>
                            <div class="d-none form-check form-switch mb-3 d-md-flex">
                                <input v-model="isActive" class="form-check-input mt-3" type="checkbox" role="switch"
                                       id="switchIsActive">
                            </div>
                            <div class="align-self-center">
                                <button @click.prevent="update(carModel.id)"
                                        class="btn btn-primary d-flex align-items-center" data-bs-toggle="tooltip"
                                        title="Сохранить" :disabled="!isDataLoaded">
                                    <i class="bi bi-check-square"></i>
                                </button>
                            </div>
                            <button class="btn btn-link align-self-center me-2 shadow-none"
                                    @click="editCarModelId = null"
                                    type="button">
                                <i class="bi bi-x fs-1 text-danger"></i>
                            </button>
                        </div>
                        <p v-show="errors.name" class="alert alert-danger py-2 my-2">{{ errors.name }}</p>
                    </div>
                </template>
            </div>
            <h3 v-else-if="!showAdd">Результаты отсутствуют</h3>
            <transition name="slide-fade">
                <div v-if="showAdd" class="car-model bg-transparent mb-3">
                    <div class="d-flex bg-white shadow-sm rounded-3 overflow-hidden align-items-center">
                        <div class="text-decoration-none text-dark flex-grow-1 me-3">
                            <div class="car-model-title ps-3 py-2">
                                <input v-model="name" @input="updateAlias" type="text" class="form-control">
                            </div>
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
import ConfirmDelete from "../../../components/ConfirmDelete.vue"
import alias from "../../../store/AliasMaker"

export default {
    name: "Index",
    components: {
        Preloader,
        ConfirmDelete,
    },

    props: {
        brand: {
            type: Object,
            required: true,
        }
    },

    data() {
        return {
            isLoading: true,
            carModels: [],
            keyword: '',
            editCarModelId: null,
            showAdd: false,
            name: '',
            alias: '',
            isActive: true,
            isDataLoaded: true,
            errors: {
                name: '',
            }
        }
    },

    mounted() {
        this.$nextTick(() => {
            this.getCarModels()
        })
    },

    methods: {

        async getCarModels() {
            this.isLoading = true

            try {
                const res = await axios.get('/api/car_models')
                this.carModels = res.data.data.filter(carModel => carModel.brand_id === this.$props.brand.id)
                this.isLoading = false
            } catch (err) {
                console.error('Не удалось загрузить данные')
            }

            if (this.keyword.length) {
                this.carModels = this.carModels.filter(carModel => carModel.name.toLowerCase().includes(this.keyword.toLowerCase()))
            }

            this.isLoading = false
        },

        changeEditCarModelId(carModel) {
            this.editCarModelId = carModel ? carModel.id : null
            this.name = carModel ? carModel.name : ''
            this.alias = carModel ? carModel.alias : ''
            this.isActive = carModel ? carModel.is_active : true
            this.showAdd = !carModel
        },

        updateAlias() {
            this.alias = this.brand.alias + '-' + alias(this.name)
        },

        checkForm() {
            this.errors.name = ''
            if (this.name.trim() === '') {
                this.errors.name = 'Не указано наименование'
            } else {
                this.carModels.forEach(item => {
                    if (this.name === item.name || this.alias === item.alias) {
                        this.errors.name = `Модель "${this.name}" уже существует`
                    }
                })
            }

            return !(this.errors.name)
        },

        async update(id) {
            if (!this.checkForm()) return
            try {
                const data = {
                    name: this.name,
                    alias: this.alias,
                    brand_id: this.brand.id,
                    car_model_id: this.editCarModelId,
                    is_active: this.isActive,
                }
                this.isDataLoaded = false
                await axios.patch(`/api/car_models/${id}`, data)
                await this.getCarModels()
                this.name = ''
                this.alias = ''
                this.isDataLoaded = true
            } catch (err) {
                console.error('Не удалось загрузить данные')
            }
            this.editCarModelId = null
        },

        async store() {
            if (!this.checkForm()) return
            try {
                const data = {
                    name: this.name,
                    alias: this.alias,
                    brand_id: this.brand.id,
                    is_active: this.isActive,
                }
                this.isDataLoaded = false
                await axios.post('/api/car_models', data)
                await this.getCarModels()
                this.name = ''
                this.alias = ''
                this.showAdd = false
                this.isDataLoaded = true
            } catch (err) {
                console.error(err.response.data)
            }
        },

        isEdit(id) {
            return this.editCarModelId === id
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
