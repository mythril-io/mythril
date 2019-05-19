<template>
<div>
	
	<h1 class="title">Recent User Activity</h1>
	
	<div class="columns is-multiline">
		<div class="column is-6-tablet is-4-desktop" v-for="activity in recentUserActivity" :key="activity.id">
			<div class="notification border-hover">
			  <article class="media">
			    <div class="media-left">
						<user-avatar 
								:user="activity.user"
								avatarSize="is-64x64">
						</user-avatar>
			    </div>
			    <div class="media-content">
			      <div class="content">
			        <p>
			          <router-link style="text-decoration:none" :to="{name: 'User', params: { id: activity.user.id }}"><strong>{{ activity.user.username }}</strong></router-link>
			          	<small>{{ activity.created_at | ago($store.user) }}</small>
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
import UserAvatar from '../components/UserAvatar.vue';

export default {
		props: ['recentUserActivity'],
    components: {UserAvatar},
    methods: {
        getGameTitle(activity) {
        	return activity.release.alternate_title ? activity.release.alternate_title : activity.game.title
        }
    }
}
</script>