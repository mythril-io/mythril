<template lang="html">
<div>
  <section class="hero is-primary">
    <div class="hero-bg-register">
      <div class="hero-body">
        <div class="container">
          <h1 class="title">
            Register an Account with Mythril!
          </h1>
<!--           <h2 class="subtitle">
            Join the Community!
          </h2> -->
        </div>
      </div>
    </div>
  </section>
  <section class="section">
    <div class="container">

      <div class="columns">

        <div class="column">   
          <h1 class="title">Features</h1>  
          <div class="content">
              <ul>
                <li>Track your video games</li>
                <li>Write reviews and share your opinion</li>
                <li>Recommend games to the community</li>
              </ul>
          </div>
        </div>

        <div class="column">
          <form @submit.prevent="onSubmit">
            <div class="field">
              <label class="label">Email</label>
              <p class="control has-icons-left">
                <input :class="{'input': true, 'is-danger': errors.has('email') }" type="email" placeholder="Email"  v-model="email" name="email" v-validate="'required|email'">
                <span class="icon is-small is-left">
                  <i class="fas fa-envelope"></i>
                </span>
                <span v-show="errors.has('email')" class="help is-danger">{{ errors.first('email') }}</span>
              </p>
            </div>
            <div class="field">
              <label class="label">Username</label>
              <p class="control has-icons-left">
                <input :class="{'input': true, 'is-danger': errors.has('username') }" type="text" placeholder="Username"  v-model="username" name="username" v-validate="'required'">
                <span class="icon is-small is-left">
                  <i class="fas fa-user"></i>
                </span>
                <span v-show="errors.has('username')" class="help is-danger">{{ errors.first('username') }}</span>
              </p>
            </div>
            <div class="field">
              <label class="label">Password</label>
              <p class="control has-icons-left">
                <input class="input" type="password" placeholder="Password" v-model="password" name="password" v-validate="'required|min: 6'">
                <span class="icon is-small is-left">
                  <i class="fas fa-lock"></i>
                </span>
                <span v-show="errors.has('password')" class="help is-danger">{{ errors.first('password') }}</span>
              </p>
            </div>
            <div class="field">
              <label class="label">Confirm Password</label>
              <p class="control has-icons-left">
                <input class="input" type="password" placeholder="Confirm Password" v-model="passwordConfirm" name="passwordConfirm" v-validate="'required|min: 6'">
                <span class="icon is-small is-left">
                  <i class="fas fa-lock"></i>
                </span>
                <span v-show="errors.has('passwordConfirm')" class="help is-danger">{{ errors.first('passwordConfirm') }}</span>
              </p>
            </div>
            <div class="field">
              <p class="control">
                <vue-recaptcha
                  ref="invisibleRecaptcha"
                  @verify="onVerify"
                  @expired="onExpired"
                  size="invisible"
                  :sitekey="sitekey">
                </vue-recaptcha>
              </p>
            </div>
            <div class="field">
              <p class="control">
                <button class="button is-primary" type="submit" :disabled="errors.any()">
                  Register
                </button>
              </p>
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
import NProgress from 'nprogress'
import VueRecaptcha from 'vue-recaptcha';

export default {
  components: { VueRecaptcha },
  data() {
    return {
      email: '',
      username: '',
      password: '',
      passwordConfirm: '',
      sitekey: '6LcQXk8UAAAAAIaCSzs026CYI7F7sP5PFd8pNNj7'
    }
  },
  methods: {
      onSubmit() {
        this.$refs.invisibleRecaptcha.execute()
      },
      onVerify: function (response) {
        this.$validator.validateAll().then((result) => {
          if (result) { 
            if(this.password == this.passwordConfirm) {
              this.resetRecaptcha()
              this.register(response)
            } 
            else {
              flash('Passwords Must Match', 'error');
            }
          }
        });
      },
      resetRecaptcha() {
        this.$refs.invisibleRecaptcha.reset()
      },
      register(response) {
      	NProgress.start();
      	var validation = true;
        axios.post('/api/auth/register', {email: this.email, username: this.username, password: this.password, recaptcha: response})
            .catch((error) => { 
            	if(error.response.data.errors)
            	{
            		NProgress.done();
            		validation = false;
            		var errorsReadable = this.errorHandler(error.response.data.errors);
            		flash(errorsReadable, 'error')
            	}
            })
            .then(
                (response) => {
                	if(validation) {	                    
	                    flash('Sucessfully registered :) Please confirm your email.', 'success') 
	                    this.$router.replace({ name: 'Home'})
                	}
                	validation = true;
                	NProgress.done();
                }
            );
      },
      errorHandler(errorsArray) {
      	var i, errorsReadable = "";
      	if(errorsArray.email) {
	      	for (i = 0; i < errorsArray.email.length; i++) {
	    			errorsReadable += errorsArray.email[i] + "<br>";
			    }
		    }
		    
        if(errorsArray.username) {
	      	for (i = 0; i < errorsArray.username.length; i++) {
	    			errorsReadable += errorsArray.username[i] + "<br>";
    			}
    		}

    		if(errorsArray.password) {
    	      	for (i = 0; i < errorsArray.password.length; i++) {
    	    			errorsReadable += errorsArray.password[i] + "<br>";
    			}
    		}

        if(errorsArray.recaptcha) {
              for (i = 0; i < errorsArray.recaptcha.length; i++) {
                errorsReadable += errorsArray.recaptcha[i] + "<br>";
          }
        }

		    return errorsReadable;
      }
    }
}
</script>