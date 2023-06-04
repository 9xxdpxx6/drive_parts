<template>
    <div class="position-absolute w-100 h-100 bg-info">
        <div
            class="login-container position-absolute start-50 translate-middle-x col-11 col-md-8 col-lg-6 col-xl-3 rounded-4 p-3 p-md-5 bg-light">
            <div class="login-title">
                <div class="login-brand d-flex text-center flex-column text-secondary">
                    <img src="/assets/logo.svg" height="100" alt="Logo">
                    <span class="fs-4 text-white mt-3">
                        <span class="text-primary">DRIVE</span>
                        <span class="text-dark">PARTS</span>
                    </span>
                </div>
                <div class="login-info mt-4 mb-5 text-center">
                    <h1 class="text-dark">Вход</h1>
                    <p class="text-secondary">В панель управления DRIVE PARTS</p>
                </div>
                <p v-show="errors.message" class="alert alert-danger py-2">{{ errors.message }}</p>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label text-secondary text-uppercase ps-1">Email</label>
                <input v-model="email" type="email" placeholder="Email" class="form-control" id="email">
                <p v-show="errors.email" class="alert alert-danger py-2 my-2">{{ errors.email }}</p>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label text-secondary text-uppercase ps-1">Пароль</label>
                <div class="password input-group">
                    <input v-model="password" :type="showPass ? 'text' : 'password'" placeholder="Пароль"
                           class="form-control" id="password">
                    <button @click="showPass = !showPass" type="button" class="btn btn-outline-secondary pt-2">
                        <i v-if="showPass" class="bi bi-eye"></i>
                        <i v-else class="bi bi-eye-slash"></i>
                    </button>
                </div>
                <p v-show="errors.password" class="alert alert-danger py-2 my-2">{{ errors.password }}</p>
            </div>
            <div class="d-flex">
                <input @click.prevent="checkForm" type="submit" value="Войти"
                       class="btn btn-primary text-center w-75 mx-auto mt-3">
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Login",

    data() {
        return {
            email: '',
            password: '',
            showPass: false,
            errors: {
                email: '',
                password: '',
                message: '',
            }
        }
    },

    methods: {
        checkForm() {
            this.errors.email = ''
            this.errors.password =''
            if (this.email.trim() === '') {
                this.errors.email = 'Введите email'
            }
            if (this.password.trim() === '') {
                this.errors.password = 'Введите пароль'
            }

            if (this.errors.email || this.errors.password) {
                return
            }
            this.login()
        },

        async login() {
            try {
                await axios.get('/sanctum/csrf-cookie')
                const response = await axios.post('/login', {
                    email: this.email,
                    password: this.password,
                })
                localStorage.setItem('x_xsrf_token', response.config.headers['X-XSRF-TOKEN'])
                const user = await axios.get(`/api/user`)
                localStorage.setItem('user', JSON.stringify(user.data))
                await this.$router.push({ name: 'product.index' })
            } catch (error) {
                this.errors.message = 'Неверный логин или пароль'
            }
        }

    }
}
</script>

<style scoped>
.login-container {
    margin-top: 6%;
}
</style>
