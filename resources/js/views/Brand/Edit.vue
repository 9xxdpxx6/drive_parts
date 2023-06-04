<template>
    <div>
        <a @click.prevent="$router.back()" class="col-12 col-md-4 col-lg-3 col-xl-2 link-primary text-decoration-none fw-bold d-flex align-items-center" role="button">
            <i class="bi bi-arrow-left-short fs-4"></i>
            <span>Вернуться к списку</span>
        </a>
        <h3 class="mt-2 mb-4">Редактирование производителя</h3>
        <div class="row">
            <div class="col-12 col-md-4 col-xl-2 mb-4 mb-md-0">
                <label class="form-label ms-1">Фото</label>
                <div class="card bg-transparent border-0">
                    <div class="position-absolute top-50 start-50 translate-middle">
                        <i class="bi bi-plus-lg fs-4"></i>
                    </div>
                    <div class="card-img">
                        <div ref="dropzone" class="dropzone">
                            <div class="dz-message"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-8 col-xl-10">
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <label for="inputName" class="form-label ms-1">Наименование</label>
                        <div class="form-check form-switch mb-3 d-flex">
                            <label class="form-check-label me-5 text-secondary" for="switchIsActive">Активность</label>
                            <input v-model="isActive" class="form-check-input" type="checkbox" role="switch" id="switchIsActive">
                        </div>
                    </div>
                    <input v-model="name" @input="updateAlias" type="text" class="form-control"
                           placeholder="Наименование" id="inputName">
                    <p v-show="errors.name" class="alert alert-danger py-2 my-2">{{ errors.name }}</p>
                </div>
                <div class="mb-3">
                    <label for="inputUrl" class="form-label ms-1">URL</label>
                    <input v-model="alias" type="text" class="form-control" placeholder="URL" id="inputUrl" disabled>
                </div>
                <div v-show="!brand.car_models_qty" class="form-check form-switch mb-3 fs-5">
                    <label class="form-check-label" for="switchHasVehicles">Транспортные средства</label>
                    <input v-model="hasVehicles" class="form-check-input" type="checkbox" role="switch" id="switchHasVehicles">
                </div>
            </div>
        </div>
        <div v-show="hasVehicles">
            <car-models :brand="brand"></car-models>
        </div>
        <div class="d-flex flex-row-reverse">
            <input @click.prevent="checkForm" type="submit" class="btn btn-primary mt-4" value="Сохранить" :disabled="!isDataLoaded">
        </div>
    </div>
</template>

<script>
import Dropzone from 'dropzone'
import alias from "../../store/AliasMaker"
import CarModels from './CarModel/Index.vue'

export default {
    name: "Edit",

    components: {CarModels},

    data() {
        return {
            brand: {},
            dropzone: null,
            name: '',
            alias: '',
            brands: [],
            hasVehicles: false,
            imageForDelete: [],
            isActive: true,
            isDataLoaded: true,
            errors: {
                name: '',
            },
        }
    },

    mounted() {
        this.dropzone = new Dropzone(this.$refs.dropzone, {
            url: '/api/brands',
            autoProcessQueue: false,
            dictRemoveFile: '&#9587;',
            addRemoveLinks: true,
            acceptedFiles: '.jpeg,.jpg,.png,.gif',
            maxFiles: 1,
            init: function () {
                let currentFile = null
                this.on("addedfile", function (file) {
                    if (currentFile) {
                        this.removeFile(currentFile)
                    }
                    currentFile = file
                })
            }
        })

        this.dropzone.on('removedfile', (file) => {
            this.imageForDelete.push(file)
        })

        this.getBrand(this.$route.params.alias)
        this.getBrands()
    },

    methods: {
        async getBrand(alias) {
            try {
                const res = await axios.get(`/api/brands/${alias}`)
                this.brand = res.data.data
                this.name = this.brand.name
                this.alias = this.brand.alias
                this.isActive = this.brand.is_active
                this.hasVehicles = this.brand.has_vehicles
                if (this.brand.preview_image) {
                    const file = { name: this.brand.preview_image_name }
                    await this.dropzone.displayExistingFile(file, this.brand.preview_image)
                }
            } catch (err) {
                console.error('Не удалось загрузить данные')
            }
        },

        async getBrands() {
            try {
                const res = await axios.get('/api/brands')
                this.brands = res.data.data.filter(brand => brand.alias !== this.brand.alias)
            } catch (err) {
                console.error('Не удалось загрузить данные')
            }
        },


        updateAlias() {
            this.alias = alias(this.name)
        },

        checkForm() {
            this.errors.name = ''
            if (this.name.trim() === '') {
                this.errors.name = 'Не указано наименование производителя'
            } else {
                this.brands.forEach(item => {
                    if (this.name === item.name || this.alias === item.alias) {
                        this.errors.name = `Производитель "${this.name}" уже существует`
                    }
                })
                if (!isNaN(this.name) || !isNaN(this.alias)) {
                    this.errors.name = 'Наименование производителя не может быть числом'
                }
            }

            if (this.errors.name) {
                return
            }
            this.update()
        },

        async update() {
            try {
                const data = new FormData()
                const files = this.dropzone.getAcceptedFiles()

                files.forEach(file => {
                    data.append('preview_image', file)
                })

                data.append('name', this.name)
                data.append('alias', this.alias)
                data.append('brand_id', this.brand.id)
                data.append('has_vehicles', this.hasVehicles ? '1' : '0')
                data.append('is_active', this.isActive ? '1' : '0')

                if (this.imageForDelete) data.append('image_for_delete', this.imageForDelete)

                data.append('_method', 'PATCH')

                this.isDataLoaded = false
                await axios.post(`/api/brands/${this.brand.alias}`, data)
                this.isDataLoaded = true
                await this.$router.push({name: 'brand.index'})
            } catch (err) {
                console.error('Не удалось загрузить данные')
            }
        }
    }
}
</script>

<style scoped>

</style>
