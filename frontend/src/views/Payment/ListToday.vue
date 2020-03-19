<template>
    <table class="table text-center table-sm table-hover table-responsive-sm table-striped table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Due At</th>
                <th>Amount</th>
                <th>Payed</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for='(payment, index) in payments' :key='index'>
                <td>{{payment.id}}</td>
                <td>{{payment.sale.customer.name}}</td>
                <td>{{payment.due_at}}</td>
                <td>$ {{payment.amount}}</td>
                <td>
                    <span v-if='payment.payed == true'>
                        <i class="fas fa-check"></i>
                    </span>

                    <button 
                        class='btn btn-success' 
                        @click.prevent.stop="pay(payment.id, index)"
                        v-else
                    >
                        <i class="fas fa-check"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        data() {
            return {
                payments: ''
            }
        },
        created() {
            this.$http.get('/payments/today').then(res => {this.payments = res.data})
        },
        methods: {
            pay(id, index) {
                //POST PARA ALTERAR STATUS
                this.$http.put('/payment/'+id+'/pay', {payed: true})
                    .then(() => {
                        this.payments[index].payed = true
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