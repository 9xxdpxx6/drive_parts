<template>
    <div>
        <a @click.prevent="$router.back()" class="col-12 col-md-4 col-lg-3 col-xl-2 link-primary text-decoration-none fw-bold d-flex align-items-center" role="button">
            <i class="bi bi-arrow-left-short fs-4"></i>
            <span>Вернуться к списку</span>
        </a>
        <h3 class="mt-2 mb-4">Редактироваание группы покупателей</h3>
        <div class="row">
            <div class="col-12">
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
                <div class="mb-3">
                    <label for="inputDiscount" class="form-label ms-1">Скидка</label>
                    <div class="input-group">
                        <input v-model="discount" @change="validate" type="number" class="form-control" min="0" max="99.99" step="1" id="inputDiscount">
                        <div class="input-group-append">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                    <p v-show="errors.discount" class="alert alert-danger py-2 my-2">{{ errors.discount }}</p>
                </div>
            </div>
        </div>
        <div class="d-flex flex-row-reverse">
            <input @click.prevent="checkForm" type="submit" class="btn btn-primary mt-4" value="Сохранить" :disabled="!isDataLoaded">
        </div>
    </div>
</template>

<script>
import alias from "../../../store/AliasMaker"

export default {
    name: "Edit",

    data() {
        return {
            group: {},
            name: '',
            alias: '',
            groups: [],
            discount: 0,
            isActive: true,
            isDataLoaded: true,
            errors: {
                name: '',
                discount: '',
            }
        }
    },

    mounted() {
        this.getGroup(this.$route.params.alias)
        this.getGroups()
    },

    methods: {
        async getGroups() {
            try {
                const res = await axios.get('/api/groups')
                this.groups = res.data.data.filter(group => group.id !== this.group.id)
            } catch (err) {
                console.error('Не удалось загрузить данные')
            }
        },

        async getGroup(alias) {
            try {
                const res = await axios.get(`/api/groups/${alias}`)
                this.group = res.data.data
                this.name = this.group.name
                this.alias = this.group.alias
                this.discount = this.group.discount
                this.isActive = this.group.is_active
            } catch (err) {
                console.error('Не удалось загрузить данные')
            }
        },

        // async getUsers() {
        //     try {
        //         const res = await axios.get(`api/groups/${}/users`)
        //     } catch (err) {
        //         console.error('Не удалось загрузить данные')
        //     }
        // },

        updateAlias() {
            this.alias = alias(this.name)
        },

        validate() {
            if (this.discount < 0) {
                this.discount = 0
            } else if (this.discount > 99.99) {
                this.discount = 99.99
            } else {
                this.discount = parseFloat(this.discount).toFixed(2)
            }
        },

        checkForm() {
            this.errors.name = ''
            if (this.name.trim() === '') {
                this.errors.name = 'Не указано наименование группы'
            } else {
                this.groups.forEach(item => {
                    if (this.name === item.name || this.alias === item.alias) {
                        this.errors.name = `Группа "${this.name}" уже существует`
                    }
                })
                if (!isNaN(this.name) || !isNaN(this.alias)) {
                    this.errors.name = 'Наименование группы не может быть числом'
                }
            }
            if (isNaN(this.discount)) {
                this.errors.discount = 'Скидка не соответствует формату (0 - 99.99)'
            }

            if (this.errors.name || this.errors.discount) {
                return
            }
            this.update()
        },

        async update() {
            try {
                const data = {
                    name: this.name,
                    alias: this.alias,
                    group_id: this.group.id,
                    discount: this.discount,
                    is_active: this.isActive,
                }

                this.isDataLoaded = false
                await axios.patch(`/api/groups/${this.group.alias}`, data)
                this.isDataLoaded = true
                await this.$router.push({name: 'settings.index'})
            } catch (err) {
                console.error('Не удалось загрузить данные')
            }
        }
    }
}
</script>

<style scoped>

</style>
