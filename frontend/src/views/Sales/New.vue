<template>
    <div class='card'>
        <div class="card-header">
            <strong>New Sale</strong>
        </div>

        <div class='col-8 offset-2'>
            <form-sale :sale='sale' :errors='errors' @save='save($event.data)'/>
        </div>
    </div>
</template>

<script>
    import FormSale from './Form'

    export default {
        components: {
            FormSale
        },
        data() {
            return {
                sale: {
                    id: null,
                    customer_id: '',
                    value: 1,
                    discount: 0.00,
                    installments: 1,
                    start_date: new Date().toISOString().slice(0,10),
                    period: 'Monthly',
                    products: [],
                },
                errors: {}
            }
        },
        methods: {
            save(data) {
                this.$http.post('sales', data)
                    .then(() => {
                        this.$router.push({ name: 'sales.index'})
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