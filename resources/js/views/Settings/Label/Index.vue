<template>
    <div>
        <div class="title d-flex">
            <h3 class="me-auto mb-4">Метки заказов</h3>
            <a @click.prevent="changeEditLabelId(null)" href="#">
                <div class="d-flex align-items-center btn btn-primary px-3">
                    <i class="bi bi-plus-lg fs-5"></i>
                    <span class="d-none d-md-block ms-2">Добавить</span>
                </div>
            </a>
        </div>
        <div class="controls mt-2 mb-4">
            <div class="row">
                <div class="col-12 mb-2">
                    <input v-model="keyword" @input="getLabels()" type="text" class="form-control" placeholder="Поиск">
                </div>
            </div>
        </div>
        <Preloader v-if="isLoading"></Preloader>
        <div v-else class="labels">
            <div class="heading d-flex">
                <h4 class="ms-md-2">Цвет</h4>
                <h4 class="ms-md-4 ps-2">Наименование</h4>
            </div>
            <div class="row" v-if="labels.length">
                <template v-for="(label, index) in labels">
                    <div class="label bg-transparent mb-3" :class="isEdit(label.id) ? 'd-none' : ''">
                        <div class="d-flex shadow-sm rounded-3 overflow-hidden align-items-center"
                             :class="label.is_active ? 'bg-white' : 'bg-secondary'">
                            <div
                                class="thumb-color d-flex align-items-center justify-content-center align-self-stretch rounded-3"
                                :style="`background-color: ${label.color};`">
                                <div class="text-uppercase px-md-3"
                                     :class="isColorDark(label.color) ? 'text-white' : 'text-black'">
                                    <span class="d-none d-md-block">{{ label.color }}</span>
                                    <span class="d-block d-md-none mx-3 opacity-0">000</span>
                                </div>
                            </div>
                            <div class="text-decoration-none text-dark flex-grow-1 my-2 me-3">
                                <div class="label-title ps-4">
                                    <h4 class="text-break">{{ label.name }}</h4>
                                </div>
                            </div>
                            <div class="align-self-center">
                                <button @click.prevent="changeEditLabelId(label)"
                                        class="btn btn-outline-primary d-flex align-items-center"
                                        data-bs-toggle="tooltip" title="Редактировать">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                            </div>
                            <ConfirmDelete :item="label" table="labels" :update-content="getLabels"></ConfirmDelete>
                        </div>
                    </div>
                    <div class="label bg-transparent mb-3" :class="isEdit(label.id) ? '' : 'd-none'">
                        <div class="d-flex bg-white shadow-sm rounded-3 overflow-hidden align-items-center">
                            <label
                                class="thumb-color d-flex align-items-center justify-content-center align-self-stretch rounded-3"
                                :style="`background-color: ${color};`">
                                <div class="text-uppercase px-md-5"
                                     :class="isColorDark(color) ? 'text-white' : 'text-black'">
                                    <i class="bi bi-paint-bucket"></i>
                                </div>
                                <input v-model="color" type="color" class="visually-hidden">
                            </label>
                            <div class="text-decoration-none text-dark flex-grow-1 me-3">
                                <div class="label-title ps-3 py-2">
                                    <input v-model="name" type="text" class="form-control">
                                </div>
                            </div>
                            <div class="d-none form-check form-switch mb-3 d-md-flex">
                                <input v-model="isActive" class="form-check-input mt-3" type="checkbox" role="switch"
                                       id="switchIsActive">
                            </div>
                            <div class="align-self-center">
                                <button @click.prevent="update(label.id)"
                                        class="btn btn-primary d-flex align-items-center" data-bs-toggle="tooltip"
                                        title="Сохранить" :disabled="!isDataLoaded">
                                    <i class="bi bi-check-square"></i>
                                </button>
                            </div>
                            <ConfirmDelete :item="label" table="labels" :update-content="getLabels"></ConfirmDelete>
                        </div>
                        <p v-show="errors.name" class="alert alert-danger py-2 my-2">{{ errors.name }}</p>
                    </div>
                </template>
            </div>
            <h3 v-else-if="!showAdd">Результаты отсутствуют</h3>
            <transition name="slide-fade">
                <div v-if="showAdd" class="label bg-transparent mb-3">
                    <div class="d-flex bg-white shadow-sm rounded-3 overflow-hidden align-items-center">
                        <label
                            class="thumb-color d-flex align-items-center justify-content-center align-self-stretch rounded-3"
                            :style="`background-color: ${color};`">
                            <div class="text-uppercase px-md-5"
                                 :class="isColorDark(color) ? 'text-white' : 'text-black'">
                                <i class="bi bi-paint-bucket"></i>
                            </div>
                            <input v-model="color" type="color" class="visually-hidden">
                        </label>
                        <div class="text-decoration-none text-dark flex-grow-1 me-3">
                            <div class="label-title ps-3 py-2">
                                <input v-model="name" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="d-none form-check form-switch mb-3 d-md-flex">
                            <input v-model="isActive" class="form-check-input mt-3" type="checkbox" role="switch"
                                   id="switchIsActive">
                        </div>
                        <div class="align-self-center">
                            <button @click.prevent="store" class="btn btn-primary d-flex align-items-center"
                                    title="Добавить" :disabled="!isDataLoaded">
                                <i class="bi bi-check-square"></i>
                            </button>
                        </div>
                        <button class="btn btn-link align-self-center me-2 shadow-none" @click="showAdd = false"
                                type="button">
                            <i class="bi bi-x fs-1 text-danger"></i>
                        </button>
                    </div>
                    <p v-show="errors.name" class="alert alert-danger py-2 my-2">{{ errors.name }}</p>
                </div>
            </transition>
        </div>
    </div>
