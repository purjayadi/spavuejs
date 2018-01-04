<template>
    <div>
    <navbar></navbar>
      <div class="container" v-if="this.token">
              <div class="row">
                  <div class="col-md-12">
                      <div class="panel panel-default">
                          <div class="panel-heading">Dashboard</div>

                          <div class="panel-body">
                              <h4>Selamat Datang Administrator !</h4>
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
            navbar: require('./Navbar.vue'),
        },
        data: function() {
          return{
              token: localStorage.getItem('token')
          }
        },
        mounted() {
            if (this.token ===null){
                this.$router.replace('/login')
            }
            else {
                console.log('Component mounted.')
            }
        }
    }
</script>
