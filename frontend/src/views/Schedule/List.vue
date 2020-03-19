<template>
    <div>
        <h1 class='text-center'>Schedule</h1>

        <div class='row'>
            <div class="form-group col-md-6">
                <label for="start_date">Start Date</label>
                <datetime 
                    id='start_date'
                    type='date' 
                    input-class='form-control' 
                    format='yyyy-LL-dd'
                    v-model="start_date" 
                    @input="getSchedule"
                />
            </div>

            <div class="form-group col-md-6">
                <label for="end_date">End Date</label>
                <datetime 
                    id='end_date'
                    type='date' 
                    input-class='form-control' 
                    format='yyyy-LL-dd'
                    v-model="end_date" 
                    @input="getSchedule"
                />
            </div>
        </div>

        <table class="table text-center table-sm table-hover table-responsive-sm table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Place</th>
                    <th>Team</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for='(event, index) in events' :key='index'>
                    <td>{{event.id}}</td>
                    <td>{{event.customer.name}}</td>
                    <td>{{event.date}}</td>
                    <td>{{event.place}}</td>
                    <td>
                        <span v-for='member in event.team' :key='member.id'>{{member.name}}<br/></span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    import { Datetime } from 'vue-datetime';
    import { DateTime } from "luxon";

    export default {
        components: {
            datetime: Datetime,
        },
        data() {
            return {
                events: [],
                start_date: '',
                end_date: '',
            }
        },
        created() {
            this.$http.get('schedule').then(res => {this.events = res.data})
        },
        methods: {
            getSchedule() {
                let url = ''
                let start = ''
                let end = ''

                if(this.start_date != '') {
                    start = this.getDateTime(this.start_date)
                    url += '/'+start
                }

                if(this.end_date != '') {
                    end = this.getDateTime(this.end_date)
                    url += '/'+end
                }
                
                this.$http.get('schedule'+url).then(res => {this.events = res.data})
            },
            getDateTime(date) {
                if(date === null)
                    return null;

                return DateTime.fromISO(date).toFormat('yyyy-LL-dd')
            },
        }
    }
</script>

<style scoped>

</style>