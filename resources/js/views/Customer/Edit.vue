<template>
    <div>
        <a @click.prevent="$router.back()" class="col-12 col-md-4 col-lg-3 col-xl-2 link-primary text-decoration-none fw-bold d-flex align-items-center" role="button">
            <i class="bi bi-arrow-left-short fs-4"></i>
            <span>Вернуться к списку</span>
        </a>
        <h3 class="mt-2 mb-4">Редактирование клиента</h3>
        <div class="row">
            <div class="col-12">
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <label for="inputName" class="form-label ms-1">ФИО / Наименование</label>
                        <div class="form-check form-switch mb-3 d-flex">
                            <label class="form-check-label me-5 text-info" for="switchIsActive">Активность</label>
                            <input v-model="isActive" class="form-check-input" type="checkbox" role="switch" id="switchIsActive">
                        </div>
                    </div>
                    <input v-model="name" type="text" class="form-control"
                           placeholder="ФИО / Наименование" id="inputName">
                    <p v-show="errors.name" class="alert alert-danger py-2 my-2">{{ errors.name }}</p>
                </div>
                <div class="mb-3">
                    <label for="inputEmail" class="form-label ms-1">Email</label>
                    <input v-model="email" type="text" class="form-control" placeholder="example@mail.com" id="inputEmail">
                    <p v-show="errors.email" class="alert alert-danger py-2 my-2">{{ errors.email }}</p>
                </div>
                <div class="mb-3">
                    <label for="inputPhone" class="form-label ms-1">Телефон</label>
                    <div class="input-group">
                        <div class="input-group-append">
                            <span class="input-group-text">+7</span>
                        </div>
                        <input v-model="displayPhone" @input="maskPhone" type="text" class="form-control" placeholder="(999) 888-7777" id="inputPhone"/>
                    </div>
                    <p v-show="errors.phone" class="alert alert-danger py-2 my-2">{{ errors.phone }}</p>
                </div>
                <div class="mb-3 v-select-container">
                    <label for="inputGroup" class="form-label ms-1">Группа покупателей</label>
                    <v-select v-model="selectedGroup" :options="groups" label="name" value="id"
                              :searchable="true" placeholder="Группа покупателей"
                              id="inputGroup">
                        <span slot="no-options">Нет подходящих групп покупателей</span>
                    </v-select>
                </div>
                <div class="mb-3">
                    <label for="textareaInfo" class="form-label ms-1">Дополнительная информация</label>
                    <textarea v-model="info" class="form-control" placeholder="Дополнительная информация" id="textareaInfo" rows="3"></textarea>
                </div>
            </div>
        </div>
        <div class="d-flex flex-row-reverse">
            <input @click.prevent="checkForm" type="submit" class="btn btn-primary mt-4" value="Сохранить" :disabled="!isDataLoaded">
            <router-link :to="{name: 'car.index', params: {customer: customer.id}}" class="btn btn-outline-primary mt-4 me-auto text-decoration-none">Перейти к автомобилям</router-link>
        </div>
    </div>
</template>

<script>
import vSelect from 'vue-select'
import Designer from "../../store/Designer"

export default {
    name: "Edit",

    components: {
        'v-select': vSelect,
    },

    data() {
        return {
            customer: {},
            name: '',
            email: '',
            displayPhone: '',
            phone: '',
            selectedGroup: null,
            cars: null,
            orders: null,
            groups: [],
            info: '',
            isActive: true,
            customers: [],
            isDataLoaded: true,
            errors: {
                name: '',
                email: '',
                phone: '',
            }
        }
    },

    mounted() {
        this.getCustomer(this.$route.params.id)
        this.getGroups()
    },

    methods: {

        async getCustomer(id) {
            try {
                const res = await axios.get(`/api/customers/${id}`)
                this.customer = res.data.data
                this.name = this.customer.name
                this.email = this.customer.email
                this.displayPhone = this.customer.phone_number
                this.maskPhone()
                this.selectedGroup = this.customer.group
                this.cars = this.customer.cars
                this.orders = this.customer.orders
                this.info = this.customer.info
                this.isActive = this.customer.is_active
            } catch (err) {
                console.error('Не удалось загрузить данные')
            }
        },

        async getCustomers(keyword) {
            try {
                const res = await axios.get('/api/customers', {
                    params: {
                        keyword: keyword,
                        page: 1,
                        per_page: 999,
                    }
                })
                this.customers = res.data.data.filter(customer => customer.id !== this.customer.id)
            } catch (err) {
                console.error('Не удалось загрузить данные')
            }
        },

        async getGroups() {
            try {
                const res = await axios.get('/api/groups')
                this.groups = res.data.data
            } catch (err) {
                console.error('Не удалось загрузить данные')
            }
        },

        maskPhone() {
            this.displayPhone = Designer.maskPhone(this.displayPhone)
            this.phone = this.displayPhone.replace(/\D/g, '')
        },

        async checkForm() {
            this.errors.name = ''
            this.errors.email = ''
            this.errors.phone = ''
            if (this.name.trim() === '') {
                this.errors.name = 'Не указано ФИО / Наименование'
            }
            if (this.email.trim() === '') {
                this.errors.email = 'Не указан Email';
            } else {
                await this.getCustomers(this.email)
                this.customers.forEach(item => {
                    if (this.email === item.email) {
                        this.errors.email = `Email "${this.email}" уже зарегистрирован`
                    }
                })
                const emailRegExp = /\S+@\S+\.\S+/;
                if (!emailRegExp.test(this.email)) {
                    this.errors.email = 'Неверный формат Email (example@mail.com)'
                }
            }
            if (this.phone.trim().length < 10) {
                this.errors.phone = 'Номер телефона не соответствует формату (999) 888-7777'
            } else {
                await this.getCustomers(this.phone)
                this.customers.forEach(item => {
                    if (this.phone === item.phone_number) {
                        this.errors.phone = `Телефон "${this.phone}" уже зарегистрирован`
                    }
                })
            }

            if (this.errors.name || this.errors.email || this.errors.phone) {
                return;
            }
            await this.update()
        },

        async update() {
            try {
                const data = {
                    name: this.name,
                    email: this.email,
                    phone_number: this.phone,
                    group_id: this.selectedGroup ? this.selectedGroup.id : null,
                    customer_id: this.customer.id,
                    cars: this.cars,
                    info: this.info,
                    is_active: this.isActive,
                }

                this.isDataLoaded = false
                await axios.patch(`/api/customers/${this.$route.params.id}`, data)
                this.isDataLoaded = true
                await this.$router.push({name: 'customer.index'})
            } catch (err) {
                console.error('Не удалось загрузить данные')
            }
        }

    }
}
</script>

<style scoped>

</style>
