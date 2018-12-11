<template>
    <div class="card nobottommargin">
        <div class="card-body" style="padding: 40px;">
            <h3>Register for an Account</h3>
            <form id="register-form" name="register-form" class="nobottommargin" @submit.prevent="register" method="post">
                <div class="col_full">
                    <label for="register-form-name">Name:</label>
                    <input v-model="name" type="text" id="register-form-name" name="register-form-name" value="" class="form-control" />
                </div>
                <div class="col_full">
                    <label for="register-form-email">Email Address:</label>
                    <input v-model="email" type="text" id="register-form-email" name="register-form-email" value="" class="form-control" />
                </div>
                <div class="col_full">
                    <label for="register-form-username">Choose a Type:</label>
                    <v-select id="register-form-username" :options="types" v-model="typeSelected" label="type">
                    </v-select>
                </div>
                <div class="col_full">
                    <label for="register-form-phone">Phone:</label>
                    <input v-model="phone" type="text" id="register-form-phone" name="register-form-phone" value="" class="form-control" />
                </div>
                <div class="col_full">
                    <label for="register-form-password">Password:</label>
                    <input v-model="password" type="password" id="register-form-password" name="register-form-password" value="" class="form-control" />
                </div>
                <div class="col_full nobottommargin">
                    <button class="button button-3d button-black nomargin" id="register-form-submit" name="register-form-submit" value="register">Register Now</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        name: "RegisterComponent",
        data() {
            return {
                'types' : [],
                'typeSelected' : '',
                'name' : '',
                'email' : '',
                'phone' : '',
                'password' : '',
                'passwordConfirm' : '',
            }
        },
        created() {
            this.fetchUserTypes();
        },
        methods: {
            fetchUserTypes() {
                // var url = 'http://localhost/eyetech/api/v1/user-types';
                var url = 'http://202.191.56.249/eyetech/api/v1/user-types';
                axios({
                    method: 'get',
                    url: url,
                })
                    .then(response => {
                        this.types = response.data.types;
                        console.log(response);
                    })
                    .catch(error => {
                        console.log(error);
                    })
            },
            register() {
                // var url = 'http://localhost/eyetech/api/v1/users/register';
                var url = 'http://202.191.56.249/eyetech/api/v1/users/register';
                axios({
                    method: 'post',
                    url: url,
                    data: {
                        name: this.name,
                        email: this.email,
                        telephone: this.phone,
                        type: this.typeSelected.type,
                        password: this.password,
                    }
                })
                    .then(response => {
                        console.log(response);
                        window.location = response.data.redirect;
                    })
                    .catch(error => {
                        console.log(error);
                    })
            }
        }
    }
</script>

<style scoped>

</style>
