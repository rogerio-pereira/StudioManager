<template>
    <div>
        <h1 class='text-center'>Products</h1>

            <div class='text-center my-4'>
                <router-link to='/products/new' class='btn btn-lg btn-success'>
                    <i class="fas fa-plus-circle"></i> &nbsp; New
                </router-link>
            </div>


            <table class="table text-center table-sm table-hover table-responsive-sm table-striped table-bordered">
                <thead>
                    <tr>
                        <th width='120px'></th>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Value</th>
                        <th>Cost</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for='(product, index) in products' :key='index'>
                        <td>
                            <router-link :to="'/products/edit/'+product.id" class='btn btn-info text-white mr-2'>
                                <i class="fas fa-edit"></i>
                            </router-link >
                            <a class='btn btn-danger text-white' @click='deleteItem(product.id, index)'>
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                        <td>{{product.id}}</td>
                        <td>{{product.name}}</td>
                        <td>$ {{product.value}}</td>
                        <td>
                            <span v-if='product.cost'>
                                $ {{product.cost}}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                products: []
            }
        },
        created() {
            this.$http.get('products').then(res => {this.products = res.data})
        },
        methods: {
            deleteItem(id, index) {
                this.$http.delete('products/'+id)
                    .then(() => {
                        this.products.splice(index, 1);
                    })
                    .catch((error) => {
                        console.log(error)
                    });
            }
        }
    }
</script>

<style scoped>

</style>