<template>
    <div id="app">
        <menu-app />

        <div class='row content my-3 mb-5 contentContainer'>
            <div class='container'>
                <transition enter-active-class="animated fadeInDown" leave-active-class="animated fadeOutUp" mode='out-in'>
                    <router-view/>
                </transition>
            </div>
        </div>

        <footer-app />
    </div>
</template>

<script>
    import MenuApp from './components/template/Menu'
    import FooterApp from './components/template/Footer'

    export default {
        components: {
            MenuApp,
            FooterApp
        },
        data() {
            return {
                user: null
            }
        },
        created() {
            if(this.$store.state.PassportApiToken.token) {
                this.$http.defaults.headers.common['Authorization'] = 'Bearer '+this.$store.state.PassportApiToken.token
            }
            else {
                this.$router.push({ name: 'login'})
            }
        }
    }
</script>

<style>
    * {
        font-family: 'Open Sans Condensed', sans-serif;
    }

    body {
        background: #D9D2E6;  /* fallback for old browsers */
        background: -webkit-linear-gradient(to bottom, #E9E4F0, #D9D2E6);  /* Chrome 10-25, Safari 5.1-6 */
        background: linear-gradient(to bottom, #E9E4F0, #D9D2E6); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

        overflow-x: hidden;
        font-size: 1.4rem !important;
    }

    h1 {
        font-size: 2.2em;
        font-weight: 700 !important;
    }

    div.contentContainer {
        z-index: 0;
    }

    div.content{
        min-height: 500px;
    }

    .btn-primary {
        background-color: #233A54 !important;
        border-color: #233A54 !important;
    }

    .btn-primary:hover {
        background-color: #141F31 !important;
        border-color: #141F31 !important;
    }
</style>
