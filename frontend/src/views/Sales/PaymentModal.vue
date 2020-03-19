<template>
    <div class="modal fade" id="modalPayment" tabindex="-1" role="dialog" aria-labelledby="modalPaymentLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <strong>Payments for Sale {{id}}</strong>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <table class="table text-center table-sm table-hover table-responsive-sm table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Due At</th>
                                <th>Amount</th>
                                <th>Payed</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for='(payment, index) in payments' :key='payment.id'>
                                <td>{{payment.id}}</td>
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
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            id: {type: Number, required: true},
            payments: {type: Array, required: true}
        },
        data() {
            return {
                
            }
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