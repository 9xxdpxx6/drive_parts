<template xmlns="http://www.w3.org/1999/html">
    <div>
        <section class="mb-5">
            <h3 class="mb-3">Настройки заказов</h3>
            <div class="container-fluid px-0">
                <div class="row">
                    <div class="col-12 col-lg-6">
                        <order-labels></order-labels>
                    </div>
                    <div class="col-12 col-lg-6 mt-5 mt-lg-0">
                        <order-statuses></order-statuses>
                    </div>
                </div>
            </div>
        </section>
        <section>
            <h3 class="mb-3">Настройки покупателей</h3>
            <div class="container-fluid px-0">
                <div class="row">
                    <div class="col-12">
                        <groups></groups>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
import OrderLabels from "./Label/Index.vue"
import OrderStatuses from "./OrderStatus/Index.vue"
import Groups from "./Group/Index.vue"

export default {
    name: "Index",

    components: {
        OrderLabels,
        OrderStatuses,
        Groups,
    }
}
</script>

<style scoped>

</style>
