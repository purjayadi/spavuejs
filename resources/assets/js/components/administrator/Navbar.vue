<template>
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">
                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <!-- Branding Image -->
                <router-link to="/" ><a class="navbar-brand">Laravel</a></router-link>
            </div>
            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    &nbsp;
                </ul>
                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right" v-if="this.token" >
                    <!-- Authentication Links -->
                    <router-link to="/dashboard" tag="li" ><a>Dashboard</a></router-link>
                    <router-link to="/halaman" tag="li" ><a>Halaman</a></router-link>
                    <router-link to="/menus" tag="li" ><a>Menu</a></router-link>
                    <router-link to="/submenu" tag="li" ><a>Submenu</a></router-link>
                    <router-link to="/work" tag="li" ><a>Work</a></router-link>
                    <router-link to="/portfolio" tag="li" ><a>Portfolio</a></router-link>
                    <router-link to="/logout" tag="li" ><a>Log Out</a></router-link>
                </ul>
                <ul class="nav navbar-nav navbar-right" v-else>
                <!-- Authentication Links -->
                    <router-link to="/register" tag="li" ><a>Register</a></router-link>
                    <router-link to="/login" tag="li" ><a>Login</a></router-link>

                </ul>
            </div>
        </div>
    </nav>
</template>
<script>
    export default {
        data: function () {
            return {
                token: localStorage.getItem('token'),
                menus: []
            }
        },
        mounted() {
            //do something after mounting vue instance
            var app = this;
            app.getMenus()
        },
        methods: {
            getMenus(page) {
                var app = this;
                if (typeof page === 'undefined'){
                    page = 1;
                }
                // ?page='+page+'
                axios.get('/api/getmenu?page='+page)
                    .then(function (resp){
                        app.menus = resp.data.data;
                        app.menusData = resp.data;
                        app.loading = false;
                    })
                    .catch(function (resp){
                        console.log(resp);
                        app.loading = false;
                        alert("Could not load menu");
                    });
            }
        }
    }
</script>

