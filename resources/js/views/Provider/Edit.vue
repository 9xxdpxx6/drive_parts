<template>
    <div>
        <a @click.prevent="$router.back()" class="col-12 col-md-4 col-lg-3 col-xl-2 link-primary text-decoration-none fw-bold d-flex align-items-center" role="button">
            <i class="bi bi-arrow-left-short fs-4"></i>
            <span>Вернуться к списку</span>
        </a>
        <h3 class="mt-2 mb-4">Редактирование поставщика</h3>
        <div class="row">
            <div class="col-12 col-md-4 col-xl-2">
                <color-picker :color="color" @input="updateColor"></color-picker>
            </div>
            <div class="col-12 col-md-8 col-xl-10">
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <label for="inputName" class="form-label ms-1">Наименование</label>
                        <div class="form-check form-switch mb-3 d-flex">
                            <label class="form-check-label me-5 text-info" for="switchIsActive">Активность</label>
                            <input v-model="isActive" class="form-check-input" type="checkbox" role="switch" id="switchIsActive">
                        </div>
                    </div>
                    <input v-model="name" type="text" class="form-control"
                           placeholder="Наименование" id="inputName">
                    <p v-if="errors.name" class="alert alert-danger py-2 my-2">{{ errors.name }}</p>
                </div>
                <div class="mb-3">
                    <label for="inputCode" class="form-label ms-1">Код поставщика</label>
                    <input v-model="code" @input="handleCode" type="text" class="form-control"
                           placeholder="Код поставщика" id="inputCode" maxlength="3">
                    <p v-show="errors.code" class="alert alert-danger py-2 my-2">{{ errors.code }}</p>
                </div>
            </div>
        </div>
        <div class="d-flex flex-row-reverse">
            <input @click.prevent="checkForm" type="submit" class="btn btn-primary mt-4" value="Сохранить" :disabled="!isDataLoaded">
        </div>
    </div>
</template>

<script>
import ColorPicker from "../../components/ColorPicker.vue"

export default {
    name: "Edit",

    components: {ColorPicker},

    data() {
        return {
            provider: {},
            name: '',
            code: '',
            color: '#ffffff',
            providers: [],
            isActive: true,
            isDataLoaded: true,
            errors: {
                name: '',
                code: '',
            },
        }
    },

    mounted() {
        this.getProvider(this.$route.params.code)
        this.getProviders()
    },

    methods: {
        updateColor(color) {
            this.color = color
        },

        handleCode(e) {
            let value = e.target.value
            value = value.replace(/[^A-Za-z0-9]/g, "").toUpperCase()
            this.code = value

            if (value.length < 3) {
                this.errors.code = 'Код должен содержать 3 символа'
            } else {
                this.errors.code = ''
            }
        },

        async getProvider(code) {
            try {
                const res = await axios.get(`/api/providers/${code}`)
                this.provider = res.data.data
                this.name = this.provider.name
                this.code = this.provider.code
                this.color = this.provider.color
                this.isActive = this.provider.is_active
            } catch (err) {
                console.error('Не удалось загрузить данные')
            }
        },

        async getProviders() {
            try {
                const res = await axios.get('/api/providers')
                this.providers = res.data.data.filter(provider => provider.code !== this.provider.code)
            } catch (err) {
                console.error('Не удалось загрузить данные')
            }
        },

        checkForm() {
            this.errors.name = ''
            if (this.name.trim() === '') {
                this.errors.name = 'Не указано наименование поставщика'
            } else {
                this.providers.forEach(item => {
                    if (this.name === item.name) {
                        this.errors.name = `Поставщик с наименованием "${this.name}" уже существует`
                    }
                    if (this.code === item.code) {
                        this.errors.code = `Поставщик с кодом "${this.name}" уже существует`
                    }
                })
                if (!isNaN(this.name)) {
                    this.errors.name = 'Наименование поставщика не может быть числом'
                }
                if (!isNaN(this.code)) {
                    this.errors.code = 'Код поставщика не может быть числом'
                }
            }
            if (this.code.trim().length < 3) {
                this.errors.code = 'Код должен содержать 3 символа'
            }

            if (this.errors.name || this.errors.code) {
                return
            }
            this.update()
        },

        async update() {
            try {
                const data = {
                    name: this.name,
                    code: this.code,
                    color: this.color,
                    provider_id: this.provider.id,
                    is_active: this.isActive,
                }

                this.isDataLoaded = false
                await axios.patch(`/api/providers/${this.provider.code}`, data)
                this.isDataLoaded = true
                await this.$router.push({name: 'provider.index'})
            } catch (err) {
                console.error('Не удалось загрузить данные')
            }
        }
    }
}
</script>

<style scoped>

</style>
