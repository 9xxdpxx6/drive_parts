<template>
    <div>
        <div class="controls container w-50">
            <div class="row mb-5">
                <div>
                    <button @click.prevent="print" class="btn btn-primary me-3">Печать</button>
                    <button @click.prevent="saveAsPDF(order.id)" class="btn btn-info">Сохранить в PDF</button>
                </div>
            </div>
        </div>
        <div class="container w-50 order-container">
            <div v-if="order" class="print-order">
                <div class="row">
                    <div class="col-6">
                        <h2>
                            <span>Заявка №</span>
                            <span>{{ order.id }}</span>
                        </h2>
                        <h5>
                            <span>от</span>
                            <span>{{ orderDate }}</span>
                        </h5>
                    </div>
                    <div class="col-6">
                        <!--                    <span>DRIVE PARTS Автозапчасти Консультация Подбор Доставка 350041, Краснодарский край, г.Краснодар, ул. Красная, дом № 176/1 8(999) 88-88-888</span>-->
                        <div>DRIVE PARTS</div>
                        <div>Автозапчасти. Консультация. Подбор. Доставка.</div>
                        <div>350041, Краснодарский край, г.Краснодар, ул. Красная, дом № 176/1</div>
                        <div>Телефон: +7 (999) 888-8888</div>
                    </div>
                    <div class="col-6">
                        <h5>
                            <span>Получатель:</span>
                            <span>{{ order.user.name }}</span>
                        </h5>
                        <h6>
                            <span>Телефон:</span>
                            <span>{{ formatPhone(order.user.phone_number) }}</span>
                        </h6>
                        <h6 v-if="order.car">
                            <span>{{ order.car.name }}</span>
                            <span>{{ order.car.modification }}</span>
                        </h6>
                    </div>
                </div>
                <div class="row mt-5 d-flex flex-row-reverse">
                    <div class="col-12">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">№</th>
                                <th scope="col">Наименование</th>
                                <th scope="col" class="text-end">Цена</th>
                                <th scope="col" class="text-end">Количество</th>
                                <th scope="col" class="text-end">Сумма</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(purchase, index) in order.purchases">
                                <th scope="row">{{ index + 1 }}</th>
                                <td class="text-break text-wrap" style="max-width: 360px;">
                                    <div class="product-title text-break">
                                        <span>{{ purchase.product.name }}</span>
                                    </div>
                                </td>
                                <td class="text-end">{{ purchase.price | formatNumber }} руб</td>
                                <td class="text-end">{{ purchase.qty | formatNumber }} шт</td>
                                <td class="text-end">{{ formatPrice(purchase.price, purchase.qty) | formatNumber }} руб</td>
                            </tr>
                            <tr><br></tr>
                            <tr v-if="order.delivery_price" class="">
                                <th scope="row">{{ order.purchases.length + 1 }}</th>
                                <td>{{ order.delivery_name }}</td>
                                <td class="text-end">{{ order.delivery_price | formatNumber }} руб</td>
                                <td class=""></td>
                                <td class="text-end">{{ order.delivery_price | formatNumber }} руб</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-6">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div>Итого:</div>
                                <div>Скидка:</div>
                                <div>Способ оплаты:</div>
                                <div>Всего к оплате:</div>
                            </div>
                            <div class="text-end">
                                <div>{{ subTotal }} руб</div>
                                <div>{{ order.discount }} руб</div>
                                <div>{{ order.payment_method_name }}</div>
                                <div>{{ order.total_price }} руб</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-6 d-flex">
                        <span>Товар получен</span>
                        <div class="ms-2 col-3 border-bottom border-dark"></div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12 warranty lh-sm">
                        <div>
                            Гарантийный срок на неоригинальные запчасти (кроме электрических) составляет 14 дней, на неоригинальные электрические запчасти ограничивается ее работоспособностью в момент установки.
                        </div>
                        <div>
                            Гарантия распространяется на оригинальные электрические части при условии установки товара на сервисных станциях официальных дилеров соответствующего производителя.
                        </div>
                        <div>
                            Возврат товара надлежащего качества возможен только в случае сохранения целостности и чистоты как упаковки, так и Товара. Купленный товар может быть возвращен или обменен на равноценный в 14-дневний срок.
                        </div>
                        <div>
                            Гарантия распространяется только на изделия, установленные в сертифицированных станциях технического обслуживания, при наличии пакета документов (Заказ-наряд на выполненные работы. Акт дефектовки. Копия сертификата на вид деятельности).
                        </div>
                        <div>
                            Гарантия не распространяется при наличии неисправностей обусловленных:
                        </div>
                        <ul class="list-unstyled">
                            <li>- Установкой изделия на автомобиль не предусмотренный рекомендациями завода производителя;</li>
                            <li>- Нарушением владельца правил эксплуатации;</li>
                            <li>- Нарушением владельцем сохранности пломб, ремонтом изделия посторонними лицами;</li>
                            <li>- Механическими повреждениями изделия;</li>
                            <li>- Действиями непреодолимой силы (пожар, аварии, стихийные бедствия и т. п.);</li>
                            <li>- Вышедшие из строя вследствие естественного износа.</li>
                        </ul>
                        <div>
                            Все транспортные расходы по доставке деталей, а также снятию и установке на Станциях Технического Обслуживания возврату не подлежат.
                        </div>
                        <div>
                            Оплата товара означает согласие с условиями гарантии и возврата.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Designer from "../../store/Designer"
import html2pdf from 'html2pdf.js'

export default {
    name: "Print",

    data() {
        return {
            order: null,
        }
    },

    mounted() {
        this.getOrder(this.$route.params.id)
    },

    methods: {

        async getOrder(id) {
            try {
                const res = await axios.get(`/api/orders/${id}`)
                this.order = res.data.data
            } catch (err) {
                console.error('Не удалось загрузить данные')
            }
        },

        formatPhone(phone) {
            return Designer.formatPhone(phone)
        },

        formatPrice(price, qty) {
            return Designer.formatPrice(price, qty)
        },

        print() {
            window.print()
        },

        saveAsPDF(id) {
            const order = document.querySelector('.print-order')
            // order.classList.remove('w-50')

            html2pdf()
                .set({
                    margin: 10,
                    filename: `order_${id}.pdf`,
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2 },
                    jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
                })
                .from(order)
                .save()

            // setTimeout(() => {
            //     order.classList.add('w-50')
            // }, 1000)
        },

    },

    computed: {
        orderDate() {
            return Designer.formatDateTime(this.order.date)
        },

        subTotal() {
            let subTotal = 0
            this.order.purchases.forEach(purchase => {
                subTotal += purchase.price * purchase.qty
            })
            return this.formatPrice(subTotal)
        }
    }

}
</script>

<style>

.warranty {
    text-align: justify;
    font-size: 10px;
}

@media print {
    header,
    aside,
    .controls {
        display: none;
    }

    .order-container {
        width: 100% !important;
    }
}

</style>
