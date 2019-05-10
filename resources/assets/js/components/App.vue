<template lang="html">
  <div>
    <header-component :user="user" v-on:logout="logout"></header-component>

        <router-view :user="user"></router-view>
        <router-view :user="user" name="reload" :key="$route.fullPath"></router-view>

    <footer-component></footer-component>
  </div>
</template>

<script>
import Auth from '../utilities/Auth'
import NProgress from 'nprogress'
import Header from './layout/Header.vue';
import Footer from './layout/Footer.vue';

export default {
  components:{
    'header-component': Header,
    'footer-component': Footer
  },
  methods: {
    logout() {
      NProgress.start();
      
      axios.post('/api/auth/logout')
        .then((response) => { 
          Auth.removeSession();
          this.$snackbar.open({
              message: 'See you later!', 
              type: 'is-primary',
              actionText: null
          })
          //this.$router.go()
        })
        .catch((error) => flash('Could not logout', 'error'));
        
      NProgress.done();
    }
  },
  computed: {
    user() {
      return this.$store.state.user;
    }
  }
}
</script>