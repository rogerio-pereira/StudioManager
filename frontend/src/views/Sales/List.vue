<template>
    <div>
        <h1 class='text-center'>Sales</h1>

        <div class='text-center my-4'>
            <router-link to='/sales/new' class='btn btn-lg btn-success'>
                <i class="fas fa-plus-circle"></i> &nbsp; New
            </router-link>
        </div>

        <table class="table text-center table-sm table-hover table-responsive-sm table-striped table-bordered">
            <thead>
                <tr>
                    <th width='180px'></th>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Value</th>
                    <th>Discount</th>
                    <th>Installment</th>
                    <th>Start Date</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for='(sale, index) in sales' :key='index'>
                    <td>
                        <router-link :to="'/sales/edit/'+sale.id" class='btn btn-info text-white mr-2'>
                            <i class="fas fa-edit"></i>
                        </router-link >

                        <a class='btn btn-danger text-white' @click='deleteItem(sale.id, index)'>
                            <i class="fas fa-trash-alt"></i>
                        </a>

                        <a class='btn btn-success text-white ml-2' @click='showPayments(sale.id, sale.payments)'>
                            <i class="fas fa-money-bill-wave"></i>
                        </a>
                    </td>
                    <td>{{sale.id}}</td>
                    <td>{{sale.customer.name}}</td>
                    <td>$ {{sale.value}}</td>
                    <td>$ {{sale.discount}}</td>
                    <td>{{sale.installments}}</td>
                    <td>{{sale.start_date}}</td>
                </tr>
            </tbody>
        </table>

        <payment-modal 
            :id='saleId'
            :payments='payments'
        />
    </div>
</template>

<script>
    import PaymentModal from './PaymentModal'
    import * as $ from 'jquery';$ 

    export default {
        components: {
            PaymentModal
        },
        data() {
            return {
                sales: [],

                saleId: 0,
                payments: [],
            }
        },
        created() {
            this.$http.get('sales').then(res => {this.sales = res.data})
        },
        methods: {
            deleteItem(id, index) {
                this.$http.delete('sales/'+id)
                    .then(() => {
                        this.sales.splice(index, 1);
                    })
                    .catch((error) => {
                        console.log(error)
                    });
            },
            showPayments(id, payments)
            {
                this.saleId = id
                this.payments = payments

                $('#modalPayment').modal('show')
            }
        }
    }
</script>

<style scoped>

</style>