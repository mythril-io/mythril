<template>
	
	<div class="columns is-mobile is-multiline is-centered">
		<div class="column is-4-mobile is-2-tablet is-1-desktop" v-for="user in users">
            <div class="card imageFade">
            	<div v-if="type=='followers'">
	                <router-link :to="{name: 'User', params: { id: user.follower_id }}" >
	                    <figure class="image tooltip is-tooltip-primary is-1by1 imageFade" :data-tooltip="user.username" v-bind:style="avatarStyle(user.avatar)" v-if="user.avatar"></figure>
	                    <figure class="image tooltip is-tooltip-primary is-1by1 imageFade" :data-tooltip="user.username" v-bind:style="avatarStyle('default.jpg')" v-else></figure>
	                </router-link>
            	</div>
            	<div v-else-if="type=='following'">
	                <router-link :to="{name: 'User', params: { id: user.leader_id }}" >
	                    <figure class="image tooltip is-tooltip-primary is-1by1 imageFade" :data-tooltip="user.username" v-bind:style="avatarStyle(user.avatar)" v-if="user.avatar"></figure>
	                    <figure class="image tooltip is-tooltip-primary is-1by1 imageFade" :data-tooltip="user.username" v-bind:style="avatarStyle('default.jpg')" v-else></figure>
	                </router-link>	
            	</div>
            	<div v-else>
	                <router-link :to="{name: 'User', params: { id: user.id }}">
	                    <figure class="image tooltip is-tooltip-primary is-1by1 imageFade" :data-tooltip="user.username" v-bind:style="avatarStyle(user.avatar)" v-if="user.avatar"></figure>
	                    <figure class="image tooltip is-tooltip-primary is-1by1 imageFade" :data-tooltip="user.username" v-bind:style="avatarStyle('default.jpg')" v-else></figure>
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
                backgroundImage: 'url(https://mythril.nyc3.digitaloceanspaces.com/users/avatars/' + avatar + ')',
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