<template>
    <div>
        <h1 class='text-center'>Customers</h1>

            <div class='text-center my-4'>
                <router-link to='/customers/new' class='btn btn-lg btn-success'>
                    <i class="fas fa-plus-circle"></i> &nbsp; New
                </router-link>
            </div>


            <table class="table text-center table-sm table-hover table-responsive-sm table-striped table-bordered">
                <thead>
                    <tr>
                        <th width='120px'></th>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for='(customer, index) in customers' :key='index'>
                        <td>
                            <router-link :to="'/customers/edit/'+customer.id" class='btn btn-info text-white mr-2'>
                                <i class="fas fa-edit"></i>
                            </router-link >
                            <a class='btn btn-danger text-white' @click='deleteItem(customer.id, index)'>
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                        <td>{{customer.id}}</td>
                        <td>{{customer.name}}</td>
                        <td><a :href="'mailto:'+customer.email">{{customer.email}}</a></td>
                        <td><a :href="'tel:'+customer.phone">{{customer.phone}}</a></td>
                        <td>
                            {{customer.address}}
                            <span v-if='customer.address2'> - {{customer.address2}}</span>
                            . {{customer.city}} - {{customer.state}}. {{customer.zipcode}}
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
                customers: []
            }
        },
        created() {
            this.$http.get('customers').then(res => {this.customers = res.data})
        },
        methods: {
            deleteItem(id, index) {
                this.$http.delete('customers/'+id)
                    .then(() => {
                        this.customers.splice(index, 1);
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