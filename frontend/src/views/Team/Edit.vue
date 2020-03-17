<template>
    <div class='card'>
        <div class="card-header">
            <strong>Edit Team</strong>
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
                team: {},
                errors: {}
            }
        },
        created() {
            this.$http.get('team/'+this.$route.params.id)
                .then(response => {
                    this.team = response.data
            console.log(this.team)
                })
                .catch(error => {
                    console.log('Error at fetching team\n'+error)
                })
        },
        methods: {
            save(data) {
                this.$http.put('team/'+this.team.id, data)
                    .then(() => {
                        this.$router.push({ name: 'team.index'})
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