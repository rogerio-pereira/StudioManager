<template>
    <div class='card'>
        <div class="card-header">
            <strong>Edit Event</strong>
        </div>

        <div class='col-8 offset-2'>
            <form-event :event='event' :errors='errors' @save='save($event.data)'/>
        </div>
    </div>
</template>

<script>
    import FormEvent from './Form'
    import { DateTime } from "luxon";

    export default {
        components: {
            FormEvent
        },
        data() {
            return {
                event: {},
                errors: {}
            }
        },
        created() {
            this.$http.get('events/'+this.$route.params.id)
                .then(response => {
                    this.event = response.data
                    let arr = []
                    for(let i=0; i<this.event.team.length; i++) {
                        console.log(this.event.team[i].id)
                        arr.push(this.event.team[i].id)
                    }
                    this.event.team = arr
                    this.event.date = DateTime.fromISO(this.event.date.replace(' ', 'T'));
                })
                .catch(error => {
                    console.log('Error at fetching event\n'+error)
                })
        },
        methods: {
            save(data) {
                this.$http.put('events/'+this.event.id, data)
                    .then(() => {
                        this.$router.push({ name: 'events.index'})
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