</template>

<script>
import Preloader from "../../../components/Preloader.vue"
import ConfirmDelete from "../../../components/ConfirmDelete.vue"
import Designer from "../../../store/Designer"

export default {
    name: "Index",
    components: {
        Preloader,
        ConfirmDelete,
    },

    data() {
        return {
            isLoading: true,
            labels: [],
            keyword: '',
            editLabelId: null,
            showAdd: false,
            name: '',
            color: '#000000',
            isActive: true,
            isDataLoaded: true,
            errors: {
                name: '',
            },
        }
    },

    mounted() {
        this.getLabels()
    },

    methods: {
        async getLabels() {
            this.isLoading = true

            try {
                const res = await axios.get('/api/labels');
                this.labels = res.data.data
                this.isLoading = false
            } catch (err) {
                console.error('Не удалось загрузить данные')
            }

            if (this.keyword.length) {
                this.labels = this.labels.filter(label => label.name.toLowerCase().includes(this.keyword.toLowerCase()) || label.color.toLowerCase().includes(this.keyword.toLowerCase()))
            }

            this.isLoading = false
        },

        changeEditLabelId(label) {
            this.errors.name = ''
            this.errors.color = ''
            this.editLabelId = label ? label.id : null
            this.name = label ? label.name : ''
            this.color = label ? label.color : '#000000'
            this.isActive = label ? label.is_active : true
            this.showAdd = !label
        },

        checkForm() {
            this.errors.name = ''
            this.errors.color = ''
            if (this.name.trim() === '') {
                this.errors.name = 'Не указано наименование'
            }

            return !this.errors.name;
        },

        async update(id) {
            if (!this.checkForm()) return
            this.editLabelId = null
            try {
                const data = {
                    name: this.name,
                    color: this.color,
                    is_active: this.isActive,
                }
                this.isDataLoaded = false
                await axios.patch(`/api/labels/${id}`, data)
                await this.getLabels()
                this.name = ''
                this.color = '#000000'
                this.isDataLoaded = true
            } catch (err) {
                console.error('Не удалось загрузить данные')
            }
        },

        async store() {
            if (!this.checkForm()) return
            try {
                const data = {
                    name: this.name,
                    color: this.color,
                    is_active: this.isActive,
                }
                this.isDataLoaded = false
                await axios.post('/api/labels', data)
                await this.getLabels()
                this.name = ''
                this.color = '#000000'
                this.isActive = true
                this.showAdd = false
                this.isDataLoaded = true
            } catch (err) {
                console.error('Не удалось загрузить данные')
            }
        },

        isEdit(id) {
            return this.editLabelId === id
        },

        isColorDark(color) {
            return Designer.isColorDark(color)
        }

    }
}
</script>

<style scoped>

.slide-fade-enter-active,
.slide-fade-leave-active {
    transition: .3s;
}

.slide-fade-enter, .slide-fade-leave-to {
    transform: translateY(100%);
    opacity: 0;
}

</style>
