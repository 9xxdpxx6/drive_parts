<template>
    <div>
        <div class="title d-flex">
            <h3 class="me-auto mb-4">Группы покупателей</h3>
            <router-link :to="{name: 'group.create'}">
                <div class="d-flex align-items-center btn btn-primary px-3">
                    <i class="bi bi-plus-lg fs-5"></i>
                    <span class="d-none d-md-block ms-2">Добавить</span>
                </div>
            </router-link>
        </div>
        <div class="controls mt-2 mb-4">
            <div class="row">
                <div class="col-12 mb-2">
                    <input v-model="keyword" @input="getGroups()" type="text" class="form-control" placeholder="Поиск">
                </div>
            </div>
        </div>
        <Preloader v-if="isLoading"></Preloader>
        <div v-else class="groups">
            <div class="heading d-flex">
                <h4 class="ms-md-2">Наименование</h4>
                <h4 class="ms-auto me-md-4 pe-5">Скидка</h4>
            </div>
            <div class="row" v-if="groups.length">
                <div class="group bg-transparent mb-3" v-for="(group, index) in groups" :key="index">
                    <div class="d-flex shadow-sm rounded-3 overflow-hidden align-items-center" :class="group.is_active ? 'bg-white' : 'bg-secondary'">
                        <router-link :to="{name: 'group.edit', params: {alias: group.alias}}"
                                     class="text-decoration-none text-dark flex-grow-1 my-2 me-3">
                            <div class="group-title ps-4">
                                <h4>{{ group.name }}</h4>
                            </div>
                        </router-link>
                        <div>
                            <h5>{{ group.discount }}<span class="ms-1">%</span></h5>
                        </div>
                        <ConfirmDelete :item="group" table="groups" :update-content="getGroups" :related="group.users_qty"></ConfirmDelete>
                    </div>
                </div>
            </div>
            <h3 v-else>Результаты отсутствуют</h3>
        </div>
    </div>
</template>

<script>
import Preloader from "../../../components/Preloader.vue"
import ConfirmDelete from "../../../components/ConfirmDelete.vue";

export default {
    name: "Index",
    components: {
        Preloader,
        ConfirmDelete,
    },

    data() {
        return {
            isLoading: true,
            groups: [],
            keyword: '',
        }
    },

    mounted() {
        this.getGroups()
    },

    methods: {

        async getGroups() {
            this.isLoading = true

            try {
                const res = await axios.get('/api/groups');
                this.groups = res.data.data
                this.isLoading = false
            } catch (err) {
                console.error('Не удалось загрузить данные')
            }

            if (this.keyword.length) {
                this.groups = this.groups.filter(group => group.name.toLowerCase().includes(this.keyword.toLowerCase()))
            }

            this.isLoading = false
        },

    }
}
</script>

<style scoped>

</style>
