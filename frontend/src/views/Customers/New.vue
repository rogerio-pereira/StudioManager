<template>
    <div class='card'>
        <div class="card-header">
            <strong>New Customer</strong>
        </div>

        <div class='col-8 offset-2'>
            <form-customer :customer='customer' :errors='errors' @save='save($event.data)'/>
        </div>
    </div>
</template>

<script>
    import FormCustomer from './Form'

    export default {
        components: {
            FormCustomer
        },
        data() {
            return {
                customer: {
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
                this.$http.post('customers', data)
                    .then(() => {
                        this.$router.push({ name: 'customers.index'})
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