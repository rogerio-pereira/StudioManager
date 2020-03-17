<template>
    <div>
        <h1 class='text-center'>Team</h1>

            <div class='text-center my-4'>
                <router-link to='/team/new' class='btn btn-lg btn-success'>
                    <i class="fas fa-plus-circle"></i> &nbsp; New
                </router-link>
            </div>


            <table class="table text-center table-sm table-hover table-responsive-sm table-striped table-bordered">
                <thead>
                    <tr>
                        <th width='120px'></th>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Own Equipment</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for='(team, index) in teams' :key='index'>
                        <td>
                            <router-link :to="'/team/edit/'+team.id" class='btn btn-info text-white mr-2'>
                                <i class="fas fa-edit"></i>
                            </router-link >
                            <a class='btn btn-danger text-white' @click='deleteItem(team.id, index)'>
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                        <td>{{team.id}}</td>
                        <td>{{team.name}}</td>
                        <td><a :href="'mailto:'+team.email">{{team.email}}</a></td>
                        <td><a :href="'tel:'+team.phone">{{team.phone}}</a></td>
                        <td>
                            <span v-if='team.own_equipment == true'><i class="fas fa-check"></i></span>
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
                teams: []
            }
        },
        created() {
            this.$http.get('team').then(res => {this.teams = res.data;})
        },
        methods: {
            deleteItem(id, index) {
                this.$http.delete('team/'+id)
                    .then(() => {
                        this.teams.splice(index, 1);
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