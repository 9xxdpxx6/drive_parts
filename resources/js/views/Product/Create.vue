<template>
    <div>
        <a @click.prevent="$router.back()" class="col-12 col-md-4 col-lg-3 col-xl-2 link-primary text-decoration-none fw-bold d-flex align-items-center" role="button">
            <i class="bi bi-arrow-left-short fs-4"></i>
            <span>Вернуться к списку</span>
        </a>
        <h3 class="mt-2 mb-4">Добавление товара</h3>
        <div class="row d-flex">
            <div class="col-12 col-md-6 col-xl-4 mb-4">
                <label class="form-label ms-1">Фото</label>
                <div class="card bg-transparent border-0">
                    <div class="position-absolute top-50 start-50 translate-middle">
                        <i class="bi bi-plus-lg fs-4"></i>
                    </div>
                    <div class="card-img">
                        <div ref="dropzone" class="dropzone flex-wrap">
                            <div class="dz-message"></div>
                            <div class="dz-error-message"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-8">
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <label for="inputName" class="form-label ms-1">Наименование</label>
                        <div class="form-check form-switch mb-3 d-flex">
                            <label class="form-check-label me-5 text-info" for="switchIsActive">Активность</label>
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
            </div>
            <div class="col-12 col-xl-4">
                <div class="mb-3">
                    <label for="inputPartNumber" class="form-label ms-1">Артикул</label>
                    <input v-model="partNumber" @input="updateAlias" type="text" class="form-control" placeholder="Артикул" id="inputPartNumber">
                    <p v-show="errors.partNumber" class="alert alert-danger py-2 my-2">{{ errors.partNumber }}</p>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                <div class="mb-3">
                    <label for="inputPrice" class="form-label ms-1">Цена</label>
                    <input v-model="price" type="number" min="0" class="form-control" placeholder="Цена" id="inputPrice">
                    <p v-show="errors.price" class="alert alert-danger py-2 my-2">{{ errors.price }}</p>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                <div class="mb-3">
                    <label for="inputOldPrice" class="form-label ms-1">Старая цена</label>
                    <input v-model="oldPrice" type="number" min="0" class="form-control" placeholder="Старая цена" id="inputOldPrice">
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                <div class="mb-3">
                    <label for="inputQty" class="form-label ms-1">Количество</label>
                    <input v-model="qty" type="number" min="0" class="form-control" placeholder="Количество" id="inputQty">
                    <p v-show="errors.qty" class="alert alert-danger py-2 my-2">{{ errors.qty }}</p>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                <div class="mb-3">
                    <label for="inputPurchasePrice" class="form-label ms-1">Закупочная цена</label>
                    <input v-model="purchasePrice" type="number" min="0" class="form-control" placeholder="Закупочная цена" id="inputPurchasePrice">
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-6">
                <div class="mb-3 v-select-container">
                    <label for="inputCategory" class="form-label ms-1">Категория</label>
                    <v-select v-model="selectedCategory" :options="categories" label="name" value="id"
                              :searchable="true" placeholder="Категория"
                              id="inputCategory">
                        <span slot="no-options">Нет подходящих категорий</span>
                    </v-select>
                    <p v-show="errors.category" class="alert alert-danger py-2 my-2">{{ errors.category }}</p>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-6">
                <div class="mb-3 v-select-container">
                    <label for="inputBrand" class="form-label ms-1">Производитель</label>
                    <v-select v-model="selectedBrand" :options="brands" label="name" value="id"
                              :searchable="true" placeholder="Производитель"
                              id="inputBrand">
                        <span slot="no-options">Нет подходящих производителей</span>
                    </v-select>
                    <p v-show="errors.brand" class="alert alert-danger py-2 my-2">{{ errors.brand }}</p>
                </div>
            </div>
            <div class="col-12">
                <div class="mb-3">
                    <label for="textareaDescription" class="form-label ms-1">Описание</label>
                    <textarea v-model="description" class="form-control" placeholder="Описание" id="textareaDescription" rows="3"></textarea>
                </div>
            </div>
        </div>
        <div class="d-flex flex-row-reverse">
            <input @click.prevent="checkForm" type="submit" class="btn btn-primary mt-4" value="Добавить" :disabled="!isDataLoaded">
        </div>
    </div>
</template>

<script>
import Dropzone from 'dropzone'
import alias from "../../store/AliasMaker"
import vSelect from "vue-select"

