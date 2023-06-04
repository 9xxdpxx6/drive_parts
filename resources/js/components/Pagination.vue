<template>
    <div>
        <div class="pagination d-flex justify-content-between align-items-center">
            <nav aria-label="Page navigation example">
                <ul class="pagination mb-0">
                    <li class="page-item" :class="pagination.current_page === 1 ? 'disabled' : ''">
                        <a @click.prevent="getItems(pagination.current_page - 1)" class="page-link" href="#" aria-label="Previous">
                                    <span aria-hidden="true">
                                        <i class="bi bi-chevron-left"></i>
                                    </span>
                        </a>
                    </li>
                    <li v-for="link in pagination.links" class="page-item">
                        <template v-if="Number(link.label) &&
                                                (pagination.current_page - link.label < 3 &&
                                                pagination.current_page - link.label > -3) ||
                                                Number(link.label) === 1 ||
                                                Number(link.label) === pagination.last_page">
                            <a @click.prevent="getItems(link.label)" class="page-link" :class="link.active ? 'active' : ''" href="#">{{ link.label }}</a>
                        </template>
                        <template v-if="Number(link.label) &&
                                                pagination.current_page !== 4 &&
                                                (pagination.current_page - link.label === 3) ||
                                                Number(link.label) &&
                                                pagination.current_page !== pagination.last_page - 3 &&
                                                (pagination.current_page - link.label === -3)">
                            <a class="page-link disabled" href="#">...</a>
                        </template>
                    </li>
                    <li class="page-item" :class="pagination.current_page === pagination.last_page ? 'disabled' : ''">
                        <a @click.prevent="getItems(pagination.current_page + 1)" class="page-link" href="#" aria-label="Next">
                                    <span aria-hidden="true">
                                        <i class="bi bi-chevron-right"></i>
                                    </span>
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="page-total">
                <span>Отображается:</span>
                <span>{{ pagination.from }}</span>
                <span>&#8211;</span>
                <span>{{ pagination.to }}</span>
                <span class="ms-3">Всего:</span>
                <span>{{ pagination.total }}</span>
            </div>
        </div>
    </div>
</template>

<script>
import vSelect from "vue-select"

export default {
    name: "Pagination",

    components: {
        'v-select': vSelect
    },

    props: {
        page: {
            type: Number,
            required: true,
        },
        perPage: {
            type: Number,
            required: true,
        },
        pagination: {
            type: Object,
            required: true,
        },
        getItems: {
            type: Function,
            required: true,
        }
    },

}
</script>

<style scoped>

</style>
