<template>
    <div class='row'>
        <input type='hidden' id='id' v-model='sale.id'>
        
        <div class="form-group col-md-12">
            <label for="customer">Customer</label>
            <select class='form-control' id='customer' v-model='sale.customer_id'>
                <option v-for='customer in customers' :key='customer.id' :value='customer.id'>{{customer.name}}</option>
            </select>
            <div class='text-danger' v-if='errors.name'>
                <small>
                    <p v-for='(error, index) in errors.name' :key='index'>{{error}}</p>
                </small>
            </div>
        </div>

        <div class='for-group col-md-12 text-center'>
            <h2>Products</h2>

            <div class='text-danger' v-if='errors.products'>
                <small>
                    <p v-for='(error, index) in errors.products' :key='index'>{{error}}</p>
                </small>
            </div>
        </div>

        <div class='col-md-6 form-inline' v-for='product in products' :key='product.id'>
            <input 
                type="checkbox" 
                class="form-control" 
                :id="'product_'+product.id" 
                :value='product.id' 
                v-model='sale.products' 
                @change="sumValues"
            >
            <label :for="'product_'+product.id" class='ml-3'>{{product.name}} - {{product.value}} </label>
        </div>

        <div class='col-md-12 text-center'>
            <h2>Payment</h2>
        </div>

        <div class="form-group col-md-4">
            <label for="installments">Installments</label>
            <input type="number" class="form-control" id="installments" v-model='sale.installments' min='1'>
            <div class='text-danger' v-if='errors.installments'>
                <small>
                    <p v-for='(error, index) in errors.installments' :key='index'>{{error}}</p>
                </small>
            </div>
        </div>
        
        <div class="form-group col-md-4">
            <label for="start_date">Start Date</label>
            <datetime 
                type='date' 
                input-class='form-control' 
                format='yyyy-LL-dd'
                v-model="sale.start_date" 
            />
            <div class='text-danger' v-if='errors.start_date'>
                <small>
                    <p v-for='(error, index) in errors.start_date' :key='index'>{{error}}</p>
                </small>
            </div>
        </div>
        
        <div class="form-group col-md-4">
            <label for="period">Period</label>
            <select class='form-control' id='period' v-model='sale.period'>
                <option>Weekly</option>
                <option>Biweekly</option>
                <option>Monthly</option>
            </select>
            <div class='text-danger' v-if='errors.period'>
                <small>
                    <p v-for='(error, index) in errors.period' :key='index'>{{error}}</p>
                </small>
            </div>
        </div>

        <div class='col-md-12 text-center'>
            <h2>Values</h2>
        </div>

        <div class="form-group col-md-6">
            <label for="value">Value</label>
            <input type="text" class="form-control" id="value" v-model='sale.value' disabled='true'>
            <div class='text-danger' v-if='errors.value'>
                <small>
                    <p v-for='(error, index) in errors.value' :key='index'>{{error}}</p>
                </small>
            </div>
        </div>
        
        <div class="form-group col-md-6">
            <label for="discount">Discount</label>
            <input 
                type="number" 
                class="form-control" 
                id="discount" 
                v-model='sale.discount' 
                min='0' 
                step='0.01' 
                :disabled="disabledDiscount"
            >
            <div class='text-danger' v-if='errors.discount'>
                <small>
                    <p v-for='(error, index) in errors.discount' :key='index'>{{error}}</p>
                </small>
            </div>
        </div>

        <div class='form-group text-center col-md-12 mt-4'>
            <button type="submit" class="btn btn-success" @click.stop.prevent='save'>
                <i class="far fa-save mr-2"></i> Save
            </button>
        </div>
    </div>
</template>

<script>
    import { Datetime } from 'vue-datetime';
    import { DateTime } from "luxon";

    export default {
        components: {
            datetime: Datetime,
        },
        props: {
            sale: {type: Object, required: true},
            errors: {type: Object, required:false}
        },
        data() {
            return {
                customers: null,
                products: null,
                disabledDiscount: 0,
            }
        },
        created() {
            this.$http.get('customers')
                .then(response => {
                    this.customers = response.data
                })
                .catch(error => {
                    console.log('Error at fetching customers\n'+error)
                })
            this.$http.get('products')
                .then(response => {
                    this.products = response.data
                })
                .catch(error => {
                    console.log('Error at fetching products\n'+error)
                })
        },
        methods: {
            save(){
                this.sale.start_date = this.getDateTime(this.sale.start_date)
                this.$emit('save', {data: this.sale})
            },
            getDateTime(date) {
                if(date === null)
                    return null;

                return DateTime.fromISO(date).toFormat('yyyy-LL-dd')
            },
            sumValues() {
                //Filter the array of sale products inside all products array
                //After that get just the value and parse it to float
                //And Finally sum all values
                this.sale.value = this.products.filter((product) => {
                                        return this.sale.products.includes(product.id); 
                                    })
                                    .map(product => {
                                        return parseFloat(product.value)
                                    })
                                    .reduce((sum, value) => {
                                        return sum += value
                                    }, 0);

                if(this.sale.value > 0)
                    this.disabledDiscount = false
                else
                    this.disabledDiscount = true
            }
        }
    }
</script>

<style scoped>

</style>