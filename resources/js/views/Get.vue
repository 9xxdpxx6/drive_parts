<template>
    <div>
        get111111111111111111111111111111111111111111111111111111111
    </div>
</template>

<script>
export default {
    name: "Get",

    mounted() {
        this.getData()
    },

    methods: {
        getData() {
            axios.get('/api/get')
            .then( res => {
                console.log(res)
            })
        }
    }
}
</script>

<style scoped>

</style>
