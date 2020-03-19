<template>
    <div class='card'>
        <div class="card-header">
            <strong>Edit Sale</strong>
        </div>

        <div class='col-8 offset-2'>
            <form-sale :sale='sale' :errors='errors' @save='save($event.data)'/>
        </div>
    </div>
</template>

<script>
    import FormSale from './Form'
    import { DateTime } from "luxon";

    export default {
        components: {
            FormSale
        },
        data() {
            return {
                sale: {},
                errors: {}
            }
        },
        created() {
            this.$http.get('sales/'+this.$route.params.id)
                .then(response => {
                    this.sale = response.data
                    let arr = []
                    for(let i=0; i<this.sale.products.length; i++) {
                        arr.push(this.sale.products[i].id)
                    }
                    this.sale.products = arr
                    this.sale.date = DateTime.fromISO(this.sale.date.replace(' ', 'T'));
                })
                .catch(error => {
                    console.log('Error at fetching sale\n'+error)
                })
        },
        methods: {
            save(data) {
                this.$http.put('sales/'+this.sale.id, data)
                    .then(() => {
                        this.$router.push({ name: 'sales.index'})
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