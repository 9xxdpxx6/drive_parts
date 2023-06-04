<template>
    <div>
        <input v-model="name" type="name" placeholder="name" class="form-control">
        <input v-model="email" type="email" placeholder="email" class="form-control">
        <input v-model="phone_number" type="text" placeholder="phone number" class="form-control">
        <input v-model="password" type="password" placeholder="password" class="form-control">
        <input v-model="password_confirmation" type="password" placeholder="password_confirmation" class="form-control">
        <input @click.prevent="register" type="submit" value="register" class="btn btn-primary">
    </div>
</template>

<script>
export default {
    name: "Registration",

    data() {
        return {
            name: null,
            email: null,
            phone_number: null,
            password: null,
            password_confirmation: null,
        }
    },

    methods: {
        register() {
            axios.get('/sanctum/csrf-cookie')
                .then(response => {
                    axios.post('/register', {
                        name: this.name,
                        email: this.email,
                        phone_number: this.phone_number,
                        password: this.password,
                        password_confirmation: this.password_confirmation,
                        group_id: null,
                        role_id: 1
                    })
                        .then(res => {
                            localStorage.setItem('x_xsrf_token', res.config.headers['X-XSRF-TOKEN'])
                            this.$router.push({name: 'user.personal'})
                            console.log(res);
                        })
                        .catch(err => {
                            console.log(err.response)
                        })
                })
        },
    }
}
</script>

<style scoped>

</style>
