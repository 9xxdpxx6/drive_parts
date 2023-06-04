<template>
    <div>
        <a @click.prevent="$router.back()" class="col-12 col-md-4 col-lg-3 col-xl-2 link-primary text-decoration-none fw-bold d-flex align-items-center" role="button">
            <i class="bi bi-arrow-left-short fs-4"></i>
            <span>Вернуться к списку</span>
        </a>
        <div class="d-flex flex-column flex-lg-row">
            <h3 class="mt-2 mb-4 me-4 d-flex">
                <span class="me-3">Редактирование заказа</span>
                <router-link v-if="order" :to="{name: 'order.print', params: {id: order.id}}" target="_blank" class="text-decoration-none text-dark">
                    <h3 class="link-primary fs-2"><i class="bi bi-printer"></i></h3>
                </router-link>
            </h3>
            <div class="status col-12 col-lg-3 mt-1 mb-4 me-4">
                <v-select v-model="selectedStatus" :options="statuses" label="name" value="id"
                          :searchable="true" placeholder="Статус">
                    <span slot="no-options">Нет подходящих статусов</span>
                </v-select>
                <p v-show="errors.status" class="alert alert-danger py-2 my-2">{{ errors.status }}</p>
            </div>
            <div class="labels flex-grow-1 mt-1 mb-4">
                <v-select v-model="selectedLabels" multiple :options="labels" class="custom-select" label="name" value="id" placeholder="метки" >
                    <span slot="no-options">Нет подходящих меток</span>
                    <template slot="selected-option" slot-scope="option">
                        <div class="rounded-1 px-2" :class="isColorDark(option.color) ? 'text-white' : 'text-black'" :style="`background-color: ${option.color};`">
                            {{ option.name }}
                        </div>
                    </template>
                    <template slot="option" slot-scope="option">
                        <div class="rounded-1 px-3 py-1" :class="isColorDark(option.color) ? 'text-white' : 'text-black'" :style="`background-color: ${option.color};`">
                            {{ option.name }}
                        </div>
                    </template>
                </v-select>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-xxl-9">
                <div class="order-content mb-5">
                    <h5>Содержание заказа</h5>
                    <table class="table table-hover">
                        <thead>
                        <tr class="d-none d-lg-table-row">
                            <th scope="col">#</th>
                            <th scope="col">Фото</th>
                            <th scope="col">Наименование</th>
                            <th scope="col">Цена</th>
                            <th scope="col">Кол-во</th>
                            <th scope="col">З/Ц</th>
                            <th scope="col">Поставщик</th>
                            <th scope="col">Статус</th>
                            <th scope="col" class="text-end">Сумма</th>
                            <th scope="col" class="text-end"></th>
                        </tr>
                        <tr class="d-table-row d-lg-none"><th></th></tr>
                        </thead>
                        <tbody>
                        <template v-for="(purchase, index) in purchases">
                            <PurchaseItem :purchase="purchase" :index="index" :labels="labels" :providers="providers"
                                          :update-totals="updateTotals"
                                          :update-provider="updatePurchaseProvider"
                                          :update-label="updatePurchaseLabel"
                                          :delete-purchase="deletePurchase"></PurchaseItem>
                        </template>
                        </tbody>
                    </table>
                    <p v-show="errors.purchases" class="alert alert-danger py-2 my-2">{{ errors.purchases }}</p>
                    <div class="d-flex flex-column flex-md-row justify-content-between">
                        <div class="select-products flex-grow-1 me-5 mt-1">
                            <v-select v-model="selectedProduct" :options="products" value="id"
                                      :searchable="true" placeholder="Товар" :get-option-label="getProductLabel"
                                      @input="updatePurchases(selectedProduct)" @search="getProducts">
                                <span slot="no-options">Нет подходящих товаров</span>
                                <template slot="option" slot-scope="option">
                                    <div class="d-flex">
                                        <div class="image d-none d-md-block me-2">
                                            <img :src="option.preview_image ? option.preview_image : '/assets/no_image.svg'"
                                                 class="rounded-3" width="50" :alt="option.part_number">
                                        </div>
                                        <div class="product-title text-break pe-5">
                                            <span class="text-wrap">{{ option.name }}</span>
                                            <div class="text-secondary">
                                                <span>Артикул:</span>
                                                <span>{{ option.part_number }}</span>
                                            </div>
                                        </div>
                                        <div class="d-block ms-auto pe-3 text-end">
                                            <div>цена {{ option.price }} руб</div>
                                            <div>на складе {{ option.qty }} шт</div>
                                            <div v-if="option.purchase_price">з/ц {{ option.purchase_price }} руб</div>
                                        </div>
                                    </div>
                                </template>
                            </v-select>
                        </div>
                        <div class="subtotal text-end">
                            <div>
                                <span>Сумма:</span>
                                <span v-if="subTotal">{{ subTotal | formatNumber }}</span>
                                <span>руб</span>
                            </div>
                            <div>
                                <span>Сумма З/Ц:</span>
                                <span v-if="subTotal">{{ purchaseTotal | formatNumber }}</span>
                                <span>руб</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="order-params">
                    <h5>Параметры заказа</h5>
                    <div class="my-3">
                        <label for="inputDiscount" class="form-label ms-1">Скидка</label>
                        <input v-model="discount" @change="validate" type="number" class="form-control" min="0" :max="total" step="1" id="inputDiscount">
                        <div v-if="selectedCustomer && selectedCustomer.group" class="m-1">
                            <span class="me-1">Группа:</span>
                            <span class="me-4">{{ selectedCustomer.group.name }}</span>
                            <span class="me-1">Скидка</span>
                            <span class="me-1">{{ selectedCustomer.group.discount }}</span>
                            <span>%</span>
                        </div>
                    </div>
                    <div class="mb-3 select-container">
                        <label for="inputPaymentMethod" class="form-label ms-1">Способ оплаты</label>
                        <v-select v-model="selectedPaymentMethod" :options="paymentMethods" value="id"
                                  :searchable="true" placeholder="Способ оплаты" label="name"
                                  id="inputPaymentMethod">
                            <span slot="no-options">Результаты отсутствуют</span>
                        </v-select>
                        <p v-show="errors.paymentMethod" class="alert alert-danger py-2 my-2">{{ errors.paymentMethod }}</p>
                    </div>
                    <div class="mb-3 d-flex row">
                        <div class="select-container col-12 col-md-6">
                            <label for="inputDelivery" class="form-label ms-1">Доставка</label>
                            <v-select v-model="selectedDelivery" :options="deliveries" value="id"
                                      :searchable="true" placeholder="Доставка" label="name"
                                      id="inputDelivery" @input="handleDeliveryChanged">
                                <span slot="no-options">Результаты отсутствуют</span>
                            </v-select>
                            <p v-show="errors.delivery" class="alert alert-danger py-2 my-2">{{ errors.delivery }}</p>
                        </div>
                        <div class="col12 col-md-6">
                            <label for="inputDeliveryPrice" class="form-label ms-1">Стоимость доставки</label>
                            <input v-model="deliveryPrice" @change="updateTotals" type="number" class="form-control" min="0" step="1" id="inputDeliveryPrice" :disabled="selectedDelivery ? selectedDelivery.id < 2 : true">
                            <p v-show="errors.deliveryPrice" class="alert alert-danger py-2 my-2">{{ errors.deliveryPrice }}</p>
                        </div>
                    </div>
                    <div class="mb-3 d-flex justify-content-between px-1">
                        <div class="form-check form-switch d-flex ps-0">
                            <label class="form-check-label me-5 text-info" for="switchIsActive">Заказ оплачен</label>
                            <input v-model="isPaid" class="form-check-input" type="checkbox" role="switch" id="switchIsActive">
                        </div>
                        <div class="total text-end h5">
                            <span class="me-2">Итого:</span>
                            <span v-if="total" class="me-2">{{ total | formatNumber }}</span>
                            <span>руб</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="user-info col-12 col-xxl-3 mt-5 mt-xxl-0">
                <h5 class=" mb-3">Информация о покупателе</h5>
                <div class="order-date border-bottom pb-2 mb-3">
                    <span>Дата заказа:</span>
                    <span>{{ orderDate }}</span>
                </div>
                <div class="customer mb-3 v-select-container">
                    <label for="inputCustomer" class="form-label ms-1">Покупатель</label>
                    <v-select v-model="selectedCustomer" :options="customers" value="id"
                              :searchable="true" placeholder="Покупатель" :get-option-label="getCustomerLabel"
                              id="inputCustomer" @input="handleUserChanged">
                        <span slot="no-options">Нет подходящих покупателей</span>
                        <template slot="selected-option" slot-scope="option">
                            <div>
                                {{ option.name }}
                            </div>
                        </template>
                        <template slot="option" slot-scope="option">
                            <div class="d-flex flex-wrap">
                                <span class="me-2 text-wrap">{{ option.name }}</span>
                                <span v-if="option.info" class="text-wrap">{{ option.info }}</span>
                            </div>
                        </template>
                    </v-select>
                    <p v-show="errors.customer" class="alert alert-danger py-2 my-2">{{ errors.customer }}</p>
                </div>
                <div v-if="selectedCustomer" class="ms-1 mb-3">
                    <div v-show="selectedCustomer.phone_number">
                        <span>Телефон:</span>
                        <span>{{ formatPhone(selectedCustomer.phone_number) }}</span>
                    </div>
                    <div v-show="selectedCustomer.info">
                        <span>Дополнительно:</span>
                        <span>{{ selectedCustomer.info }}</span>
                    </div>
                </div>
                <div class="car mb-3 v-select-container">
                    <label for="inputCar" class="form-label ms-1">Автомобиль</label>
                    <v-select v-model="selectedCar" :options="cars" value="id"
                              :searchable="true" placeholder="Автомобиль" :get-option-label="getCarLabel"
                              id="inputCar">
                        <span slot="no-options">Нет подходящих покупателей</span>
                        <template slot="selected-option" slot-scope="option">
                            <div>
                                {{ option.name }}
                            </div>
                        </template>
                        <template slot="option" slot-scope="option">
                            <div class="d-flex flex-wrap">
                                <span class="me-2 text-wrap">{{ option.name }}</span>
                                <span v-if="option.modification" class="text-wrap">{{ option.modification }}</span>
                            </div>
                            <div v-if="option.vin_code" class="me-2 text-wrap">{{ option.vin_code }}</div>
                        </template>
                    </v-select>
                    <p v-show="errors.brand" class="alert alert-danger py-2 my-2">{{ errors.brand }}</p>
                </div>
                <div v-if="selectedCar" class="ms-1 mb-3">
                    <div v-show="selectedCar.vin_code">
                        <span>VIN:</span>
                        <span>{{ selectedCar.vin_code }}</span>
                    </div>
                    <div v-show="selectedCar.modification">
                        <span>Модификация:</span>
                        <span class="text-wrap">{{ selectedCar.modification }}</span>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="textareaComment" class="form-label ms-1">Комментарий</label>
                    <textarea v-model="comment" class="form-control" placeholder="Комментарий" id="textareaComment" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label for="textareaAddress" class="form-label ms-1">Адрес</label>
                    <textarea v-model="address" class="form-control" placeholder="Адрес" id="textareaAddress" rows="2"></textarea>
                </div>
                <div class="mb-3">
                    <label for="textareaNote" class="form-label ms-1">Примечание администратора</label>
                    <textarea v-model="note" class="form-control" placeholder="Примечание администратора" id="textareaNote" rows="3"></textarea>
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
import Designer from "../../store/Designer"
import PurchaseItem from "../../components/PurchaseItem.vue"

