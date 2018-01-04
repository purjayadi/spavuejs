import VueRouter from 'vue-router'
let routes=[
    {
      path: '/logout',
        component: require('./components/auth/Logout')
    },
  {
    path:'/register',
    component: require('./components/auth/Register'),
      meta: {title: 'Register new user'}
  },
  {
    path:'/login',
    component: require('./components/auth/Login'),
      meta: {title: 'Login'}
  },
  {
    path:'/dashboard',
    component: require('./components/administrator/Dashboard'),
    meta: { title: 'SPA Laravel Vuejs' }
  },
  {
    path :'/halaman',
    component:require('./components/administrator/Halaman'),
    meta: { title: 'halaman - laravel' }
  },
  {
    path: '/menus',
    component:require('./components/administrator/Menus'),
    meta: { title: 'menu - laravel' }
  },
  {
    path: '/submenu',
    component:require('./components/administrator/Submenu'),
    meta: { title: 'submenu - laravel' }
  },
  {
    path: '/work',
    component:require('./components/administrator/Workes'),
    meta: { title: 'work - laravel' }
  },
  {
    path: '/portfolio',
    component:require('./components/administrator/Portfolio'),
    meta: { title: 'portfolio - laravel' }
  },
    {
      path:'/',
        component:require('./components/frontend/Main'),
        meta: {title: 'Halaman Utama Laravel SPA Vue JS'}
    }
]

export default new VueRouter({
  routes,
  mode : 'history'
})
