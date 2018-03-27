<template>
<div class="buttons is-right">
<!-- 	  		  
	      battleNet: '',
 -->

	<a class="button is-dark tooltip" data-tooltip="Twitch" v-if="displayedUser.twitch" style="background-color: #6441a4" :href="'https://www.twitch.tv/' + displayedUser.twitch">
      <span class="icon is-small">
        <i class="fab fa-twitch"></i>
      </span>
  </a>	
	<a class="button is-dark tooltip" data-tooltip="Youtube" v-if="displayedUser.youtube" style="background-color: #ff0000" :href="displayedUser.youtube">
      <span class="icon is-small">
        <i class="fab fa-youtube"></i>
      </span>
  </a>	
	<span class="button is-dark tooltip" :data-tooltip="'Discord ('+displayedUser.discord+')'" v-if="displayedUser.discord" style="background-color: #7289da" @click="copyToClipboard(displayedUser.discord)">
      <span class="icon is-small">
        <i class="fab fa-discord"></i>
      </span>
  </span>
	<a class="button is-dark tooltip" data-tooltip="Twitter" v-if="displayedUser.twitter" style="background-color: #1da1f2" :href="'https://twitter.com/' + displayedUser.twitter">
      <span class="icon is-small">
        <i class="fab fa-twitter"></i>
      </span>
  </a>	
	<a class="button is-dark tooltip" data-tooltip="Facebook" v-if="displayedUser.facebook" style="background-color: #3B5998" :href="displayedUser.facebook">
      <span class="icon is-small">
        <i class="fab fa-facebook"></i>
      </span>
  </a>		
	<a class="button is-dark tooltip" data-tooltip="Instagram" v-if="displayedUser.instagram" style="background-color: #c32aa3" :href="'https://www.instagram.com/' + displayedUser.instagram">
      <span class="icon is-small">
        <i class="fab fa-instagram"></i>
      </span>
  </a>
	<a class="button is-dark tooltip" data-tooltip="Deviant Art" v-if="displayedUser.deviant_art" style="background-color: #05cc47" :href="'https://'+ displayedUser.deviant_art +'.deviantart.com/'">
      <span class="icon is-small">
        <i class="fab fa-deviantart"></i>
      </span>
  </a>
	<a class="button is-dark tooltip" data-tooltip="Reddit" v-if="displayedUser.reddit" style="background-color: #ff4500" :href="'https://www.reddit.com/user/' + displayedUser.reddit">
      <span class="icon is-small">
        <i class="fab fa-reddit-alien"></i>
      </span>
  </a>
	<a class="button is-dark tooltip" data-tooltip="Patreon" v-if="displayedUser.patreon" style="background-color: #f96854" :href="displayedUser.patreon">
      <span class="icon is-small">
        <i class="fab fa-patreon"></i>
      </span>
  </a>
	<a class="button is-dark tooltip" data-tooltip="Tumblr" v-if="displayedUser.tumblr" style="background-color: #35465d" :href="'http://' + displayedUser.tumblr + '.tumblr.com'">
      <span class="icon is-small">
        <i class="fab fa-tumblr"></i>
      </span>
  </a>
	<a class="button is-dark tooltip" data-tooltip="Steam" v-if="displayedUser.steam" style="background-color: #171A21" :href="displayedUser.steam">
      <span class="icon is-small">
        <i class="fab fa-steam"></i>
      </span>
  </a>
	<span class="button is-dark tooltip" :data-tooltip="'Playstation ('+displayedUser.playstation+')'" v-if="displayedUser.playstation" style="background-color: #003791" @click="copyToClipboard(displayedUser.playstation)">
      <span class="icon is-small">
        <i class="fab fa-playstation"></i>
      </span>
  </span>
	<span class="button is-dark tooltip" :data-tooltip="'Nintendo Switch ('+displayedUser.nintendo_switch+')'" v-if="displayedUser.nintendo_switch" style="background-color: #D20014" @click="copyToClipboard(displayedUser.nintendo_switch)">
      <span class="icon is-small">
        <i class="fab fa-nintendo-switch"></i>
      </span>
  </span>
	<span class="button is-dark tooltip" :data-tooltip="'Xbox ('+displayedUser.xbox+')'" v-if="displayedUser.xbox" style="background-color: #52b043" @click="copyToClipboard(displayedUser.xbox)">
      <span class="icon is-small">
        <i class="fab fa-xbox"></i>
      </span>
  </span>
	<span class="button" v-bind:class="{ 'is-loading': isLoading }" v-if="followButton" @click="followUser">
      <span>Follow</span>
  </span>
  <span class="button is-danger" v-bind:class="{ 'is-loading': isLoading }" v-if="following" @click="unfollowUser">
      <span>Unfollow</span>
  </span>
</div>
</template>

<script>
export default {
  props: [ 'displayedUser', 'userOwns', 'user'],
  data() {
    return {
      following: false,
      isLoading: false 
    }
  },
  watch: {
    user: function () { this.checkfollowStatus() },
    '$route.params.id': function (id) {
      this.checkfollowStatus()
    }
  },
  computed: {
    followButton() {
      if(this.userOwns) { return false }
      else if (this.following) {return false}
      else { return true }
    },
  },
	methods: {
    copyToClipboard(value) {
  			value.select();
  			document.execCommand("Copy");
        flash('"' + value + '" has been copied to the clipboard', 'success');
  	},
		followUser() {
        if(!this.user) { this.$emit('authModal') }
        else { 
          this.isLoading = true
          axios.post('/api/users/' + this.$route.params.id + '/follow')
          .then((response) => { 
            if(response.status === 200) { this.following = true } 
            else { this.following = false }
            this.isLoading = false
          })
          .catch((error) => {
            this.following = false
            this.isLoading = false
          });
        }
		},
    unfollowUser() {
        if(!this.user) { this.$emit('authModal') }
        else { 
          this.isLoading = true
          axios.delete('/api/users/' + this.$route.params.id + '/unfollow')
          .then((response) => { 
            if(response.status === 200) { this.following = false }
            this.isLoading = false
          })
          .catch((error) => {
            this.isLoading = false
          });
        }
    },
    checkfollowStatus() {
      //check if logged in user is following displayed user users/{id}/follow
      axios.get('/api/users/' + this.$route.params.id + '/follow')
      .then((response) => { 
        this.following = response.data.following;
        if(response.status === 401) { this.following = false } 
      })
      .catch((error) => {
        if(error.response.status === 401) { this.following = false } 
      });
    }
	},
  created() {
    if(this.user) { this.checkfollowStatus() }
  }
}
</script>