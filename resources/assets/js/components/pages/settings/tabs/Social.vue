<template>
<div>
    <div class="content">
        <h1>Social Accounts</h1>
    </div>
    <div class="columns">
    	<div class="column">
			<div class="field">
			  <label class="label">Twitter</label>
			  <div class="control has-icons-left">
			    <input class="input" type="text" placeholder="Twitter Username" v-model="twitter" name="twitter">
			    <span class="icon is-small is-left">
			      <i class="fab fa-twitter"></i>
			    </span>
			  </div>
			  <p v-show="errors.has('twitter')" class="help is-danger">{{ errors.first('twitter') }}</p>
			</div>

			<div class="field">
			  <label class="label">Facebook</label>
			  <div class="control has-icons-left">
			    <input class="input" type="text" placeholder="Facebook Profile URL" v-model="facebook" name="facebook">
			    <span class="icon is-small is-left">
			      <i class="fab fa-facebook-f"></i>
			    </span>
			  </div>
			  <p v-show="errors.has('facebook')" class="help is-danger">{{ errors.first('facebook') }}</p>
			</div>

			<div class="field">
			  <label class="label">Instagram</label>
			  <div class="control has-icons-left">
			    <input class="input" type="text" placeholder="Instagram Username" v-model="instagram" name="instagram">
			    <span class="icon is-small is-left">
			      <i class="fab fa-instagram"></i>
			    </span>
			  </div>
			  <p v-show="errors.has('instagram')" class="help is-danger">{{ errors.first('instagram') }}</p>
			</div>

			<div class="field">
			  <label class="label">Discord</label>
			  <div class="control has-icons-left">
			    <input class="input" type="text" placeholder="Discord Tag (Username#1234)" v-model="discord" name="discord">
			    <span class="icon is-small is-left">
			      <i class="fab fa-discord"></i>
			    </span>
			  </div>
			  <p v-show="errors.has('discord')" class="help is-danger">{{ errors.first('discord') }}</p>
			</div>

			<div class="field">
			  <label class="label">Deviant Art</label>
			  <div class="control has-icons-left">
			    <input class="input" type="text" placeholder="Deviant Art Username" v-model="deviant_art" name="deviantArt">
			    <span class="icon is-small is-left">
			      <i class="fab fa-deviantart"></i>
			    </span>
			  </div>
			  <p v-show="errors.has('deviantArt')" class="help is-danger">{{ errors.first('deviantArt') }}</p>
			</div>

			<div class="field">
			  <label class="label">Reddit</label>
			  <div class="control has-icons-left">
			    <input class="input" type="text" placeholder="Reddit Username" v-model="reddit" name="reddit">
			    <span class="icon is-small is-left">
			      <i class="fab fa-reddit-alien"></i>
			    </span>
			  </div>
			  <p v-show="errors.has('reddit')" class="help is-danger">{{ errors.first('reddit') }}</p>
			</div>
			<div class="field">
			  <label class="label">Patreon</label>
			  <div class="control has-icons-left">
			    <input class="input" type="text" placeholder="Patreon Project URL" v-model="patreon" name="patreon">
			    <span class="icon is-small is-left">
			      <i class="fab fa-patreon"></i>
			    </span>
			  </div>
			  <p v-show="errors.has('patreon')" class="help is-danger">{{ errors.first('patreon') }}</p>
			</div>
			<div class="field">
			  <label class="labels">Tumblr</label>
			  <div class="control has-icons-left">
			    <input class="input" type="text" placeholder="Tumblr Username" v-model="tumblr" name="tumblr">
			    <span class="icon is-small is-left">
			      <i class="fab fa-tumblr"></i>
			    </span>
			  </div>
			  <p v-show="errors.has('tumblr')" class="help is-danger">{{ errors.first('tumblr') }}</p>
			</div>
    	</div>

    	<div class="column">
			<div class="field">
			  <label class="label">Youtube</label>
			  <div class="control has-icons-left">
			    <input class="input" type="text" placeholder="Youtube Channel URL" v-model="youtube" name="youtube">
			    <span class="icon is-small is-left">
			      <i class="fab fa-youtube"></i>
			    </span>
			  </div>
			  <p v-show="errors.has('youtube')" class="help is-danger">{{ errors.first('youtube') }}</p>
			</div>

			<div class="field">
			  <label class="label">Twitch.TV</label>
			  <div class="control has-icons-left">
			    <input class="input" type="text" placeholder="Twitch Username" v-model="twitch" name="twitch">
			    <span class="icon is-small is-left">
			      <i class="fab fa-twitch"></i>
			    </span>
			  </div>
			  <p v-show="errors.has('twitch')" class="help is-danger">{{ errors.first('twitch') }}</p>
			</div>

			<div class="field">
			  <label class="label">Blizzard Battle.net</label>
			  <div class="control">
			    <input class="input" type="text" placeholder="Battle.net BattleTag (Username#1234)" v-model="battle_net" name="battleNet">
			  </div>
			  <p v-show="errors.has('battleNet')" class="help is-danger">{{ errors.first('battleNet') }}</p>
			</div>

			<div class="field">
			  <label class="label">Steam</label>
			  <div class="control has-icons-left">
			    <input class="input" type="text" placeholder="Steam Profile URL" v-model="steam" name="steam">
			    <span class="icon is-small is-left">
			      <i class="fab fa-steam"></i>
			    </span>
			  </div>
			  <p v-show="errors.has('steam')" class="help is-danger">{{ errors.first('steam') }}</p>
			</div>

			<div class="field">
			  <label class="label">Playstation Network</label>
			  <div class="control has-icons-left">
			    <input class="input" type="text" placeholder="Playstation Network Username" v-model="playstation" name="playstation">
			    <span class="icon is-small is-left">
			      <i class="fab fa-playstation"></i>
			    </span>
			  </div>
			  <p v-show="errors.has('playstation')" class="help is-danger">{{ errors.first('playstation') }}</p>
			</div>

			<div class="field">
			  <label class="label">Nintendo Switch Friend Code</label>
			  <div class="control has-icons-left">
			    <input class="input" type="text" placeholder="Nintendo Switch Friend Code" v-model="nintendo_switch" name="nintendoSwitch">
			    <span class="icon is-small is-left">
			      <i class="fab fa-nintendo-switch"></i>
			    </span>
			  </div>
			  <p v-show="errors.has('nintendoSwitch')" class="help is-danger">{{ errors.first('nintendoSwitch') }}</p>
			</div>

			<div class="field">
			  <label class="label">Xbox Live</label>
			  <div class="control has-icons-left">
			    <input class="input" type="text" placeholder="Xbox Live Username" v-model="xbox" name="xbox">
			    <span class="icon is-small is-left">
			      <i class="fab fa-xbox"></i>
			    </span>
			  </div>
			  <p v-show="errors.has('xbox')" class="help is-danger">{{ errors.first('xbox') }}</p>
			</div>
    	</div>
    </div>

    <div class="field">
	  <div class="control">
	    <button class="button is-primary" @click="validateBeforeSubmit">Update</button>
	  </div>
	</div>
