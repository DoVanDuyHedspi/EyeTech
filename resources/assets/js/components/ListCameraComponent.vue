<template>
    <div class="tab-content clearfix" id="tab-cameras">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="camera in cameras">
                <td>
                    {{ camera.id }}
                </td>
                <td>
                    {{ camera.name }}
                </td>
                <td>
                    <a href="#"> Edit </a>
                    <a href="#" style="color: #FF0000; margin-left: 10px;"> Delete </a>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        name: "ListCameraComponent",
        data() {
            return {
                cameras : [],
            }
        },
        created() {
            this.fetchListCameras();
        },
        methods: {
            fetchListCameras() {
                axios({
                    method: 'get',
                    url: 'http://localhost/eyetech/api/v1/cameras'
                })
                    .then(response => {
                        console.log(response);
                        this.cameras = response.data.cameras;
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
