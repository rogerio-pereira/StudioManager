<template>
    <div class='card'>
        <div class="card-header">
            <strong>New Team</strong>
        </div>

        <div class='col-8 offset-2'>
            <form-team :team='team' :errors='errors' @save='save($event.data)'/>
        </div>
    </div>
</template>

<script>
    import FormTeam from './Form'

    export default {
        components: {
            FormTeam
        },
        data() {
            return {
                team: {
                    id: null,
                    name: '',
                    email: '',
                    phone: '',
                    own_equipment: 1,
                },
                errors: {}
            }
        },
        methods: {
            save(data) {
                this.$http.post('team', data)
                    .then(() => {
                        this.$router.push({ name: 'team.index'})
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