<template>
<div>
	
	<section class="hero is-primary">
	  <div class="hero-body">
	    <div class="container">
			<h1 class="title has-text-centered-desktop">
				Staff
			</h1>
			<h2 class="subtitle has-text-centered-desktop">
				<span class="has-text-grey-dark is-size-6">
					View the profiles of those who work on Mythril
				</span>
			</h2>
	    </div>
	  </div>
	</section>
	<section class="section">
		<div class="container">	
			<article class="message">
			  <div class="message-body">
			    <strong>Administrator/Creator</strong>
			  </div>
			</article>
			<div class="columns is-mobile">
				<div class="column is-4-mobile is-3-tablet is-2-desktop">
							<router-link :to="{name: 'User', params: { id: 1 }}">
								<b-tooltip label="Cloud">
									<figure class="card image imageFade item-shadow">
										<div class="text-container">
											<img v-lazy="$store.state.cdnURL + 'users/avatars/1.png'">
										</div>
									</figure>
								</b-tooltip>
							</router-link>
				</div>
			</div>
		</div>
	</section>

</div>
</template>

<script>
export default {
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