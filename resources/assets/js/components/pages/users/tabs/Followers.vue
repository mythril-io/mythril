<template>
<div>
 	<div class="content">
        <h2>Followers</h2>
    </div>

	<div v-if="followersLoading">
		<center><i class="fas fa-spinner fa-spin fa-2x"></i></center>
	</div>

	<div v-else-if="this.followers.length > 0">
		<user-profile-component :users="followers" type="followers"></user-profile-component>
    </div>

    <article class="message is-warning" v-else>
      <div class="message-body">
        No users found.
      </div>
    </article>

</div>
</template>

<script>
import UserProfileComponent from '../../components/UserProfileComponent.vue';

export default {
    props: ['displayedUser'],
    components: { UserProfileComponent },
	data() {
		return {
		  followers: [],
		  followersLoading: true
		}
	},
	methods: {
		getFollowers() {
			axios.get('/api/users/'+ this.$route.params.id + '/followers')
			.then((response) => { 
				this.followers = response.data.followers; 
				this.followersLoading = false; 
			})
			.catch((error) => console.log("Followers array not updated."));
		}
	},
	created() {
		this.getFollowers()
	}
}	
</script>