export default {
    name: "Create",

    components: {
        'v-select': vSelect
    },

    data() {
        return {
            dropzone: null,
            partNumber: '',
            name: '',
            alias: '',
            description: '',
            price: null,
            oldPrice: null,
            qty: 1,
            purchasePrice: null,
            selectedCategory: null,
            selectedBrand: null,
            isActive: true,
            products: [],
            categories: [],
            brands: [],
            isDataLoaded: true,
            errors: {
                partNumber: '',
                name: '',
                price: '',
                qty: '',
                category: '',
                brand: '',
            }
        }
    },

    mounted() {
        this.dropzone = new Dropzone(this.$refs.dropzone, {
            url: '/api/products',
            autoProcessQueue: false,
            dictRemoveFile: '&#9587;',
            addRemoveLinks: true,
            acceptedFiles: '.jpeg,.jpg,.png,.gif',
            maxFiles: 10,
            init: function () {
                let currentFile = null
                this.on("addedfile", function (file) {
                    if (this.files.length > 10) {
                        this.removeFile(currentFile)
                    }
                    currentFile = file
                })
            }
        })

        this.getCategories()
        this.getBrands()
    },

    methods: {

        async getProducts(keyword) {
            try {
                const res = await axios.get('/api/products', {
                    params: {
                        keyword: this.partNumber,
                        page: 1,
                        per_page: 999,
                    }
                })
                this.products = res.data.data
            } catch (err) {
                console.error('Не удалось загрузить данные')
            }
        },

        async getCategories() {
            try {
                const res = await axios.get(`/api/categories`)
                this.categories = res.data.data
            } catch (err) {
                console.error('Не удалось загрузить данные')
            }
        },

        async getBrands() {
            try {
                const res = await axios.get(`/api/brands`)
                this.brands = res.data.data
            } catch (err) {
                console.error('Не удалось загрузить данные')
            }
        },

        updateAlias() {
            this.alias = alias(this.name + ' ' + this.partNumber)
        },

        async checkForm() {
            this.errors.partNumber = ''
            this.errors.name = ''
            this.errors.price = ''
            this.errors.qty = ''
            this.errors.category = ''
            this.errors.brand = ''
            if (this.partNumber.trim() === '') {
                this.errors.partNumber = 'Не указан артикул'
            } else {
                await this.getProducts()
                this.products.forEach(item => {
                    if (this.alias === item.alias) {
                        this.errors.name = `Товар "${this.name}" уже существует`
                    }
                })
                if (!isNaN(this.name) || !isNaN(this.alias)) {
                    this.errors.name = 'Наименование товара не может быть числом'
                }
            }
            if (this.name.trim() === '') {
                this.errors.name = 'Не указано наименование товара'
            }
            if (this.price < 0) {
                this.errors.price = 'Цена должна быть меньше 0'
            }
            if (this.price === '' || this.price === null) {
                this.errors.price = 'Цена не указана'
            }
            if (this.qty < 0) {
                this.errors.qty = 'Количество должно быть меньше 0'
            }
            if (this.qty === '' || this.qty === null) {
                this.errors.qty = 'Количество не указано'
            }
            if (this.selectedCategory === null) {
                this.errors.category = 'Не указана категория'
            }
            if (this.selectedBrand === null) {
                this.errors.brand = 'Не указан производитель'
            }

            if (this.errors.partNumber || this.errors.name || this.errors.price || this.errors.qty || this.errors.category || this.errors.brand) {
                return
            }
            await this.store()
        },

        async store() {
            try {
                const data = new FormData();
                const files = this.dropzone.getAcceptedFiles()
                files.forEach((file) => {
                    data.append('images[]', file)
                })
                data.append('part_number', this.partNumber)
                data.append('name', this.name)
                data.append('alias', this.alias)
                data.append('description', this.description ? this.description : '')
                data.append('price', this.price)
                if (this.oldPrice) data.append('old_price', this.oldPrice)
                data.append('qty', this.qty)
                if (this.purchasePrice) data.append('purchase_price', this.purchasePrice)
                data.append('category_id', this.selectedCategory.id)
                data.append('brand_id', this.selectedBrand.id)
                data.append('is_active', this.isActive ? '1' : '0')

                this.isDataLoaded = false
                await axios.post('/api/products', data)
                this.isDataLoaded = true
                await this.$router.push({name: 'product.index'})
            } catch (err) {
                console.error('Не удалось загрузить данные')
            }
        }
    }
}
</script>

<style scoped>

</style>
