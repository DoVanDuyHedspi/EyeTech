<template>
    <form style="max-width: 25rem;" method="post" @submit.prevent="create">
        <div class="form-group">
            <label for="inputName">Name</label>
            <input v-model="name" type="text" class="form-control" id="inputName" aria-describedby="nameHelp" placeholder="Enter name">
            <small id="namelHelp" class="form-text text-muted">Input your favorites camera name</small>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Create</button>
    </form>
</template>

<script>
    export default {
        name: "CreateCameraComponent",
        data() {
            return {
                name: '',
            }
        },
        methods: {
            create() {
                axios({
                    method: 'post',
                    url: 'http://localhost/eyetech/api/v1/cameras',
                    data: {
                        name: this.name,
                        branch_id: 1,
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
