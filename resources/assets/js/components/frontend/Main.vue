<template>
    <div>
        <navbar></navbar>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default" v-for="row in halamen">
                        <div class="panel-heading">{{ row.judul }}</div>
                        <div class="panel-body">
                           <div v-html="row.deskripsi"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        components: {
            navbar: require('../administrator/Navbar.vue'),
        },
        data : function () {
            return{
                halamen : []
            }
        },
        mounted(){
            console.log(this.$route.params);
            var app = this;
            app.getHalaman();
        },
        methods :{
        getHalaman(page) {
            var app = this;
            if (typeof page === 'undefined'){
                page = 1;
            }
            axios.get('/api/gethalaman?page='+page)
                .then(function (resp){
                    app.halamen = resp.data.data;
                    app.halamenData = resp.data;
                    app.loading = false;
                })
                .catch(function (resp){
                    console.log(resp);
                    app.loading = false;
                    alert("Could not load halaman");
                });
        }
        }
    }
</script>

<style scoped>

</style>