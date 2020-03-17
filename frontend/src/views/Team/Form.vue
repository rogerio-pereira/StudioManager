<template>
    <div class='row'>
        <input type='hidden' id='id' v-model='team.id'>
        
        <div class="form-group col-md-6">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" v-model='team.name'>
            <div class='text-danger' v-if='errors.name'>
                <small>
                    <p v-for='(error, index) in errors.name' :key='index'>{{error}}</p>
                </small>
            </div>
        </div>
        
        <div class="form-group col-md-6">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" v-model='team.email'>
            <div class='text-danger' v-if='errors.email'>
                <small>
                    <p v-for='(error, index) in errors.email' :key='index'>{{error}}</p>
                </small>
            </div>
        </div>
        
        <div class="form-group col-md-6">
            <label for="phone">Phone</label>
            <input type="text" class="form-control" id="phone" v-model='team.phone'>
            <div class='text-danger' v-if='errors.phone'>
                <small>
                    <p v-for='(error, index) in errors.phone' :key='index'>{{error}}</p>
                </small>
            </div>
        </div>
        
        <div class="form-group col-md-6">
            <label for="own_equipment">Own Equipment?</label>
            <select id='own_equipment' v-model='team.own_equipment' class='form-control'>
                <option value='0'>No</option>
                <option value='1'>Yes</option>
            </select>
            <div class='text-danger' v-if='errors.own_equipment'>
                <small>
                    <p v-for='(error, index) in errors.own_equipment' :key='index'>{{error}}</p>
                </small>
            </div>
        </div>

        <div class='form-group text-center col-md-12 mt-4'>
            <button type="submit" class="btn btn-success" @click.stop.prevent='save'>
                <i class="far fa-save mr-2"></i> Save
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            team: {type: Object, required: true},
            errors: {type: Object, required:false}
        },
        /*data() {
            return {
                team: {
                    id: null,
                    name: '',
                    initialBalance: 0
                }
            }
        },*/
        methods: {
            save(){
                this.$emit('save', {data: this.team})
            }
        }
    }
</script>

<style scoped>

</style>