<template>

</template>

<script>
    export default {
        components: {
            navbar: require('../administrator/Navbar.vue'),
        },
        name: "logout",
        mounted () {
            localStorage.removeItem('token'),
                console.log('Succesfully Logout'),
                this.$router.replace('/login')
        }
    }
</script>

<style scoped>

</style>