<template>
    <div class="row navbarContainer">
        <div class="container">
            <nav class="navbar navbar-expand-lg">
                <router-link to='/' class='navbar-brand'>Studio Manager</router-link>
                <button class="navbar-toggler btn btn-light" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false">
                    <i class="fas fa-bars"></i>
                </button>


                <transition enter-active-class="animated fadeIn" leave-active-class="animated fadeOut" mode='out-in'>
                    <div class="collapse navbar-collapse" id="navbar" v-if='this.$store.state.PassportApiToken.token'>
                        <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="people" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    People
                                </a>
                                <div class="dropdown-menu" aria-labelledby="people">
                                    <router-link 
                                        to='/customers' 
                                        class='dropdown-item' 
                                        data-toggle="collapse" 
                                        data-target=".navbar-collapse.show"
                                    >
                                        Customers
                                    </router-link>

                                    <router-link 
                                        to='/supliers' 
                                        class='dropdown-item' 
                                        data-toggle="collapse" 
                                        data-target=".navbar-collapse.show"
                                    >
                                        Supliers
                                    </router-link>

                                    <router-link 
                                        to='/team' 
                                        class='dropdown-item' 
                                        data-toggle="collapse" 
                                        data-target=".navbar-collapse.show"
                                    >
                                        Team
                                    </router-link>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="sales" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Sales
                                </a>
                                <div class="dropdown-menu" aria-labelledby="sales">
                                    <router-link 
                                        to='/products' 
                                        class='dropdown-item'
                                        data-toggle="collapse" 
                                        data-target=".navbar-collapse.show"
                                    >
                                        Products &amp; Services
                                    </router-link>

                                    <router-link 
                                        to='/sales' 
                                        class='dropdown-item'
                                        data-toggle="collapse" 
                                        data-target=".navbar-collapse.show"
                                    >
                                        Sales
                                    </router-link>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="events" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Events
                                </a>
                                <div class="dropdown-menu" aria-labelledby="events">
                                    <router-link 
                                        to='/events' 
                                        class='dropdown-item'
                                        data-toggle="collapse" 
                                        data-target=".navbar-collapse.show"
                                    >
                                        Events
                                    </router-link>

                                    <router-link 
                                        to='/schedule' 
                                        class='dropdown-item'
                                        data-toggle="collapse" 
                                        data-target=".navbar-collapse.show"
                                    >
                                        Schedule
                                    </router-link>
                                </div>
                            </li>

                            <li class="nav-item">
                                <a href='/' @click.prevent.stop='logout()' class='nav-link'>Logout</a>
                            </li>
                        </ul>
                    </div>
                </transition>
            </nav>
        </div>
    </div>     
</template>

<script>
    export default {
        data() {
            return {

            }
        },
        methods: {
            logout() {
                this.$store.commit('setToken', null)
                this.$http.defaults.headers.common['Authorization'] = null
                this.$router.push({ name: 'login'})
            }
        }
    }
</script>

<style>
    .navbarContainer
    {
        background: #141E30;  /* fallback for old browsers */
        background: -webkit-linear-gradient(to left, #243B55, #141E30);  /* Chrome 10-25, Safari 5.1-6 */
        background: linear-gradient(to left, #243B55, #141E30); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
        z-index: 10;
    }

    .navbar a
    {
        color:white;

        font-size: 1.2em;
    }

    .navbar a:hover{
        color:white;
        text-decoration: underline;
    }

    .navbarContainer .dropdown-menu {
        background: #141E30;  /* fallback for old browsers */
        background: -webkit-linear-gradient(to left, #243B55, #141E30);  /* Chrome 10-25, Safari 5.1-6 */
        background: linear-gradient(to left, #243B55, #141E30); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
    }

    .dropdown-item:focus, .dropdown-item:hover {
         background: #141E30dd;  /* fallback for old browsers */
        background: -webkit-linear-gradient(to left, #243B55dd, #141E30dd);  /* Chrome 10-25, Safari 5.1-6 */
        background: linear-gradient(to left, #243B55dd, #141E30dd); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

        text-decoration: underline;

    }

    button.navbar-toggler {
        background-color: white;
    }
</style>