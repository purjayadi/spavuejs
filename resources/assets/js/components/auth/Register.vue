<template lang="html">
    <div>
        <navbar></navbar>
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="panel panel-default">
                            <div class="panel-heading">Register</div>

                            <div class="panel-body">
                                <form class="form-horizontal" @submit.prevent="registerUser" method="POST">
                                    <div class="form-group">
                                        <label for="name" class="col-md-4 control-label">Name</label>

                                        <div class="col-md-6">
                                            <input id="name" type="text" v-model="register.name" class="form-control" name="name" required autofocus>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                                        <div class="col-md-6">
                                            <input id="email" type="email" v-model="register.email" class="form-control" name="email"required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="password" class="col-md-4 control-label">Password</label>

                                        <div class="col-md-6">
                                            <input id="password" type="password" v-model="register.password" class="form-control" name="password" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-6 col-md-offset-4">
                                            <button type="submit" class="btn btn-primary">
                                                Register
                                            </button>
                                        </div>
                                    </div>
                                </form>
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
  data(){
      return {
          register: {
              name: '',
              email: '',
              password: ''
          },
          token: localStorage.getItem('token')
      }
  },
    methods:{
      registerUser(){
          axios.post('/api/auth/register?token='+this.token, this.register)
              .then(response =>{
                  console.log(response);
          })
          .catch(error =>{
                 console.log(error.response)
          })
      }
    }
}
</script>

<style lang="css">
</style>
