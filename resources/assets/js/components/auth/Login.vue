<template>
    <div>
        <navbar></navbar>
            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-md-offset-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">Login</div>
                            <div class="panel-body">
                                <form class="form-horizontal" @submit.prevent="logIn">

                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label for="email" >E-Mail Address</label>
                                            <input id="email" type="email" class="form-control" v-model="email" placeholder="Inser your email" required autofocus>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label for="password">Password</label>
                                            <input id="password" type="password" class="form-control" placeholder="Password" v-model="password" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-6">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" > Remember Me
                                                </label>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary btn-block">Login</button>
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
            return{
                email:"",
                password:"",
                token: localStorage.getItem('token')
            }
        },
        mounted() {
            if (this.token !==null){
                this.$router.replace('/dashboard')
            }
            else {
                console.log('Component mounted.');
                console.log(this.$route.params);
            }
        },
        methods:{
            logIn(){
                axios.post('/api/auth/signin',{email:this.email,password:this.password})
                    .then(response=>{
                    let token= response.data.token;
                if(token){
                    localStorage.setItem('token',token);
                    this.$router.replace('/dashboard');
                }
                console.log(token);
            })
            }
        }
    }
</script>
