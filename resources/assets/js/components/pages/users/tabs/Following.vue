<template>
<div>
 	<div class="content">
        <h2>Following</h2>
    </div>

	<div v-if="followingLoading">
		<center><i class="fas fa-spinner fa-spin fa-2x"></i></center>
	</div>

	<div v-else-if="this.following.length > 0">
		<user-profile-component :users="following" type="following"></user-profile-component>
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
		  following: [],
		  followingLoading: true
		}
	},
	methods: {
		getFollowing() {
			axios.get('/api/users/'+ this.$route.params.id + '/following')
			.then((response) => { 
				this.following = response.data.following; 
				this.followingLoading = false; 
			})
			.catch((error) => console.log("Followers array not updated."));
		}
	},
	created() {
		this.getFollowing()
	}
}	
</script>