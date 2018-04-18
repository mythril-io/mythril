<template>
<div>  
	<section class="hero is-primary">
	  <div class="hero-body">
	    <div class="container">
	    	<div class="columns is-mobile">

	    		<div class="column is-1 is-hidden-touch">
		            <div class="card">
		                <div class="card-image imageFade">
		                    <figure class="image">
		                        <img :src="userAvatar" class="imageFade" :alt="user.username">
		                    </figure>
		                </div>
		            </div>
	    		</div>

		    	<div class="column">
					<h1 class="title">
						Settings
					</h1>
					<h2 class="subtitle">
						<span class="has-text-grey-dark is-size-6">Update Your Information</span>
					</h2>
				</div>

	    	</div>
	    </div>
	  </div>
	  <div class="hero-foot">
	    <nav class="tabs is-boxed is-fullwidth">
	      <div class="container">
	        <ul>
	          <router-link tag="li" :to="{name: 'UserSettings'}" exact>
	            <a>Profile</a>
	          </router-link>
	          <router-link tag="li" :to="{name: 'UserSettingsImages'}">
	            <a>Avatar &amp; Banner</a>
	          </router-link>
	          <router-link tag="li" :to="{name: 'UserSettingsSocial'}">
	            <a>Social Accounts</a>
	          </router-link>
	          <router-link tag="li" :to="{name: 'UserSettingsPassword'}">
	            <a>Password</a>
	          </router-link>
	        </ul>
	      </div>
	    </nav>
	  </div>
	</section>
	<section class="section">
		<div class="container">
			<router-view :user="user"></router-view>
		</div>
	</section>
</div>
</template>

<script>
var moment = require('moment-timezone');

export default {
  props: [ 'user' ],
  data() {
    return {
    }
  },
  computed: {
    userAvatar() {
    	if(this.user.avatar) { return this.$store.state.cdnURL + 'users/avatars/' + this.user.avatar;}
    	else { return this.$store.state.cdnURL + 'users/avatars/default.jpg' }
    }
  },
  watch: {
    user: function () { 
  		if(!this.user) { this.$router.replace({ name: 'Home'}) }
    }
  }
}
</script>