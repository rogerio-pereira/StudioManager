<template>
<div>
    <h1 class='mt-3 mb-5'>Welcome</h1>

    <div class='row'>
        <div class='col-md-6'>
            <div class='card'>
                <div class="card-header text-center">
                    <strong>Schedule For Today</strong>
                </div>
                
                <div class='card-body'>
                    <schedule-list :events='events' />
                </div>
            </div>
        </div>

        <div class='col-md-6'>
            <div class='card'>
                <div class="card-header text-center">
                    <strong>Payments For Today</strong>
                </div>
                
                <div class='card-body'>
                    <payment-today-list />
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
    import ScheduleList from './Schedule/List'
    import PaymentTodayList from '../views/Payment/ListToday'

    export default {
        components: {
            ScheduleList,
            PaymentTodayList
        },
        data() {
            return {
                events: [],
            }
        },
        created() {
            this.$http.get('schedule').then(res => {this.events = res.data})
        }
    }
</script>

<style scoped>
    .card-header {
        background: #141E30;  /* fallback for old browsers */
        background: -webkit-linear-gradient(to left, #243B55, #141E30);  /* Chrome 10-25, Safari 5.1-6 */
        background: linear-gradient(to left, #243B55, #141E30); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
        
        color:white;
    }
</style>