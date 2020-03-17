<template>
    <div class='card'>
        <div class="card-header">
            <strong>Edit Product</strong>
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
                product: {},
                errors: {}
            }
        },
        created() {
            this.$http.get('products/'+this.$route.params.id)
                .then(response => {
                    this.product = response.data
                })
                .catch(error => {
                    console.log('Error at fetching product\n'+error)
                })
        },
        methods: {
            save(data) {
                this.$http.put('products/'+this.product.id, data)
                    .then(() => {
                        this.$router.push({ name: 'products.index'})
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