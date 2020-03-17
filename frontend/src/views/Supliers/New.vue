<template>
    <div class='card'>
        <div class="card-header">
            <strong>New Suplier</strong>
        </div>

        <div class='col-8 offset-2'>
            <form-suplier :suplier='suplier' :errors='errors' @save='save($event.data)'/>
        </div>
    </div>
</template>

<script>
    import FormSuplier from './Form'

    export default {
        components: {
            FormSuplier
        },
        data() {
            return {
                suplier: {
                    id: null,
                    name: '',
                    email: '',
                    phone: '',
                    address: '',
                    address2: '',
                    city: '',
                    state: 'CT',
                    zipcode: '',
                },
                errors: {}
            }
        },
        methods: {
            save(data) {
                this.$http.post('supliers', data)
                    .then(() => {
                        this.$router.push({ name: 'supliers.index'})
                    })
                    .catch(error => {
                        console.log(error.response)
                        this.errors = error.response.data.errors
                    })
            }
        }
    }
</script>

<style scoped>

</style>