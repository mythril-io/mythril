<template>
	
	<div class="columns is-mobile is-multiline is-centered">
		<div class="column is-4-mobile is-2-tablet is-1-desktop" v-for="user in users" :key="user.id">
            <div class="card imageFade">
            	<div v-if="type=='followers'">
	                <router-link :to="{name: 'User', params: { id: user.follower_id }}" >
						<figure class="image is-square username-hover" v-if="user.avatar">
							<img v-lazy="$store.state.cdnURL + 'users/avatars/' + user.avatar" style="object-fit: cover;" :title="user.username">
							<div class="username-overlay"><span class="username-text">{{ user.username }}</span></div>
						</figure>

	                    <figure class="image is-square username-hover" v-else>
							<img v-lazy="$store.state.cdnURL + 'users/avatars/default.jpg'" style="object-fit: cover;" :title="user.username">
							<div class="username-overlay"><span class="username-text">{{ user.username }}</span></div>
						</figure>
	                </router-link>
            	</div>
            	<div v-else-if="type=='following'">
	                <router-link :to="{name: 'User', params: { id: user.leader_id }}" >
						<figure class="image is-square username-hover" v-if="user.avatar">
							<img v-lazy="$store.state.cdnURL + 'users/avatars/' + user.avatar" style="object-fit: cover;" :title="user.username">
							<div class="username-overlay"><span class="username-text">{{ user.username }}</span></div>
						</figure>

	                    <figure class="image is-square username-hover" v-else>
							<img v-lazy="$store.state.cdnURL + 'users/avatars/default.jpg'" style="object-fit: cover;" :title="user.username">
							<div class="username-overlay"><span class="username-text">{{ user.username }}</span></div>
						</figure>
	                </router-link>	
            	</div>
            	<div v-else>
	                <router-link :to="{name: 'User', params: { id: user.id }}">
						<figure class="image is-square username-hover" v-if="user.avatar">
							<img v-lazy="$store.state.cdnURL + 'users/avatars/' + user.avatar" style="object-fit: cover;" :title="user.username">
							<div class="username-overlay"><span class="username-text">{{ user.username }}</span></div>
						</figure>

	                    <figure class="image is-square username-hover" v-else>
							<img v-lazy="$store.state.cdnURL + 'users/avatars/default.jpg'" style="object-fit: cover;" :title="user.username">
							<div class="username-overlay"><span class="username-text">{{ user.username }}</span></div>
						</figure>
	                </router-link>
            	</div>
            </div>
		</div>
	</div>

</template>

<script>

export default {
    props: ['users', 'type'],
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
        }
	},
}
</script>