<template>
    <div class='row'>
        <input type='hidden' id='id' v-model='event.id'>
        
        <div class="form-group col-md-12">
            <label for="customer">Customer</label>
            <select class='form-control' id='customer' v-model='event.customer_id'>
                <option v-for='customer in customers' :key='customer.id' :value='customer.id'>{{customer.name}}</option>
            </select>
            <div class='text-danger' v-if='errors.name'>
                <small>
                    <p v-for='(error, index) in errors.name' :key='index'>{{error}}</p>
                </small>
            </div>
        </div>
        
        <div class="form-group col-md-6">
            <label for="place">Place</label>
            <input type="text" class="form-control" id="place" v-model='event.place'>
            <div class='text-danger' v-if='errors.place'>
                <small>
                    <p v-for='(error, index) in errors.place' :key='index'>{{error}}</p>
                </small>
            </div>
        </div>
        
        <div class="form-group col-md-6">
            <label for="date">Date</label>
            <datetime 
                type='datetime' 
                input-class='form-control' 
                value-zone='EST' 
                minute-step='5' 
                format='yyyy-LL-dd HH:mm:ss'
                v-model="event.date" 
            />
            <div class='text-danger' v-if='errors.date'>
                <small>
                    <p v-for='(error, index) in errors.date' :key='index'>{{error}}</p>
                </small>
            </div>
        </div>

        <div class='for-group col-md-12 text-center'>
            <h2>Team</h2>

            <div class='text-danger' v-if='errors.team'>
                <small>
                    <p v-for='(error, index) in errors.team' :key='index'>{{error}}</p>
                </small>
            </div>
        </div>

        <div class='col-md-4 form-inline' v-for='member in team' :key='member.id'>
            <input type="checkbox" class="form-control" :id="'member_'+member.id" :value='member.id' v-model='event.team' >
            <label :for="'member_'+member.id" class='ml-3'>{{member.name}}</label>
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
            event: {type: Object, required: true},
            errors: {type: Object, required:false}
        },
        data() {
            return {
                customers: null,
                team: null
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
            this.$http.get('team')
                .then(response => {
                    this.team = response.data
                })
                .catch(error => {
                    console.log('Error at fetching team\n'+error)
                })
        },
        methods: {
            save(){
                this.event.date = this.getDateTime(this.event.date);
                this.$emit('save', {data: this.event})
            },
            getDateTime(date) {
                if(date === null)
                    return null;

                return DateTime.fromISO(date).toFormat('yyyy-LL-dd HH:mm:ss')
            }
        }
    }
</script>

<style scoped>

</style>