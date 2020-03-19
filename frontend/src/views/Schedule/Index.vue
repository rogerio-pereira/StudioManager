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

        <schedule-list :events='events' />
    </div>
</template>

<script>
    import { Datetime } from 'vue-datetime';
    import { DateTime } from "luxon";
    import ScheduleList from './List'

    export default {
        components: {
            datetime: Datetime,
            ScheduleList,
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