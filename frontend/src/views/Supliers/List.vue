<template>
    <div>
        <h1 class='text-center'>Supliers</h1>

            <div class='text-center my-4'>
                <router-link to='/supliers/new' class='btn btn-lg btn-success'>
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
                        <th>Phone</th>
                        <th>Address</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for='(suplier, index) in supliers' :key='index'>
                        <td>
                            <router-link :to="'/supliers/edit/'+suplier.id" class='btn btn-info text-white mr-2'>
                                <i class="fas fa-edit"></i>
                            </router-link >
                            <a class='btn btn-danger text-white' @click='deleteItem(suplier.id, index)'>
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                        <td>{{suplier.id}}</td>
                        <td>{{suplier.name}}</td>
                        <td><a :href="'mailto:'+suplier.email">{{suplier.email}}</a></td>
                        <td><a :href="'tel:'+suplier.phone">{{suplier.phone}}</a></td>
                        <td>
                            {{suplier.address}}
                            <span v-if='suplier.address2'> - {{suplier.address2}}</span>
                            . {{suplier.city}} - {{suplier.state}}. {{suplier.zipcode}}
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
                supliers: []
            }
        },
        created() {
            this.$http.get('supliers').then(res => {this.supliers = res.data})
        },
        methods: {
            deleteItem(id, index) {
                this.$http.delete('supliers/'+id)
                    .then(() => {
                        this.supliers.splice(index, 1);
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