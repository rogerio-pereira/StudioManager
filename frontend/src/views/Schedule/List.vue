<template>
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
</template>

<script>
    export default {
        props: {
            events: {type: Array, required: true}
        }
    }
</script>

<style scoped>

</style>