</div>
</template>

<script>
import store from '../../../../store.js'

export default {
    props: ['user'],
    data() {
      return {      
	      twitter: '',
	      facebook: '',
	      instagram: '',
	      discord: '',
		  deviantArt: '',
		  reddit: '',
		  patreon: '',
	      tumblr: '',

		  youtube: '',
	      twitch: '',
	      battleNet: '',
	      steam: '',
	      playstation: '',
	      nintendoSwitch: '',
	      xbox: ''
      }
    },
    watch: {
      user: function () { 
        this.updateSettings()
      }
    },
    methods: {
      	validateBeforeSubmit() {
	        this.$validator.validateAll().then((result) => {
	          if (result) { this.updateProfile() }
	        });
      	},
        updateSettings() {
          this.twitter = this.user.twitter;
          this.facebook = this.user.facebook;
          this.instagram = this.user.instagram;
          this.discord = this.user.discord;
          this.deviant_art = this.user.deviant_art;
          this.reddit = this.user.reddit;
          this.patreon = this.user.patreon;
          this.tumblr = this.user.tumblr;

          this.youtube = this.user.youtube;
          this.twitch = this.user.twitch;
          this.battle_net = this.user.battle_net;
          this.steam = this.user.steam;
          this.playstation = this.user.playstation;
          this.nintendo_switch = this.user.nintendo_switch;
          this.xbox = this.user.xbox;
        },
  	  	updateProfile() {
			axios.patch(('/api/users/' + this.user.id ), {
              twitter: this.twitter,
              facebook: this.facebook,
              instagram: this.instagram,
              discord: this.discord,
              deviant_art: this.deviant_art,
              reddit: this.reddit,
              patreon: this.patreon,
              tumblr: this.tumblr,

              youtube: this.youtube,
              twitch: this.twitch,
              battle_net: this.battle_net,
              steam: this.steam,
              playstation: this.playstation,
              nintendo_switch: this.nintendo_switch,
              xbox: this.xbox,
              update: 'social'
            })
    		    .then((response) => { 
    		    	if((response.status === 401) || (response.status === 403) || (response.status === 404)) { 
                this.$router.replace({ name: 'Home'}) 
                flash('Unauthorized to Update Settings', 'error');
              }
              else if(response.status === 200) { 
                flash('Settings Updated', 'success');
                this.$store.commit('updateUser', response.data);
              }
    		    })
    		    .catch((error) => this.$router.replace({ name: 'Home'}) );
  	  	}
  	},
    created() {
      this.updateSettings()
    }
}
</script>