<template>
    <div class='card'>
        <div class="card-header">
            <strong>New Event</strong>
        </div>

        <div class='col-8 offset-2'>
            <form-event :event='event' :errors='errors' @save='save($event.data)'/>
        </div>
    </div>
</template>

<script>
    import FormEvent from './Form'

    export default {
        components: {
            FormEvent
        },
        data() {
            return {
                event: {
                    id: null,
                    customer_id: '',
                    date: '',
                    place: '',
                    team: [],
                },
                errors: {}
            }
        },
        methods: {
            save(data) {
                this.$http.post('events', data)
                    .then(() => {
                        this.$router.push({ name: 'events.index'})
                    })
                    .catch(error => {
                        console.log(error.response)
                        this.errors = error.response.data.errors
                    })
            }
        }
    }
</script>

<style scoped>

</style>