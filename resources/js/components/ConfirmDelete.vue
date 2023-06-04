<template>
    <div>
        <button class="btn btn-link align-self-center me-md-2 shadow-none" @click="showModal" type="button">
            <i class="bi bi-x fs-1 text-danger"></i>
        </button>
        <div class="modal fade" :id="`confirmDelete${item.id}`" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" :ref="`modal${item.id}`">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Подтвердите действие</h5>
                        <button type="button" class="btn-close" @click="hideModal" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-start" v-html="message" :class="color"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="hideModal" data-bs-dismiss="modal">Закрыть</button>
                        <button @click.prevent="destroy(item.id)" type="button" class="btn btn-danger text-light">Удалить</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Modal from 'bootstrap/js/dist/modal.js'
export default {
    name: "ConfirmDelete",

    props: {
        item: {
            type: Object,
            required: true,
        },
        table: {
            type: String,
            required: true,
        },
        updateContent: {
            type: Function,
            required: true,
        },
        related: {
            type: Number,
            required: false,
        }
    },

    data() {
        return {
            message: ``,
            color: '',
            exception: false,
        }
    },

    mounted() {
        this.modal = new Modal(this.$refs[`modal${this.$props.item.id}`])
        this.resetMessage()
    },

    methods: {
        showModal() {
            this.modal.show()
        },

        hideModal() {
            this.modal.hide()
            setTimeout(this.resetMessage, 200);
        },

        resetMessage() {
            this.message = `<span>Удалить безвозвратно </span><strong>${this.$props.item.name}</strong><span> ?</span>`
            this.color = 'text-dark'
        },

        async destroy(id) {
            try {
                if (this.related) {
                    this.message = 'Элемент не может быть удалён, так как он является корневым'
                    this.color = 'text-danger'
                    return
                }

                this.hideModal()
                await axios.delete(`/api/${this.$props.table}/${id}`)
                this.$props.updateContent()
            } catch (err) {
                console.error('Не удалось загрузить данные')
            }
        }
    }
}
</script>

<style scoped>

.modal {
    z-index: 3000;
}

</style>
