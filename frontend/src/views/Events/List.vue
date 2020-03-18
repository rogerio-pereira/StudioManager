<template>
    <div>
        <h1 class='text-center'>Events</h1>

            <div class='text-center my-4'>
                <router-link to='/events/new' class='btn btn-lg btn-success'>
                    <i class="fas fa-plus-circle"></i> &nbsp; New
                </router-link>
            </div>


            <table class="table text-center table-sm table-hover table-responsive-sm table-striped table-bordered">
                <thead>
                    <tr>
                        <th width='120px'></th>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Place</th>
                        <th>Team</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for='(event, index) in events' :key='index'>
                        <td>
                            <router-link :to="'/events/edit/'+event.id" class='btn btn-info text-white mr-2'>
                                <i class="fas fa-edit"></i>
                            </router-link >
                            <a class='btn btn-danger text-white' @click='deleteItem(event.id, index)'>
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
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
    export default {
        data() {
            return {
                events: []
            }
        },
        created() {
            this.$http.get('events').then(res => {this.events = res.data})
        },
        methods: {
            deleteItem(id, index) {
                this.$http.delete('events/'+id)
                    .then(() => {
                        this.events.splice(index, 1);
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