export default {
    name: "Create",

    components: {
        PurchaseItem,
        'v-select': vSelect,
    },

    data() {
        return {
            order: null,
            selectedCustomer: null,
            customers: [],
            selectedCar: null,
            cars: [],
            isPaid: false,
            total: 0,
            subTotal: 0,
            purchaseTotal: 0,
            discount: null,
            selectedStatus: null,
            statuses: [],
            selectedLabels: null,
            labels: [],
            providers: [],
            selectedPaymentMethod: null,
            paymentMethods: [],
            products: [],
            selectedProduct: null,
            purchases: [],
            comment: '',
            address: '',
            selectedDelivery: null,
            deliveryPrice: null,
            deliveries: [],
            note: '',
            orderDate: null,
            isDataLoaded: true,
            errors: {
                purchases: '',
                customer: '',
                status: '',
                delivery: '',
                deliveryPrice: '',
                paymentMethod: '',
            }
        }
    },

    mounted() {
        this.getOrder(this.$route.params.id)
        this.getStatuses()
        this.getLabels()
        this.getProviders()
        this.getCustomers()
        this.getProducts()
        this.getPaymentMethods()
        this.getDeliveries()
    },

    methods: {

        getProductLabel(option) {
            if (!option) return ''
            return option.name + option.part_number
        },

        getCustomerLabel(option) {
            if (!option) return ''
            return option.name + option.info
        },

        getCarLabel(option) {
            if (!option) return ''
            return option.name + option.vin_code
        },

        formatPhone(phone) {
            return Designer.formatPhone(phone)
        },

        handleUserChanged() {
            this.getCars()
            this.calcDiscount()
            this.updateTotals()
        },

        handleDeliveryChanged() {
            if (this.selectedDelivery && this.selectedDelivery.id < 2) {
                this.deliveryPrice = null
            }
            this.updateTotals()
        },

        updatePurchases(product) {
            if (!product) return
            let purchase = this.purchases.find(purchase => purchase.product_id === product.id)
            if (purchase) {
                purchase.qty++
            } else {
                purchase = {
                    product: product,
                    product_id: product.id,
                    price: product.price,
                    qty: 1,
                    purchase_price: product.purchase_price ?? 0,
                }
                this.purchases.push(purchase)
            }
            setTimeout(() => {
                this.calcDiscount()
                this.updateTotals()
            }, 100)
            this.selectedProduct = null
        },

        updatePurchaseProvider(purchase, provider) {
            if (!(purchase)) return
            purchase.provider = provider
            purchase.provider_id = provider ? provider.id : null
        },

        updatePurchaseLabel(purchase, label) {
            if (!(purchase)) return
            purchase.label = label
            purchase.label_id = label ? label.id : null
        },

        updateTotals() {
            let purchaseTotal = 0
            let subTotal = 0
            let total = 0

            this.purchases.forEach(purchase => {
                purchaseTotal += Number(purchase.purchase_price * purchase.qty)
                subTotal += Number(purchase.price * purchase.qty)
            })
            this.purchaseTotal = Designer.formatPrice(purchaseTotal)
            this.subTotal = Designer.formatPrice(subTotal)

            total = subTotal
            if (this.selectedDelivery && this.selectedDelivery.id !==1) {
                total += Number(this.deliveryPrice)
            }
            if (this.discount) {
                total -= Number(this.discount)
            }
            this.total = Designer.formatPrice(total)
        },

        deletePurchase(id) {
            this.purchases = this.purchases.filter(purchase => purchase.product_id !== id)
            setTimeout(() => {
                this.calcDiscount()
                this.updateTotals()
            }, 100)
        },

        calcDiscount() {
            if (!(this.selectedCustomer && this.selectedCustomer.group)) {
                this.discount = 0
                return
            }
            this.updateTotals()
            let percent = Number(this.selectedCustomer.group.discount)
            this.discount = (percent / 100 * this.subTotal).toFixed(2)
        },

        validate() {
            if (this.discount < 0) {
                this.discount = 0
            } else if (this.discount > this.total) {
                this.discount = this.total
            } else {
                this.discount = parseFloat(this.discount).toFixed(2)
            }
            this.updateTotals()
        },

        isColorDark(color) {
            return Designer.isColorDark(color)
        },

        async getOrder(id) {
            try {
                const res = await axios.get(`/api/orders/${id}`)
                this.order = res.data.data
                this.selectedCustomer = this.order.user
                this.selectedCar = this.order.car
                this.isPaid = this.order.is_paid
                this.total = this.order.total
                this.discount = this.order.discount
                this.selectedStatus = this.order.status
                this.selectedLabels = this.order.labels
                if (this.order.is_paid) {
                    this.selectedPaymentMethod = {
                        id: this.order.payment_method_id,
                        name: this.order.payment_method_name,
                    }
                }
                this.purchases = this.order.purchases
                this.comment = this.order.comment
                this.address = this.order.address
                this.selectedDelivery = {
                    id: this.order.delivery_id,
                    name: this.order.delivery_name,
                }
                this.deliveryPrice = this.order.delivery_price
                this.note = this.order.note
                this.orderDate = Designer.formatDateTime(this.order.date)

                this.updateTotals()
                await this.getCars()
            } catch (err) {
                console.error('Не удалось загрузить данные')
            }
        },

        async getStatuses() {
            try {
                const res = await axios.get('/api/order_statuses')
                this.statuses = res.data.data
            } catch (err) {
                console.error('Не удалось загрузить данные')
            }
        },

        async getLabels() {
            try {
                const res = await axios.get('/api/labels')
                this.labels = res.data.data
            } catch (err) {
                console.error('Не удалось загрузить данные')
            }
        },

        async getProviders() {
            try {
                const res = await axios.get('/api/providers')
                this.providers = res.data.data
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
                        per_page: 30,
                    }
                })
                this.customers = res.data.data
            } catch (err) {
                console.error('Не удалось загрузить данные')
            }
        },

        async getProducts(keyword) {
            try {
                const res = await axios.get('/api/products', {
                    params: {
                        keyword: keyword,
                        page: 1,
                        per_page: 10,
                    }
                })
                this.products = res.data.data
            } catch (err) {
                console.error('Не удалось загрузить данные')
            }
        },

        async getCars() {
            if (!this.selectedCustomer) return
            try {
                const res = await axios.get('/api/cars')
                this.cars = res.data.data.filter(car => car.customer_id === this.selectedCustomer.id)
            } catch (err) {
                console.error('Не удалось загрузить данные')
            }
        },

        async getPaymentMethods() {
            try {
                const res = await axios.get('/api/orders/details/payment_methods')
                this.paymentMethods = Object.entries(res.data).map(([id, name]) => ({ id, name }))
            } catch (err) {
                console.error('Не удалось загрузить данные')
            }
        },

        async getDeliveries() {
            try {
                const res = await axios.get('/api/orders/details/deliveries')
                this.deliveries = Object.entries(res.data).map(([id, name]) => ({ id, name }))
            } catch (err) {
                console.error('Не удалось загрузить данные')
            }
        },

        checkForm() {
            this.errors.purchases = ''
            this.errors.customer = ''
            this.errors.status = ''
            this.errors.delivery = ''
            this.errors.deliveryPrice = ''
            this.errors.paymentMethod = ''
            if (!this.purchases.length) {
                this.errors.purchases = 'Не добавлены товары'
            }
            if (!this.selectedCustomer) {
                this.errors.customer = 'Не указан покупатель'
            }
            if (!this.selectedStatus) {
                this.errors.status = 'Не указан статус заказа'
            }
            if (this.selectedDelivery) {
                if (this.selectedDelivery.id > 1) {
                    if (this.deliveryPrice === null) {
                        this.errors.deliveryPrice = 'Не указана цена доставки'
                    }
                }
            } else {
                this.errors.delivery = 'Не указан способ доставки'
            }
            if (this.isPaid) {
                if (!this.selectedPaymentMethod) {
                    this.errors.paymentMethod = 'Не указан способ оплаты'
                }
            }

            if (this.errors.purchases || this.errors.customer || this.errors.status || this.errors.delivery || this.errors.paymentMethod || this.errors.deliveryPrice) {
                return
            }
            this.update()
        },

        async update() {
            try {
                let labelIds = null
                if (this.selectedLabels) {
                    labelIds = this.selectedLabels.map(label => label.id)
                }
                this.purchases.forEach(purchase => delete purchase.product)

                const data = {
                    user_id: this.selectedCustomer.id,
                    car_id: this.selectedCar ? this.selectedCar.id : null,
                    is_paid: this.isPaid,
                    total_price: this.total,
                    discount: this.discount,
                    status_id: this.selectedStatus.id,
                    labels: labelIds,
                    payment_method: this.selectedPaymentMethod ? this.selectedPaymentMethod.id : null,
                    purchases: this.purchases,
                    comment: this.comment,
                    address: this.address,
                    delivery: this.selectedDelivery.id,
                    delivery_price: this.deliveryPrice,
                    note: this.note,
                }

                this.isDataLoaded = false
                await axios.patch(`/api/orders/${this.$route.params.id}`, data)
                this.isDataLoaded = true
                await this.$router.push({name: 'order.index'})
            } catch (err) {
                console.error('Не удалось загрузить данные')
            }
        }
    },
}
</script>

<style scoped>

</style>
