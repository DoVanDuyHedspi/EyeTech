<template>
    <div class="card nobottommargin">
        <div class="card-body" style="padding: 40px;">
            <form id="login-form" name="login-form" class="nobottommargin" @submit.prevent="login" method="post">
                <h3>Login to your Account</h3>
                <div class="col_full">
                    <label for="register-form-email">Email Address:</label>
                    <input v-model="email" type="text" id="register-form-email" name="register-form-email" value="" class="form-control" />
                </div>
                <div class="col_full">
                    <label for="register-form-password">Password:</label>
                    <input v-model="password" type="password" id="register-form-password" name="register-form-password" value="" class="form-control" />
                </div>
                <div class="col_full nobottommargin">
                    <button class="button button-3d button-black nomargin" id="login-form-submit" name="login-form-submit" value="login">Login</button>
                    <a href="#" class="fright">Forgot Password?</a>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        name: "LoginComponent",
        data() {
            return {
                'email' : '',
                'password' : ''
            }
        },
        methods: {
            login() {
                // var url = 'http://localhost/eyetech/api/v1/users/login';
                var url = 'http://202.191.56.249/eyetech/api/v1/users/login';
                axios({
                    method: 'post',
                    url: url,
                    data: {
                        email: this.email,
                        password: this.password,
                    }
                })
                    .then(response => {
                       console.log(response);
                       window.location = response.data.redirect;
                    })
                    .catch(error => {
                        console.log(error);
                    });
            }
        }
    }
</script>

<style scoped>

</style>
