<template>
    <div class='card'>
        <div class="card-header">
            <strong>Edit Suplier</strong>
        </div>

        <div class='col-8 offset-2'>
            <form-suplier :suplier='suplier' :errors='errors' @save='save($event.data)'/>
        </div>
    </div>
</template>

<script>
    import FormSuplier from './Form'

    export default {
        components: {
            FormSuplier
        },
        data() {
            return {
                suplier: {},
                errors: {}
            }
        },
        created() {
            this.$http.get('supliers/'+this.$route.params.id)
                .then(response => {
                    this.suplier = response.data
                })
                .catch(error => {
                    console.log('Error at fetching suplier\n'+error)
                })
        },
        methods: {
            save(data) {
                this.$http.put('supliers/'+this.suplier.id, data)
                    .then(() => {
                        this.$router.push({ name: 'supliers.index'})
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