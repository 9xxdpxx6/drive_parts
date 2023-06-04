<template>
    <div>
        <div class="title d-flex">
            <h3 class="me-auto mb-4">Категории</h3>
            <router-link :to="{name: 'category.create'}">
                <div class="d-flex align-items-center btn btn-primary px-3">
                    <i class="bi bi-plus-lg fs-5"></i>
                    <span class="d-none d-md-block ms-2">Добавить</span>
                </div>
            </router-link>
        </div>
        <div class="controls mt-2 mb-4">
            <div class="row">
                <div class="col-12 mb-2">
                    <input v-model="keyword" @input="getCategories()" type="text" class="form-control" placeholder="Поиск">
                </div>
            </div>
        </div>
        <Preloader v-if="isLoading"></Preloader>
        <div v-else class="categories">
            <div v-if="categories.length" class="heading d-flex">
                <h4 class="ms-2">Фото</h4>
                <h4 class="ms-md-4 ps-2">Наименование</h4>
            </div>
            <div class="row">
                <CategoryItem v-if="categories.length" class="col-12" :categories="categories" :get-categories="getCategories"></CategoryItem>
                <h3 v-else>Результаты отсутствуют</h3>
            </div>
        </div>
    </div>
</template>

<script>
import Preloader from "../../components/Preloader.vue"
import CategoryItem from "../../components/CategoryItem.vue"

export default {
    name: "Index",
    components: {
        CategoryItem,
        Preloader,
    },

    data() {
        return {
            isLoading: true,
            categories: [],
            keyword: '',
        }
    },

    mounted() {
        this.getCategories()
    },

    methods: {
        
        async getCategories() {
            this.isLoading = true

            try {
                const res = await axios.get('/api/categories')
                this.categories = res.data.data.filter(category => !category.parent_id)
                this.isLoading = false
            } catch (err) {
                console.error('Не удалось загрузить данные')
            }

            if (this.keyword.length) {
                this.categories = this.categories.filter(category => {
                    return this.checkAndRemoveChildren(category, this.keyword.toLowerCase())
                })
            }

            this.isLoading = false
        },

        checkAndRemoveChildren(category, keyword) {
            let hasMatch = false

            if (category.name.toLowerCase().includes(keyword)) {
                hasMatch = true
            }

            if (category.children) {
                category.children = category.children.filter(child => {
                    const childHasMatch = this.checkAndRemoveChildren(child, keyword)
                    hasMatch = hasMatch || childHasMatch
                    return childHasMatch
                })
            }

            return hasMatch
        }

    }
}
</script>

<style scoped>

</style>
