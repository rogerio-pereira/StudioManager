<template>
    <div class='card'>
        <div class="card-header">
            <strong>Edit Customer</strong>
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
                customer: {},
                errors: {}
            }
        },
        created() {
            this.$http.get('customers/'+this.$route.params.id)
                .then(response => {
                    this.customer = response.data
                })
                .catch(error => {
                    console.log('Error at fetching customer\n'+error)
                })
        },
        methods: {
            save(data) {
                this.$http.put('customers/'+this.customer.id, data)
                    .then(() => {
                        this.$router.push({ name: 'customers.index'})
                    })
                    .catch(error => {
                        console.log('Error at saving\n'+error)
                    })
            }
        }
    }
</script>

<style scoped>

</style>