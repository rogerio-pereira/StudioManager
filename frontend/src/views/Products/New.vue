<template>
    <div class='card'>
        <div class="card-header">
            <strong>New Product</strong>
        </div>

        <div class='col-8 offset-2'>
            <form-product :product='product' :errors='errors' @save='save($event.data)'/>
        </div>
    </div>
</template>

<script>
    import FormProduct from './Form'

    export default {
        components: {
            FormProduct
        },
        data() {
            return {
                product: {
                    id: null,
                    name: '',
                    value: '',
                    cost: '',
                },
                errors: {}
            }
        },
        methods: {
            save(data) {
                this.$http.post('products', data)
                    .then(() => {
                        this.$router.push({ name: 'products.index'})
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