<template>
<div>
    <div class="content">
        <h1>Change Your Password</h1>
    </div>

	<div class="field">
	  <label class="label">New Password</label>
	  <div class="control">
	    <input class="input" type="password" placeholder="New Password" v-model="newPassword" name="newPassword" v-validate="'required|min: 6'">
	  </div>
	  <p v-show="errors.has('newPassword')" class="help is-danger">{{ errors.first('newPassword') }}</p>
	</div>

	<div class="field">
	  <label class="label">Confirm New Password</label>
	  <div class="control">
	    <input class="input" type="password" placeholder="Confirm New Password" v-model="newPasswordConfirm" name="newPasswordConfirm" v-validate="'required|min: 6'">
	  </div>
	  <p v-show="errors.has('newPasswordConfirm')" class="help is-danger">{{ errors.first('newPasswordConfirm') }}</p>
	</div>

	<div class="field">
	  <div class="control">
	    <button class="button is-primary" @click="validateBeforeSubmit">Update</button>
	  </div>
	</div>

</div>
</template>

<script>
import { ErrorBag } from 'vee-validate';
const bag = new ErrorBag();

export default {
    props: ['user'],
    data() {
      return {
        newPassword: '',
        newPasswordConfirm: '',
      }
    },
    methods: {
      	validateBeforeSubmit() {
	        this.$validator.validateAll().then((result) => {
	          if (result) { 
	          	if(this.newPassword == this.newPasswordConfirm) {
	          		this.updatePassword()
	          	} 
	          	else {
	          		flash('Passwords Must Match', 'error');
	          	}
	          }
	        });
      	},
  	  	updatePassword() {
    			axios.patch(('/api/users/' + this.user.id ), {
	              new_password: this.newPassword,
	              update: 'password'
	            })
    		    .then((response) => { 
    		    	if((response.status === 401) || (response.status === 403) || (response.status === 404)) { 
		                this.$router.replace({ name: 'Home'}) 
		                flash('Unauthorized to Update Settings', 'error');
		            }
		            else if(response.status === 200) { 
		                flash('Password Updated', 'success');
		                this.newPassword = ''
		                this.newPasswordConfirm = ''
		            }
    		    })
    		    .catch((error) => this.$router.replace({ name: 'Home'}) );
  	  	}
  	}
}
</script>