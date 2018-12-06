<template>
<div>
	
	<h1 class="title">Recent User Activity</h1>
	
	<div class="columns is-multiline">
		<div class="column is-6-tablet is-4-desktop" v-for="activity in recentUserActivity">
			<div class="notification hover-translate">
			  <article class="media">
			    <div class="media-left">
			    	<router-link :to="{name: 'User', params: { id: activity.user.id }}">	
				      <figure class="card image imageFade is-64x64">
	                    <div v-bind:style="avatarStyle(activity.user.avatar)" v-if="activity.user.avatar"></div>
	                    <div v-bind:style="avatarStyle('default.jpg')" v-else></div>
				      </figure>
			  		</router-link>
			    </div>
			    <div class="media-content">
			      <div class="content">
			        <p>
			          <router-link style="text-decoration:none" :to="{name: 'User', params: { id: activity.user.id }}"><strong>{{ activity.user.username }}</strong></router-link>
			          	<small>{{ ago(activity.created_at) }}</small>
			          <br>
			          <em>{{ activity.play_status.name }}:</em> <router-link class="has-text-primary" style="text-decoration:none" :to="{name: 'Game', params: { id: activity.game.id }}">{{ getGameTitle(activity) }}</router-link>
			        </p>
			      </div>
<!-- 			      <nav class="level is-mobile">
			        <div class="level-left">
			          <a class="level-item">
			            <span class="icon is-small"><i class="fas fa-reply"></i></span>
			          </a>
			          <a class="level-item">
			            <span class="icon is-small"><i class="fas fa-retweet"></i></span>
			          </a>
			          <a class="level-item">
			            <span class="icon is-small"><i class="fas fa-heart"></i></span>
			          </a>
			        </div>
			      </nav> -->
			    </div>
			  </article>
			</div>
		</div>
	</div>


</div>
</template>

<script>
var moment = require('moment-timezone');

export default {
    props: ['recentUserActivity'],
    methods: {
        avatarStyle(avatar) {
            return {
                backgroundImage: 'url(' + this.$store.state.cdnURL + 'users/avatars/' + avatar + ')',
                backgroundSize: 'cover',
                backgroundPosition: 'center top', 
                backgroundRepeat: 'no-repeat',
                width: '100%',
                height: '100%'
            }
        },
        bannerStyle(banner) {
            return {
                backgroundImage: 'linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url(' + this.$store.state.cdnURL + 'games/banners/'+ banner +')',
                backgroundSize: 'cover',
                backgroundPosition: 'center', 
                backgroundRepeat: 'no-repeat',
                color: '#FFF',
            }
        },
        ago(date) {
            if(this.user) {
                if(this.user.timezone) {
                    return moment.utc(date).tz(this.user.timezone).fromNow();
                }               
            }
            return moment.utc(date).local().fromNow();
        },
        getGameTitle(activity) {
        	return activity.release.alternate_title ? activity.release.alternate_title : activity.game.title
        }
    }
}
</script>