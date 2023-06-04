<template>
    <div class="nested-categories">
        <div class="category bg-transparent mb-3" v-for="(category, index) in categories" :key="index">
            <div class="d-flex shadow-sm rounded-3 overflow-hidden align-items-center"
                 :class="category.is_active ? 'bg-white' : 'bg-secondary'">
                <div class="image">
                    <img :src="category.preview_image ? category.preview_image : '/assets/no_image.svg'"
                         class="rounded-3" width="70" :alt="category.name">
                </div>
                <router-link :to="{name: 'category.edit', params: {alias: category.alias}}"
                             class="text-decoration-none text-dark flex-grow-1 my-2 me-3">
                    <div class="category-title ps-4">
                        <h4>{{ category.name }}</h4>
                    </div>
                </router-link>
                <button v-if="category.children.length > 0" class="btn-show-children"
                        :class="category.is_active ? 'text-secondary' : 'text-white'" type="button"
                        data-bs-toggle="collapse" :data-bs-target="`#collapseCategory_${category.id}`"
                        aria-expanded="false" :aria-controls="`collapseCategory_${category.id}`">
                    <i class="bi bi-chevron-down fs-1"></i>
                </button>
                <ConfirmDelete :item="category" table="categories" :update-content="getCategories" :related="category.children.length"></ConfirmDelete>
            </div>
            <div v-if="category.children.length > 0" class="collapse" :id="`collapseCategory_${category.id}`">
                <CategoryItem class="child mt-3" :categories="category.children"
                              :get-categories="getCategories"></CategoryItem>
            </div>
        </div>
    </div>
</template>

<script>
import ConfirmDelete from "./ConfirmDelete.vue";

export default {
    name: "CategoryItem",
    components: {ConfirmDelete},

    props: {
        categories: {
            type: Array,
            required: true
        },
        getCategories: {
            type: Function,
            required: true,
        }
    },
}
</script>

<style scoped>
.btn-show-children {
    outline: none;
    background: transparent;
    border: none;
    width: 70px;
    transition: transform 0.3s ease;
}

.child {
    margin-left: 15px;
}

@media (min-width: 768px) {
    .child {
        margin-left: 90px;
    }
}

</style>
