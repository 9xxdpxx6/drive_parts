<template>
    <div>
        <div class="not-found mt-5">
            <h1 class="not-found__title">404 Не найдено</h1>
            <p class="not-found__description">Страница, которую вы ищете, не существует</p>
            <router-link :to="{name: 'product.index'}" class="not-found__link">Вернуться на шлавную</router-link>
        </div>
    </div>
</template>

<script>
export default {
    name: "Get",

    data() {
        return {
        }
    },

    mounted() {

    },

    methods: {

    }
}
</script>

<style scoped>

.not-found {
    text-align: center;
    padding: 20px;
}

.not-found__title {
    font-size: 36px;
    margin-bottom: 10px;
    color: #ff5555;
}

.not-found__description {
    font-size: 18px;
    color: #888888;
    margin-bottom: 20px;
}

.not-found__link {
    color: #333333;
    text-decoration: underline;
}

</style>
