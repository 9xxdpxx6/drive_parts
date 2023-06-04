<template>
    <div>
        <div class="title d-flex">
            <h3 class="me-auto mb-4">Поставщики</h3>
            <router-link :to="{name: 'provider.create'}">
                <div class="d-flex align-items-center btn btn-primary px-3">
                    <i class="bi bi-plus-lg fs-5"></i>
                    <span class="d-none d-md-block ms-2">Добавить</span>
                </div>
            </router-link>
        </div>
        <div class="controls mt-2 mb-4">
            <div class="row">
                <div class="col-12 mb-2">
                    <input v-model="keyword" @input="getProviders()" type="text" class="form-control" placeholder="Поиск">
                </div>
            </div>
        </div>
        <Preloader v-if="isLoading"></Preloader>
        <div v-else class="providers">
            <div class="heading d-flex">
                <h4 class="ms-3">Код</h4>
                <h4 class="ms-md-4 ps-3">Наименование</h4>
            </div>
            <div class="row" v-if="providers.length">
                <div class="provider bg-transparent mb-3" v-for="(provider, index) in providers" :key="index">
                    <div class="d-flex shadow-sm rounded-3 overflow-hidden align-items-center" :class="provider.is_active ? 'bg-white' : 'bg-secondary'">
                        <div class="thumb-color d-flex align-items-center justify-content-center align-self-stretch rounded-3" :style="`background-color: ${provider.color};`">
                            <div :class="isColorDark(provider.color) ? 'text-white' : 'text-black'">{{ provider.code }}</div>
                        </div>
                        <router-link :to="{name: 'provider.edit', params: {code: provider.code}}"
                                     class="text-decoration-none text-dark flex-grow-1 my-2 me-3">
                            <div class="provider-title ps-4">
                                <h4>{{ provider.name }}</h4>
                            </div>
                        </router-link>
                        <ConfirmDelete :item="provider" table="providers" :update-content="getProviders"></ConfirmDelete>
                    </div>
                </div>
            </div>
            <h3 v-else>Результаты отсутствуют</h3>
        </div>
    </div>
</template>

<script>
import Preloader from "../../components/Preloader.vue"
import ConfirmDelete from "../../components/ConfirmDelete.vue";
import Designer from "../../store/Designer";

export default {
    name: "Index",
    components: {
        Preloader,
        ConfirmDelete,
    },

    data() {
        return {
            isLoading: true,
            providers: [],
            keyword: '',
        }
    },

    mounted() {
        this.getProviders()
    },

    methods: {
        async getProviders() {
            this.isLoading = true

            try {
                const res = await axios.get('/api/providers');
                this.providers = res.data.data
            } catch (err) {
                console.error('Не удалось загрузить данные')
            }

            if (this.keyword.length) {
                this.providers = this.providers.filter(provider => provider.name.toLowerCase().includes(this.keyword.toLowerCase()) || provider.code.includes(this.keyword.toUpperCase()))
            }

            this.isLoading = false
        },

        isColorDark(color) {
            return Designer.isColorDark(color)
        }

    }
}
</script>

<style scoped>

</style>
