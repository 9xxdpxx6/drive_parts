<template>
    <tr v-if="purchase.product" class="mobile-vertical">
        <th scope="row" class="d-none d-lg-block">{{ index + 1 }}</th>
        <td class="preview">
            <div class="image d-md-none mb-2">
                <img :src="purchase.product.preview_image ? purchase.product.preview_image : '/assets/no_image.svg'" class="rounded-3" width="50" :alt="purchase.product.part_number">
            </div>
            <div class="image d-none d-md-block me-2">
                <img :src="purchase.product.preview_image ? purchase.product.preview_image : '/assets/no_image.svg'" class="rounded-3" width="50" :alt="purchase.product.part_number">
            </div>
        </td>
        <td class="product-title text-break text-wrap">
            <router-link :to="{name: 'product.edit', params: {alias: purchase.product.alias}}" target="_blank" class="text-decoration-none text-dark">
                <div class="product-title text-break">
                    <span>{{ purchase.product.name }}</span>
                </div>
                <div class="text-secondary">
                    <span>Артикул:</span>
                    <span>{{ purchase.product.part_number }}</span>
                </div>
            </router-link>
        </td>
        <td class="td-input">
            <input v-model="purchase.price" @input="updateTotals" type="number" class="form-control" step="1" min="0" aria-label="Price">
        </td>
        <td class="td-input">
            <input v-model="purchase.qty" @input="updateTotals" type="number" class="form-control" step="1" min="1" aria-label="Qty">
        </td>
        <td class="td-input">
            <input v-model="purchase.purchase_price" @input="updateTotals" type="number" class="form-control" step="1" min="0" aria-label="PurchasePrice">
        </td>
        <td class="select-container">
            <v-select v-model="selectedPurchaseProvider" :options="providers" label="code" value="id" :id="`provider${index}`"
                      :searchable="true" placeholder="Поставщик" class="vs-purchase d-flex" @input="updateProvider(purchase, selectedPurchaseProvider)">
                <span slot="no-options">Нет подходящих поставщиков</span>
                <template slot="selected-option" slot-scope="option">
                    <div style="max-width: 50px;">
                        {{ option.code }}
                    </div>
                </template>
                <template slot="option" slot-scope="option">
                    <strong>{{ option.code }}</strong>
                    <span>{{ option.name }}</span>
                </template>
            </v-select>
        </td>
        <td class="select-container">
            <v-select v-model="selectedPurchaseLabel" :options="labels" label="name" value="id" :id="`label${index}`"
                      :searchable="true" placeholder="Метка" class="vs-purchase d-flex" @input="updateLabel(purchase, selectedPurchaseLabel)">
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
        </td>
        <td class="text-end pt-3">{{ formatPrice(purchase.price, purchase.qty) | formatNumber }} руб</td>
        <td class="p-0">
            <button class="btn btn-link align-self-center shadow-none m-0 p-0" @click="deletePurchase(purchase.product_id)" type="button">
                <i class="bi bi-x fs-1 text-danger"></i>
            </button>
        </td>
    </tr>
</template>


<script>
import vSelect from "vue-select"
import Designer from "../store/Designer"

export default {
    name: "PurchaseItem",

    components: {
        'v-select': vSelect,
    },

    props: {
        index: {
            type: Number,
            required: true,
        },
        purchase: {
            type: Object,
            required: true,
        },
        labels: {
            type: Array,
            required: true,
        },
        providers: {
            type: Array,
            required: true,
        },
        updateTotals: {
            type: Function,
            required: true,
        },
        updateProvider: {
            type: Function,
            required: true,
        },
        updateLabel: {
            type: Function,
            required: true,
        },
        deletePurchase: {
            type: Function,
            required: true,
        },
    },

    data() {
        return {
            selectedPurchaseProvider: null,
            selectedPurchaseLabel: null,
        }
    },

    mounted() {
        this.getProvider()
        this.getLabel()
    },

    updated() {
        this.getProvider()
        this.getLabel()
    },

    methods: {

        isColorDark(color) {
            return Designer.isColorDark(color)
        },

        formatPrice(price, qty) {
            return Designer.formatPrice(price, qty)
        },

        getProvider() {
            if (this.$props.purchase) {
                this.selectedPurchaseProvider = this.$props.purchase.provider
            }
        },

        getLabel() {
            if (this.$props.purchase) {
                this.selectedPurchaseLabel = this.$props.purchase.label
            }
        },

    }
}
</script>

<style scoped>

.preview {
    max-width: 60px;
}
.product-title {
    max-width: 360px;
}
.td-input {
    max-width: 110px;
}

@media (max-width: 768px) {
    .mobile-vertical {
        display: block;
        margin-bottom: 20px;
    }

    .mobile-vertical > th,
    .mobile-vertical > td {
        display: block;
        width: 100%;
        text-align: left;
    }

    .preview ,
    .product-title,
    .td-input {
        max-width: none;
    }


}

</style>
