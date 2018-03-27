<template>
<div>  
	<section class="hero is-primary">
    <div id="animationBG" v-bind:style="bannerStyle">
	  <div class="hero-body">
	    <div class="container">
	    	<div class="columns is-mobile is-multiline">

	    		<div class="column is-2">
                    <figure class="card imageFade image">
                        <img :src="userAvatar" :alt="this.displayedUser.username">
                    </figure>
	    		</div>

				<div class="column is-narrow">
					<h1 class="title">
						{{ this.displayedUser.username }} <span class="tag is-light">Staff</span>
					</h1>
					<h2 class="subtitle">
						<span class="has-text-light is-size-6">
							Joined on {{ this.displayedUser.created_at | dateFormat }}
						</span>
					</h2>
				</div>

		    	<div class="column is-12-touch" style="margin-top: auto;">
					<!-- Social Links (ie. Twitter, Youtube etc) & Follow Button -->
					<social-component 
						:user="user"
						:displayedUser="displayedUser" 
						:userOwns="userOwns" 
						@authModal="toggleModal('auth')"
						>
					</social-component>
				</div>

	    	</div>
	    </div>
	  </div>
	</div>
	</section>
	<section class="tab-container">
		<div class="container">
			<div class="tabs is-toggle">
			  <ul>
				<router-link tag="li" :to="{name: 'User'}" exact>
				  <a>Summary</a>
				</router-link>
				<router-link tag="li" :to="{name: 'UserLibrary'}">
				  <a>Library</a>
				</router-link>
				<router-link tag="li" :to="{name: 'UserReviews'}">
				  <a>Reviews ({{ this.displayedUser.reviews_count }})</a>
				</router-link>
				<router-link tag="li" :to="{name: 'UserRecommendations'}">
				  <a>Recommendations ({{ this.displayedUser.recommendations_count }})</a>
				</router-link>
				<router-link tag="li" :to="{name: 'UserFollowers'}">
				  <a>Followers ({{ this.displayedUser.followers_count }})</a>
				</router-link>
				<router-link tag="li" :to="{name: 'UserFollowing'}">
				  <a>Following ({{ this.displayedUser.following_count }})</a>
				</router-link>
				<router-link tag="li" :to="{name: 'UserStats'}">
				  <a>Stats</a>
				</router-link>
			  </ul>
			</div>
		</div>
	</section>
	<section class="section">
		<div class="container">
			<router-view :displayedUser="displayedUser" :userOwns="userOwns" :key="$route.fullPath"></router-view>
		</div>
	</section>
	<authentication-modal :modalState="authModalState" v-on:close="toggleModal('auth')"></authentication-modal>	 
</div>
</template>

<script>
import moment from 'moment';
import AuthenticationModal from '../../utilities/AuthenticationModal.vue';
import SocialComponent from './components/SocialComponent.vue';

export default {
  props: [ 'user' ],
  components:{
    'authentication-modal': AuthenticationModal,
    'social-component': SocialComponent
  },
  metaInfo () {
  	return {
    	titleTemplate: '%s - User Profile - ' + this.displayedUser.username 
    	// meta: [
     //  		{ name: 'description', content: this.description },
     //  		{ name: 'keywords', content: this.tags }
    	// ]
  	}
  },
  data() {
    return {
      displayedUser: [],
      authenticationModal: false,
      userOwns: false,
      userFollows: false,
    }
  },
  filters: {
  	truncate: function(string, value) {
    	return string.substring(0, value) + '...';
    },
    dateFormat(date) {
		return moment(date).format("MMMM Do, YYYY");
	}
  },
  computed: {
    authModalState() {
    	return this.authenticationModal;
    },
    userAvatar() {
    	if(this.displayedUser.avatar) { return 'https://mythril.nyc3.digitaloceanspaces.com/users/avatars/' + this.displayedUser.avatar;}
    	else { return 'https://mythril.nyc3.digitaloceanspaces.com/users/avatars/default.jpg' }
    },
	userBannerURL() {
		if(this.displayedUser.banner) { return 'https://mythril.nyc3.digitaloceanspaces.com/users/banners/' + this.displayedUser.banner;}
	},
	bannerStyle() {
		if(this.displayedUser.banner) {
			return {
		      	backgroundImage: 'linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url(' + this.userBannerURL + ')',
		      	backgroundSize: 'cover',
		      	backgroundPosition: 'center', 
		      	backgroundRepeat: 'no-repeat'
			}
		}
	}
  },
  watch: {
    user: function () { this.checkUserInfo() },
    '$route.params.id': function (id) {
    	this.initialize()
    }
  },
  methods: {
    toggleModal(modal) {
        if(modal == 'auth') { this.authenticationModal = !this.authenticationModal; }
    },
    followGuard() {
        if(!this.user) { this.toggleModal('auth') }
        else { this.followUser() }
    },
  	getUser() {
		axios.get('/api/users/' + this.$route.params.id )
	    .then((response) => { 
	    	if(response.data === 404) { this.$router.replace({ name: 'Users'}) }
	    	this.displayedUser = response.data.user; 
	    	this.userOwns = response.data.userOwns; 
	    })
	    .catch((error) => this.$router.replace({ name: 'Users'}) );
  	},
  	checkUserInfo() {
  		if(!this.user) {
  			this.userOwns = false;
  			this.userFollows = false;
  		}
  	},
  	initialize() {
		this.getUser();
	    if(this.user) { this.checkUserInfo() }
  	}
  },
  created() {
  	this.initialize()
  }
}
</script>