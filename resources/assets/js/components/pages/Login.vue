<template lang="html">
<div>
  <section class="hero is-primary">
    <div class="hero-bg-register">
      <div class="hero-body">
        <div class="container">
          <h1 class="title">
            <span class="has-background-primary">
              Welcome Back!
            </span>
          </h1>
        </div>
      </div>
    </div>
  </section>
  <section class="section">
    <div class="container">
      
      <div class="content">
        Login to your Mythril account below. <router-link :to="{name: 'Register'}">Need to register?</router-link>
      </div>

      <div class="columns">
        <div class="column is-half is-offset-one-quarter">
          <form>
            <div class="field">
              <label class="label">Email</label>
              <p class="control has-icons-left has-icons-right">
                <input :class="{'input': true, 'is-danger': errors.has('email') }" type="email" placeholder="Email"  v-model="email" name="email" v-validate="'required|email'">
                <span class="icon is-small is-left">
                  <i class="fas fa-envelope"></i>
                </span>
                <span v-show="errors.has('email')" class="help is-danger">{{ errors.first('email') }}</span>
              </p>
            </div>
            <div class="field">
              <label class="label">Password</label>
              <p class="control has-icons-left">
                <input class="input" type="password" placeholder="Password" v-model="password" name="password" v-validate="'required'">
                <span class="icon is-small is-left">
                  <i class="fas fa-lock"></i>
                </span>
                <span v-show="errors.has('password')" class="help is-danger">{{ errors.first('password') }}</span>
              </p>
            </div>
            <div class="field is-grouped">
              <div class="control">
                <button class="button is-primary" @click.prevent="validateBeforeSubmit" :disabled="errors.any()">Login</button>
              </div>
              <div class="control">
                <a class="button is-text" href="/password/reset">Forgot Password?</a>
              </div>
            </div>
          </form>
        </div>
      </div>

    </div>
  </section>
</div>
</template>

<script>
import Auth from '../../utilities/Auth'
import VeeValidate from 'vee-validate';
export default {
  data() {
    return {
      email: '',
      password: '',
    }
  },
  methods: {
      validateBeforeSubmit() {
        this.$validator.validateAll().then((result) => {
          if (result) { this.login() }
        });
      },
      login() {
        axios.post('/api/auth/login', {email: this.email, password: this.password})
            .then(
                (response) => {
                    Auth.storeTokens(response);
                    Auth.setAuthHeader();

                    //Update VueX State with User Object
                    axios.post('/api/auth/me').then((response) => { 
                        this.$store.commit('userLoggedIn', response.data);
                    }).catch((error) => console.log(error));
                    
                    //Notify user of success
                    flash('Welcome back!', 'success')

                    //Redirect to Home Page
                    // this.$router.replace({ name: 'Home'})
                    this.$router.go(-1)
                }
            ).catch((error) => flash(error.response.data.error, 'error') );
      }
    }
}
</script>