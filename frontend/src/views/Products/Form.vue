<template>
    <div class='row'>
        <input type='hidden' id='id' v-model='product.id'>
        
        <div class="form-group col-md-12">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" v-model='product.name'>
            <div class='text-danger' v-if='errors.name'>
                <small>
                    <p v-for='(error, index) in errors.name' :key='index'>{{error}}</p>
                </small>
            </div>
        </div>
        
        <div class="form-group col-md-6">
            <label for="value">Value</label>
            <input type="number" class="form-control" id="value" v-model='product.value' min='0.01' step='0.01'>
            <div class='text-danger' v-if='errors.value'>
                <small>
                    <p v-for='(error, index) in errors.value' :key='index'>{{error}}</p>
                </small>
            </div>
        </div>
        
        <div class="form-group col-md-6">
            <label for="cost">Cost</label>
            <input type="number" class="form-control" id="cost" v-model='product.cost' min='0.01' step='0.01'>
            <div class='text-danger' v-if='errors.cost'>
                <small>
                    <p v-for='(error, index) in errors.cost' :key='index'>{{error}}</p>
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
            product: {type: Object, required: true},
            errors: {type: Object, required:false}
        },
        /*data() {
            return {
                product: {
                    id: null,
                    name: '',
                    initialBalance: 0
                }
            }
        },*/
        methods: {
            save(){
                this.$emit('save', {data: this.product})
            }
        }
    }
</script>

<style scoped>

</style>